<?php

class AdminController extends BaseController {
    public static function login() {
        View::make('admin/login.html');
    }

    public static function handle_login() {
        $params = $_POST;
        $_SESSION['admin'] = null;
        $_SESSION['user'] = null;
        $admin = Admin::authenticate($params['email'], $params['password']);

        if (!$admin) {
            View::make('admin/login.html', array('message' => 'Väärä käyttäjätunnus tai salasana!', 'email' => $params['email']));
        } else {
            $_SESSION['admin'] = $admin->email;
            Redirect::to('/', array('message' => 'Tervetuloa ' . $admin->name . '!'));
        }
    }
}
