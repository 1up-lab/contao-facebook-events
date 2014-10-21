<?php

ClassLoader::addNamespaces(array(
    'Oneup',
    'Oneup\FacebookEvents',
    'Oneup\FacebookEvents\Module'
));

ClassLoader::addClasses(array
(
	// Classes
	'Oneup\FacebookEvents\Synchronizer'   => 'system/modules/facebook-events/classes/Synchronizer.php',
    'Oneup\FacebookEvents\EventProcessor' => 'system/modules/facebook-events/classes/EventProcessor.php',

    // Go eat shit
    'FacebookEventsAutomator'             => 'system/modules/facebook-events/FacebookEventsAutomator.php',

    // Modules
    'Oneup\FacebookEvents\Module\ModuleFacebookEvents' => 'system/modules/facebook-events/modules/ModuleFacebookEvents.php',
));
