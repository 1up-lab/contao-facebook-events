<?php

namespace Oneup\FacebookEvents\Module;

use Contao\Database;
use FacebookEventsAutomator;

class ModuleFacebookEvents extends \Module
{
    public function compile()
    {
        (new FacebookEventsAutomator())->synchronizeCalendars();
    }
}
