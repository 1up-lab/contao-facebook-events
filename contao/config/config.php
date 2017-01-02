<?php

$GLOBALS['BE_MOD']['content']['calendar']['update_events'] = [
    'Oneup\Contao\FacebookEvents\Cron\FacebookEventsAutomator',
    'updateEvents',
];

// Add Module for debuging purposes
$GLOBALS['FE_MOD']['events']['facebook_events_synchronizer'] = 'Oneup\Contao\FacebookEvents\Module\ModuleFacebookEvents';

// Register Cron job
$GLOBALS['TL_CRON']['daily'][] = [
    'Oneup\Contao\FacebookEvents\Cron\FacebookEventsAutomator',
    'synchronizeCalendars',
];
