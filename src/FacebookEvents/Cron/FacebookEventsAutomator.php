<?php

namespace Oneup\Contao\FacebookEvents\Cron;

use Contao\Backend;
use Contao\Database;
use Oneup\Contao\FacebookEvents\Synchronizer;

class FacebookEventsAutomator extends Backend
{
    public function __construct()
    {
        parent::__construct();
    }

    public function synchronizeCalendars()
    {
        $database = Database::getInstance();
        $calendars = $database->prepare("
            SELECT
                id, facebookEvents_appId, facebookEvents_secret, facebookEvents_page, facebookEvents_size, facebookEvents_imageMargin, facebookEvents_floating, facebookEvents_author, facebookEvents_updateTime
            FROM
                tl_calendar
            WHERE
                facebookEvents_synced = '1'
        ")->execute();

        while ($calendars->next()) {
            $synchronizer = new Synchronizer([
                'id'            => $calendars->facebookEvents_appId,
                'secret'        => $calendars->facebookEvents_secret,
                'page'          => $calendars->facebookEvents_page,
                'author'        => $calendars->facebookEvents_author,
                'imageSize'     => $calendars->facebookEvents_size,
                'imageMargin'   => $calendars->facebookEvents_imageMargin,
                'imageFloating' => $calendars->facebookEvents_floating,
                'updateTime'    => $calendars->facebookEvents_updateTime,
                'calendar'      => $calendars->id,
                'apiVersion'    => 'v2.8',
            ]);

            $synchronizer->run();
        }
    }

    public function updateEvents()
    {
        $this->synchronizeCalendars();
        $this->redirect($this->getReferer());
    }
}
