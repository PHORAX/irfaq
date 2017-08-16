<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:irfaq/Resources/Private/Language/locallang_db.xlf:tx_irfaq_cat',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'languageField' => 'sys_language_uid',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'fe_group' => 'fe_group',
        ],
        'iconfile' => 'EXT:irfaq/Resources/Public/Icons/icon_tx_irfaq_cat.gif',
        'searchFields' => 'title',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,fe_group,title'
    ],
    'types' => [
        '0' => ['showitem' => 'hidden,--palette--;;1,title,shortcut']
    ],
    'palettes' => [
        '1' => ['showitem' => 'fe_group']
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => '0'
            ]
        ],
        'fe_group' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                    ['LLL:EXT:lang/locallang_general.php:LGL.hide_at_login', -1],
                    ['LLL:EXT:lang/locallang_general.php:LGL.any_login', -2],
                    ['LLL:EXT:lang/locallang_general.php:LGL.usergroups', '--div--']
                ],
                'foreign_table' => 'fe_groups'
            ]
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            ]
        ],
        'shortcut' => [
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.shortcut_page',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'foreign_table' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1'
            ]
        ],
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.php:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.php:LGL.default_value', 0]
                ]
            ]
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_irfaq_cat',
                'foreign_table_where' => 'AND tx_irfaq_cat.uid=###REC_FIELD_l18n_parent### AND tx_irfaq_cat.sys_language_uid IN (-1,0)',
            ]
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
    ],
];