<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function sandbox() {
        
            
        Kint::dump(Auction::find(1));
    }
    
    public static function clear_session() {
        $_SESSION['admin'] = null;
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }


}
