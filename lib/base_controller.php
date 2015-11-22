<?php

  class BaseController{

    public static function get_user_logged_in(){
        if (isset($_SESSION['user'])) {
            return User::find($_SESSION['user']);
        }
      return null;
    }

    public static function check_logged_in(){
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'Kirjaudu sisään ensin!'));
        }
    }

  }
