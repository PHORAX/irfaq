<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tx_irfaq_q=1');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tx_irfaq_cat=1');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tx_irfaq_expert=1');



//listing FAQ in Web->Page view
$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_irfaq_q'][0] = [
    'fList' => 'q,a,q_from,expert',
    'icon' => true
];



// Core DataHandler hooks for managing related entries
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['irfaq']
    = 'EXT:irfaq/Classes/System/DataHandling/RelatedQuestionsDataHandler.php'
        . ':Netcreators\Irfaq\System\Backend\DataHandling\RelatedQuestionsDataHandler';
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['irfaq']
    = 'EXT:irfaq/Classes/System/DataHandling/RelatedQuestionsDataHandler.php'
        . ':Netcreators\Irfaq\System\Backend\DataHandling\RelatedQuestionsDataHandler';



// Hook to comments for comments closing
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['comments']['closeCommentsAfter'][$_EXTKEY]
    = 'EXT:irfaq/Classes/Hooks/Comments/CommentsCloseTimeHook.php'
        . ':Netcreators\Irfaq\Hooks\Comments\CloseCommentsAfterHook->irfaqHook';



// Backend Page Module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['irfaq_pi1'][]
    = 'EXT:irfaq/Classes/System/Backend/PageModule/ExtensionSummaryProvider.php'
        . ':Netcreators\Irfaq\System\Backend\PageModule\ExtensionSummaryProvider->getExtensionSummary';

