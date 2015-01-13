<?php

$GLOBALS['TL_DCA']['tl_calendar']['list']['operations'] += array(
    'facebook_update' => array
    (
        'label'               => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_update'],
        'button_callback'     => array('tl_facebook_events', 'synchronizeCalendars'),
    ),
);

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
        'sql'
        => "varchar(255) NOT NULL default ''"
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
    'facebook_author' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_author'],
        'default'                 => BackendUser::getInstance()->id,
        'exclude'                 => true,
        'filter'                  => true,
        'sorting'                 => true,
        'flag'                    => 1,
        'inputType'               => 'select',
        'foreignKey'              => 'tl_user.name',
        'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'mandatory'=>true, 'includeBlankOption'=>true, 'cols'=>4, 'tl_class'=>'w50'),
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
        'relation'                => array('type'=>'hasOne', 'load'=>'eager')
    ),
    'facebook_update_time' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['facebook_update_time'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'cols'=>4, 'tl_class'=>'w50'),
        'sql'                     => "varchar(255) NOT NULL default '-1'"
    ),
);

// Register checkbox
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default'] = str_replace('jumpTo;', 'jumpTo;{facebook_legend:hide},facebook_synced;', $GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']);

// Register subpalettes
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['__selector__'][] = 'facebook_synced';
$GLOBALS['TL_DCA']['tl_calendar']['subpalettes'] += array
(
    'facebook_synced' => 'facebook_page,facebook_appid,facebook_secret,facebook_author,facebook_update_time,facebook_size,facebook_imagemargin,facebook_floating'
);

class tl_facebook_events extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function synchronizeCalendars($href, $label, $title, $class, $attributes)
    {
        if ($href['facebook_synced'] !== '1') {
            return '';
        }

        $href = 'key=update_events';

        return ($this->User->isAdmin || !empty($this->User->calendars) || $this->User->hasAccess('create', 'calendars')) ? '<a href="'.$this->addToUrl($href).'" class="'.$class.'" title="'.specialchars($title).'"'.$attributes.'><img src="system/modules/facebook-events/assets/img/update_events.png" width="16" height="16" alt="'.$label.'"></a> ' : '';
    }
}
