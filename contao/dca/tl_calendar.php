<?php

$GLOBALS['TL_DCA']['tl_calendar']['list']['operations'] += [
    'facebookEvents_update' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_update'],
        'button_callback' => ['Oneup\Contao\FacebookEvents\Dca\FacebookEvents', 'synchronizeCalendars'],
    ],
];

$GLOBALS['TL_DCA']['tl_calendar']['fields'] += [
    'facebookEvents_synced' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_synced'],
        'exclude' => true,
        'filter' => true,
        'inputType' => 'checkbox',
        'eval' => [
            'submitOnChange' => true,
        ],
        'sql' => "char(1) NOT NULL default ''",
    ],
    'facebookEvents_appId' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_appId'],
        'exclude' => true,
        'search' => true,
        'inputType' => 'text',
        'eval' => [
            'mandatory' => true,
            'maxlength' => 255,
            'cols' => 4,
            'tl_class' => 'w50',
        ],
        'sql' => "varchar(255) NOT NULL default ''",
    ],
    'facebookEvents_secret' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_secret'],
        'exclude' => true,
        'search' => true,
        'inputType' => 'text',
        'eval' => [
            'mandatory' => true,
            'maxlength' => 255,
            'cols' => 4,
            'tl_class' => 'w50',
        ],
        'sql' => "varchar(255) NOT NULL default ''",
    ],
    'facebookEvents_accessToken' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_accessToken'],
        'exclude' => true,
        'search' => true,
        'inputType' => 'text',
        'eval' => [
            'mandatory' => true,
            'maxlength' => 255,
            'cols' => 4,
            'tl_class' => 'w50',
        ],
        'sql' => "varchar(255) NOT NULL default ''",
    ],
    'facebookEvents_page' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_page'],
        'exclude' => true,
        'search' => true,
        'inputType' => 'text',
        'eval' => [
            'mandatory' => true,
            'maxlength' => 255,
        ],
        'sql' => "varchar(255) NOT NULL default ''",
    ],
    'facebookEvents_size' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_size'],
        'exclude' => true,
        'inputType' => 'imageSize',
        'options' => System::getImageSizes(),
        'reference' => &$GLOBALS['TL_LANG']['MSC'],
        'eval' => [
            'rgxp' => 'digit',
            'includeBlankOption' => true,
            'nospace' => true,
            'helpwizard' => true,
            'tl_class' => 'w50',
        ],
        'sql' => "varchar(64) NOT NULL default ''",
    ],
    'facebookEvents_imageMargin' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_imagemargin'],
        'exclude' => true,
        'inputType' => 'trbl',
        'options' => [
            'px',
            '%',
            'em',
            'rem',
            'ex',
            'pt',
            'pc',
            'in',
            'cm',
            'mm',
        ],
        'eval' => [
            'includeBlankOption' => true,
            'tl_class' => 'w50',
        ],
        'sql' => "varchar(128) NOT NULL default ''",
    ],
    'facebookEvents_floating' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_floating'],
        'default' => 'above',
        'exclude' => true,
        'inputType' => 'radioTable',
        'options' => [
            'above',
            'left',
            'right',
            'below',
        ],
        'eval' => [
            'cols' => 4,
            'tl_class' => 'w50',
        ],
        'reference' => &$GLOBALS['TL_LANG']['MSC'],
        'sql' => "varchar(32) NOT NULL default ''",
    ],
    'facebookEvents_author' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_author'],
        'default' => \BackendUser::getInstance()->id,
        'exclude' => true,
        'filter' => true,
        'sorting' => true,
        'flag' => 1,
        'inputType' => 'select',
        'foreignKey' => 'tl_user.name',
        'eval' => [
            'doNotCopy' => true,
            'chosen' => true,
            'mandatory' => true,
            'includeBlankOption' => true,
            'cols' => 4,
            'tl_class' => 'w50',
        ],
        'sql' => "int(10) unsigned NOT NULL default '0'",
        'relation' => [
            'type' => 'hasOne',
            'load' => 'eager',
        ],
    ],
    'facebookEvents_updateTime' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['facebookEvents_updateTime'],
        'exclude' => true,
        'search' => true,
        'inputType' => 'text',
        'eval' => [
            'mandatory' => true,
            'maxlength' => 255,
            'cols' => 4,
            'tl_class' => 'w50',
        ],
        'sql' => "varchar(255) NOT NULL default '-1'",
    ],
    'facebookEvents_enableCron' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['facebookEvents_enableCron'],
        'exclude' => true,
        'filter' => true,
        'inputType' => 'checkbox',
        'eval' => [
            'submitOnChange' => true,
        ],
        'sql' => "char(1) NOT NULL default ''",
    ],
];

// Register checkbox
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default'] = str_replace(
    'jumpTo;',
    'jumpTo;{facebookEvents_legend:hide},facebookEvents_synced;',
    $GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']
);

// Register subpalettes
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['__selector__'][] = 'facebookEvents_synced';
$GLOBALS['TL_DCA']['tl_calendar']['subpalettes'] += [
    'facebookEvents_synced' => 'facebookEvents_page,facebookEvents_appId,facebookEvents_accessToken,facebookEvents_secret,facebookEvents_author,
                                facebookEvents_updateTime,facebookEvents_size,facebookEvents_imageMargin,
                                facebookEvents_floating,facebookEvents_enableCron',
];
