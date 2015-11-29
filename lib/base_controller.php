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
    
    public static function is_admin() {
        return isset($_SESSION['admin']);
    }
    
    public static function is_customer() {
        return isset($_SESSION['user']);
    }

    public static function check_logged_in(){
        if (!isset($_SESSION['admin'])) {
            Redirect::to('/admin/login', array('message' => 'Kirjaudu sisään ensin!'));
        }
    }
  }
