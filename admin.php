<?php
require_once(__DIR__ . '/Controller/ApiTester.php');

/**
 * register assets
 */
$app['app.assets.base'] = array_merge($app['app.assets.base'], [
    'apitester:assets/css/vendor/uikit/theme__accordion.css',
    '/assets/lib/uikit/js/components/accordion.min.js',
    'apitester:assets/css/apitester.css'
]);

/**
 * register routes
 */
$app->bind('/apitester', function() {
   return $this->invoke('Cockpit\\Controller\\ApiTester', 'apitester');
});
$app->bindClass('Cockpit\\Controller\\ApiTester', 'apitester');

/**
 * on admint init
 */
$app->on('admin.init', function() {}, 0);

/*
 * add menu entry if the user has access to group stuff
 */
$this->on('cockpit.menu.aside', function() {
   if ($this->module('cockpit')->hasaccess('cockpit', 'apitester')) {
      $this->renderView("apitester:views/partials/menu.php");
   }
});

// ...
$app('admin')->init();
