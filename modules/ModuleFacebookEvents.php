<?php

namespace Oneup\FacebookEvents\Module;

use Contao\Database;
use Oneup\FacebookEvents\Synchronizer;

class ModuleFacebookEvents extends \Module
{
    public function compile()
    {
        $database = Database::getInstance();
        $calendars = $database->prepare("
            SELECT
                id, facebook_appid, facebook_secret, facebook_page, facebook_size, facebook_imagemargin, facebook_floating, facebook_author
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
                'calendar'      => $calendars->id,
            ]);

            $synchronizer->run();
        }

        die();
    }
}