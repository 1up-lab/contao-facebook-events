<?php

namespace Oneup\Contao\FacebookEvents\Module;

use Contao\Module;
use Oneup\Contao\FacebookEvents\Cron\FacebookEventsAutomator;

class ModuleFacebookEvents extends Module
{
    public function compile()
    {
        (new FacebookEventsAutomator())->synchronizeCalendars();
    }
}
