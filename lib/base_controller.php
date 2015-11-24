<?php

  class BaseController{

    public static function get_user_logged_in(){
        if (isset($_SESSION['admin'])) {
            return Admin::findByEmail($_SESSION['admin']);
        }
        if (isset($_SESSION['user'])) {
            return Customer::findByEmail($_SESSION['user']);
        }
      return null;
    }

    public static function check_logged_in(){
        if (!isset($_SESSION['user'])) {
            Redirect::to('/customer/login', array('message' => 'Kirjaudu sisään ensin!'));
        }
    }
  }
