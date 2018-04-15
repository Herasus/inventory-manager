<?php
session_start();
// Load config, autoload and database
require __DIR__ . '/autoload.php';

// Internationalisation
$languages = ['fr', 'en'];

$i18n = new i18n();
$i18n->setCachePath(__DIR__ . '/tmp/lang');
$i18n->setFilePath(__DIR__ . '/lang/{LANGUAGE}.json');
$i18n->setFallbackLang('fr');
$i18n->init();

$header = [
    'title' => L::common_HOME,
    'header-title' => 'Accueil',
    'description' => 'Tableau de bord'
];
$router = new AltoRouter();
$router->setBasePath(rtrim(BASE_PATH, '/'));

require __DIR__ . '/routes.php';

$match = $router->match();

function path($url, $parameters = null) {
    global $router;
    if($parameters == null) {
        return $router->generate($url);
    }
    else {
        return $router->generate($url, $parameters);
    }
}

function redirectPath($url, $parameters = null) {
    header('Location: ' . path($url, $parameters));
}

if ($match && is_callable($match['target'])) {

    function getCurrentRoute() {
        global $match;
        return $match['name'];
    }

    $content = call_user_func_array($match['target'], $match['params']);
} else {
    // no route was matched
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    die("404 Not found");
}

echo $content;
