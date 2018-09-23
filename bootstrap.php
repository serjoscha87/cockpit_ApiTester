<?php
/*
 * shorthand the path the the groups addon for later usage
 */
$app->path('apitester', 'addons/ApiTester/');

// ADMIN
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {
   include_once(__DIR__ . '/admin.php');
}