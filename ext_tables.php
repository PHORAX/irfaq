<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_irfaq_q');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_irfaq_cat');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_irfaq_expert');



$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';



// Adding sysfolder icon
$TCA['pages']['columns']['module']['config']['items'][$_EXTKEY]['0']
    = 'LLL:EXT:irfaq/Resources/Private/Language/locallang_db.xlf:tx_irfaq.sysfolder';
$TCA['pages']['columns']['module']['config']['items'][$_EXTKEY]['1'] = $_EXTKEY;



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript/',
    'IRFAQ default TS'
);



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    ['LLL:EXT:irfaq/Resources/Private/Language/locallang_db.xlf:tt_content.list_type_pi1', $_EXTKEY . '_pi1'],
    'list_type'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $_EXTKEY . '_pi1',
    'FILE:EXT:irfaq/Configuration/FlexForms/flexform_ds.xml'
);



if (TYPO3_MODE == 'BE') {
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['Netcreators\\Irfaq\\System\\Backend\\WizardIcon'] =
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('irfaq')
            . 'Classes/System/Backend/WizardIcon.php';

    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tcarecords-pages-contains-irfaq',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        ['source' => 'EXT:irfaq/Resources/Public/Icons/icon_tx_irfaq_sysfolder.gif']
    );
}
