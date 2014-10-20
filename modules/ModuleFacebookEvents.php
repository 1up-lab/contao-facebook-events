<?php

namespace Oneup\FacebookEvents\Module;

use Contao\Database;
use Oneup\FacebookEvents\Synchronizer;

class ModuleFacebookEvents extends \Module
{
    public function compile()
    {
        $database = Database::getInstance();
        $calendars = $database->prepare("SELECT id, facebook_appid, facebook_secret, facebook_page, facebook_image_size FROM tl_calendar WHERE facebook_synced = '1'")->execute();

        while ($calendars->next()) {
            $synchronizer = new Synchronizer([
                'id'        => $calendars->facebook_appid,
                'secret'    => $calendars->facebook_secret,
                'page'      => $calendars->facebook_page,
                'imageSize' => $calendars->facebook_image_size,
                'calendar'  => $calendars->id,
            ]);

            $synchronizer->run();
        }

        die();
    }
}