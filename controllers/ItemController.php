<?php

class ItemController extends Controller {


    function list() {
        return self::$twig->render('item/list.html.twig');
    }
}