<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('home.html');
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
    
    public static function logout() {
        $_SESSION['admin'] = null;
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }


}
