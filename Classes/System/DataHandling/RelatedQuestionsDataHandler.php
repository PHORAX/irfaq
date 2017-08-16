<?php

namespace Netcreators\Irfaq\System\Backend\DataHandling;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007 Dmitry Dulepov (dmitry@typo3.org)
 *  (c) 2009-2017 Leonie Philine Bitto (extensions@netcreators.nl)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * A hook to TCEmain that processes related records.
 *
 * @author    Dmitry Dulepov <dmitry@typo3.org>
 * @package TYPO3
 * @subpackage irfaq
 */
class RelatedQuestionsDataHandler
{

    /** Saves original 'related' field before record update */
    protected $saved_related_items = false;

    /**
     * Saves original record's 'related' field
     *
     * @param    array $incomingFieldArray Field array
     * @param    string $table Table
     * @param    integer $id UID of the record or 'NEWxxx' string
     * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $pObj Reference to TCEmain
     * @return    void        Nothing
     */
    function processDatamap_preProcessFieldArray($incomingFieldArray, $table, $id, &$pObj)
    {
        if ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tx_irfaq']['insideTCEmain']) {
            // If we were the source of this call, ignore it
            return;
        }
        // Process only if:
        //	- correct table
        //	- we are in update operation (=$id is integer)
        //  - Below version condition added to make it compatible with Typo3 v6.1 as well -  19-07-2013

        if ($table == 'tx_irfaq_q'
            && MathUtility::canBeInterpretedAsInteger($id)
            && isset($incomingFieldArray['related'])
        ) {

            $rec = BackendUtility::getRecord($table, $id, 'related');
            $this->saved_related_items = $rec['related'];
        }
    }

    /**
     * Processes related records in tx_irfaq_q
     *
     * @param    string $status Status of the record ('new' or 'update'). Unused.
     * @param    string $table Table name
     * @param    mixed $id UID of the record or 'NEWxxx' string
     * @param    array $fieldArray Added or updated fields
     * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $pObj Reference to TCEmain
     * @return    void        Nothing
     */
    function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, &$pObj)
    {
        if ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tx_irfaq']['insideTCEmain']) {
            // If we were the source of this call, ignore it
            return;
        }
        if ($table == 'tx_irfaq_q') {
            if ($status == 'new') {
                if ($fieldArray['related']) {
                    $id = ($id{0} == '-' ? substr($id, 1) : $id);
                    /* @var $pObj \TYPO3\CMS\Core\DataHandling\DataHandler */
                    $id = $pObj->substNEWwithIDs[$id];
                    $this->process_relatedItems($id, '', $fieldArray['related'], $pObj);
                }
            } elseif (isset($fieldArray['related']) && $this->saved_related_items !== false) {
                // Processing updates only if 'related' field was changed
                $this->process_relatedItems($id, $this->saved_related_items, $fieldArray['related'], $pObj);
                $this->saved_related_items = false;
            }
        }
    }

    /**
     * Saves related items for current record
     *
     * @param    string $command Command. We are interested only in 'delete'
     * @param    string $table Table name. We work only if 'tx_irfaq_q'
     * @param    int $id Record uid
     * @param    mixed $value Unused
     * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $pObj Reference to parent object
     * @return    void        Nothing
     */
    function processCmdmap_preProcess($command, $table, $id, $value, &$pObj)
    {
        if ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tx_irfaq']['insideTCEmain']) {
            // If we were the source of this call, ignore it
            return;
        }
        if ($table == 'tx_irfaq_q' && $command == 'delete') {
            $rec = BackendUtility::getRecord($table, $id, 'related');
            $this->saved_related_items = $rec['related'];
        }
    }

    /**
     * Removes all references to deleted FAQ record
     *
     * @param    string $command Command. We are interested only in 'delete'
     * @param    string $table Table name. We work only if 'tx_irfaq_q'
     * @param    int $id Record uid
     * @param    mixed $value Unused
     * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $pObj Reference to parent object
     * @return    void        Nothing
     */
    function processCmdmap_postProcess($command, $table, $id, $value, &$pObj)
    {
        if ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tx_irfaq']['insideTCEmain']) {
            // If we were the source of this call, ignore it
            return;
        }
        /* @var $pObj \TYPO3\CMS\Core\DataHandling\DataHandler */
        if ($table == 'tx_irfaq_q' && $command == 'delete') {
            if (count($pObj->errorLog) == 0) {
                // Remove all references to this item from other items
                $this->process_relatedItems($id, $this->saved_related_items, '', $pObj);
            }
            $this->saved_related_items = false;
        }
    }

    /**
     * Processes related items for current item.
     *
     * @param    integer $id UID of current record in tx_irfaq_q
     * @param    string $oldItemList Comma-separated list of items (previous)
     * @param    string $newItemList Comma-separated list of items (new)
     * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $pObj Reference to parent object
     * @return    void        Nothing
     */
    function process_relatedItems($id, $oldItemList, $newItemList, $pObj)
    {
        $oldItemList = GeneralUtility::trimExplode(',', $oldItemList, true);
        sort($oldItemList);
        $newItemList = GeneralUtility::trimExplode(',', $newItemList, true);
        sort($newItemList);
        $diff = array_unique(
            array_merge(array_diff($oldItemList, $newItemList), array_diff($newItemList, $oldItemList))
        );
        $list = [];
        foreach ($diff as $uid) {
            if (!isset($list[$uid])) {
                $rec = BackendUtility::getRecord('tx_irfaq_q', $uid, 'related');
                if ($rec) {
                    $list[$uid] = GeneralUtility::trimExplode(',', $rec['related'], true);
                } else {
                    // No such record - dead link!
                    continue;
                }
            }
            if (in_array($uid, $oldItemList)) {
                // removed
                $key = array_search($id, $list[$uid]);
                if ($key !== false) {
                    unset($list[$uid][$key]);
                }
            } else {
                // added
                $list[$uid][] = $id;
            }
        }
        if (count($list)) {
            // Create datamap and update records using TCEmain
            $datamap = ['tx_irfaq_q' => []];
            foreach ($list as $uid => $values) {
                $datamap['tx_irfaq_q'][$uid] = [
                    'related' => implode(',', $values)
                ];
            }
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tx_irfaq']['insideTCEmain'] = true;

            /* @var $tce \TYPO3\CMS\Core\DataHandling\DataHandler */
            $tce = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
            $tce->start($datamap, null, $pObj->BE_USER);
            $tce->process_datamap();
            if (count($tce->errorLog)) {
                /* @var $pObj \TYPO3\CMS\Core\DataHandling\DataHandler */
                $pObj->errorLog = array_merge($pObj->errorLog, $tce->errorLog);
            }
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tx_irfaq']['insideTCEmain'] = false;
        }
    }
}

