<?php

$GLOBALS['TL_DCA']['tl_calendar']['fields'] += array(
    'facebook_synced' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_synced'],
        'exclude'                 => true,
        'filter'                  => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('submitOnChange'=>true),
        'sql'                     => "char(1) NOT NULL default ''"
    ),
    'facebook_appid' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_appid'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'cols'=>4, 'tl_class'=>'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''"
    ),
    'facebook_secret' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_secret'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'cols'=>4, 'tl_class'=>'w50'),
        'sql'                     => "varchar(255) NOT NULL default ''"
    ),
    'facebook_page' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_page'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
        'sql'                     => "varchar(255) NOT NULL default ''"
    ),
    'facebook_size' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_size'],
        'exclude'                 => true,
        'inputType'               => 'imageSize',
        'options'                 => $GLOBALS['TL_CROP'],
        'reference'               => &$GLOBALS['TL_LANG']['MSC'],
        'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
        'sql'                     => "varchar(64) NOT NULL default ''"
    ),
    'facebook_imagemargin' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_imagemargin'],
        'exclude'                 => true,
        'inputType'               => 'trbl',
        'options'                 => array('px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
        'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
        'sql'                     => "varchar(128) NOT NULL default ''"
    ),
    'facebook_floating' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_floating'],
        'default'                 => 'above',
        'exclude'                 => true,
        'inputType'               => 'radioTable',
        'options'                 => array('above', 'left', 'right', 'below'),
        'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
        'reference'               => &$GLOBALS['TL_LANG']['MSC'],
        'sql'                     => "varchar(32) NOT NULL default ''"
    ),
);

// Register checkbox
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default'] = str_replace('jumpTo;', 'jumpTo;{facebook_legend:hide},facebook_synced;', $GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']);

// Register subpalettes
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['__selector__'][] = 'facebook_synced';
$GLOBALS['TL_DCA']['tl_calendar']['subpalettes'] += array
(
    'facebook_synced' => 'facebook_page,facebook_appid,facebook_secret,facebook_size,facebook_imagemargin,facebook_floating'
);
