<?php
/*
 * Default controller
 */

abstract class Controller
{
    protected static $twig = false;

    public function __construct()
    {
        if (!self::$twig) {
            $loader = new Twig_Loader_Filesystem(__DIR__ . '/../views/templates');
            self::$twig = new Twig_Environment($loader, array(
                'cache' => false,
            ));
            $extension = new \Umpirsky\Twig\Extension\PhpFunctionExtension();
            $extension->allowFunction('L');
            $extension->allowFunction('getBasePath');
            $extension->allowFunction('getUser');
            $extension->allowFunction('getUserAccess');
            $extension->allowFunction('path');
            $extension->allowFunction('getHTMLSessionInfo');
            $extension->allowFunction('getCurrentRoute');
            $extension->allowFunction('stringToFormatDate');
            $extension->allowFunction('stringToFormatDateTime');
            self::$twig->addExtension($extension);
        }
    }
}
