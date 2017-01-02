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
                id, facebook_appid, facebook_secret, facebook_page, facebook_size, facebook_imagemargin, facebook_floating, facebook_author, facebook_update_time
            FROM
                tl_calendar
            WHERE
                facebook_synced = '1'
        ")->execute();

        while ($calendars->next()) {
            $synchronizer = new Synchronizer([
                'id'            => $calendars->facebook_appid,
                'secret'        => $calendars->facebook_secret,
                'page'          => $calendars->facebook_page,
                'author'        => $calendars->facebook_author,
                'imageSize'     => $calendars->facebook_size,
                'imageMargin'   => $calendars->facebook_imagemargin,
                'imageFloating' => $calendars->facebook_floating,
                'updateTime'    => $calendars->facebook_update_time,
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
