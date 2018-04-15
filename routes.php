<?php

// Page d'accueil
$router->map('GET|POST', '/', function () {
    global $router;

    $ctrl = new ItemController();
    return $ctrl->list();

}, 'inventory');

$router->map('GET|POST', '/locations', function () {
    global $router;

    $ctrl = new LocationController();
    return $ctrl->list();

}, 'locations');

$router->map('POST', '/locations/delete', function () {
    global $router;

    $ctrl = new LocationController();
    return $ctrl->delete();

}, 'locations.delete');