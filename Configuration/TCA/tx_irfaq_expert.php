<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:irfaq/Resources/Private/Language/locallang_db.xlf:tx_irfaq_expert',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY crdate',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'languageField' => 'sys_language_uid',
        'delete' => 'deleted',
        'iconfile' => 'EXT:irfaq/Resources/Public/Icons/icon_tx_irfaq_expert.gif',
        'searchFields' => 'name, email, url',
    ],
    'interface' => [
        'showRecordFieldList' => 'name, email, url'
    ],
    'types' => [
        '0' => ['showitem' => 'name, email, url']
    ],
    'columns' => [
        'name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:irfaq/Resources/Private/Language/locallang_db.xlf:tx_irfaq_expert.name',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            ]
        ],
        'email' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.email',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'checkbox' => '',
                'eval' => 'nospace',
            ]
        ],
        'url' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:irfaq/Resources/Private/Language/locallang_db.xlf:tx_irfaq_expert.url',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'checkbox' => '',
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
                'foreign_table' => 'tx_irfaq_expert',
                'foreign_table_where' => 'AND tx_irfaq_expert.uid=###REC_FIELD_l18n_parent### AND tx_irfaq_expert.sys_language_uid IN (-1,0)',
            ]
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
    ],
];