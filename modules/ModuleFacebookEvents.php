<?php

namespace Oneup\FacebookEvents\Module;

use Contao\Database;
use Oneup\FacebookEvents\Automator;

class ModuleFacebookEvents extends \Module
{
    public function compile()
    {
        (new Automator())->synchronizeCalendars();
    }
}