<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function item_list() {
        View::make('suunnitelmat/item_list.html');
    }

    public static function item_show() {
        View::make('suunnitelmat/item_show.html');
    }

    public static function item_edit() {
        View::make('suunnitelmat/item_edit.html');
    }

    public static function login() {
        View::make('login.html');
    }

    public static function sandbox() {
        $tv = new Item(array(
            'name' => 'tv',
            'price' => 0,
            'description' => 'none'
        ));
        $errors = $tv->errors();

        Kint::dump($errors);
    }

}
