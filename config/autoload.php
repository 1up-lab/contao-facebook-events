<?php

ClassLoader::addNamespaces(array(
    'Oneup',
    'Oneup\FacebookEvents',
    'Oneup\FacebookEvents\Module'
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Oneup\FacebookEvents\Synchronizer'   => 'system/modules/facebook-events/classes/Synchronizer.php',
    'Oneup\FacebookEvents\EventProcessor' => 'system/modules/facebook-events/classes/EventProcessor.php',

    // Modules
    'Oneup\FacebookEvents\Module\ModuleFacebookEvents' => 'system/modules/facebook-events/modules/ModuleFacebookEvents.php',
));


/*
TemplateLoader::addFiles(array
(
	'mod_faqlist'   => 'system/modules/faq/templates/modules',
	'mod_faqpage'   => 'system/modules/faq/templates/modules',
	'mod_faqreader' => 'system/modules/faq/templates/modules',
));
*/