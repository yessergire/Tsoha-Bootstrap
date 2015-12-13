<?php

  class BaseController{

    public static function clear_session(){
        $_SESSION['admin'] = null;
        $_SESSION['user'] = null;
    }
    
    public static function logout() {
        self::clear_session();
        Redirect::to('/');
    }

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
    
    public static function is_logged_in() {
        return self::is_admin() || self::is_customer();
    }

    public static function check_logged_in(){
        if (!self::is_logged_in()) {
            Redirect::to('/', array('message' => 'Kirjaudu sisään ensin!'));
        }
    }

    public static function check_admin_logged_in(){
        if (!self::is_admin()) {
            self::clear_session();
            Redirect::to('/admin/login', array('message' => 'Kirjaudu sisään ensin!'));
        }
    }

    // Hyväksyy tietyn käyttäjän ja kaikki ylläpitäjät.
    public static function check_customer_logged_in($id){
        if (!self::is_logged_in()) {
            Redirect::to('/customer/login', array('message' => 'Kirjaudu sisään ensin!'));
        }
        if (self::is_customer() && self::get_user_logged_in()->id != $id) {
            Redirect::to('/', array('message' => 'Ei löydy!'));
        }
    }
  }
