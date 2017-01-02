<?php

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    'defaultChmod;',
    'defaultChmod;{facebookEvents_legend:hide},facebookEvents_enableCron,facebookEvents_enableNotifications;',
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);

// Register subpalettes
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'facebookEvents_enableCron';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'facebookEvents_enableNotifications';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes'] += [
    'facebookEvents_enableCron' => 'facebookEvents_cronCycle'
];
$GLOBALS['TL_DCA']['tl_settings']['subpalettes'] += [
    'facebookEvents_enableNotifications' => 'facebookEvents_notificationMail,'
];
$GLOBALS['TL_DCA']['tl_settings']['fields'] += [
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
    'facebookEvents_enableNotifications' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['facebookEvents_enableNotifications'],
        'exclude' => true,
        'filter' => true,
        'inputType' => 'checkbox',
        'eval' => [
            'submitOnChange' => true,
        ],
        'sql' => "char(1) NOT NULL default ''",
    ],
    'facebookEvents_notificationMail' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['facebookEvents_notificationMail'],
        'inputType' => 'text',
        'eval' => [
            'rgxp' => 'friendly',
            'decodeEntities' => true,
        ],
    ],
    'facebookEvents_cronCycle' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['facebookEvents_cronCycle'],
        'default' => $type,
        'inputType' => 'select',
        'filter' => true,
        'options' => array_keys($GLOBALS['TL_CRON']),
        'reference' => &$GLOBALS['TL_LANG']['tl_settings'],
        'eval' => [
            'includeBlankOption' => false,
            'submitOnChange' => false,
            'mandatory' => true,
        ],
        'sql' => "varchar(32) NOT NULL default ''",
    ],
];
