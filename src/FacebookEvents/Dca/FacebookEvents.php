<?php

namespace Oneup\Contao\FacebookEvents\Dca;

use Contao\Backend;

class FacebookEvents extends Backend
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
        if ($href['facebookEvents_synced'] !== '1') {
            return '';
        }

        $href = 'key=update_events';

        return ($this->User->isAdmin || !empty($this->User->calendars) || $this->User->hasAccess('create', 'calendars')) ? '<a href="'.$this->addToUrl($href).'" class="'.$class.'" title="'.specialchars($title).'"'.$attributes.'><img src="system/modules/facebook-events/assets/img/update_events.png" width="16" height="16" alt="'.$label.'"></a> ' : '';
    }
}
