<?php

class CustomerController extends BaseController {

    public static function index() {
        $customers = Customer::all();
        View::make('customer/index.html', array('customers' => $customers));
    }

    public static function show($id) {
        $customer = Customer::find($id);
        View::make('customer/show.html', array('customer' => $customer));
    }

    public static function create() {
        View::make('customer/new.html');
    }

    public static function store() {
        $params = $_POST;
        $attributes = array(
            'name' => $params['nimi'],
            'email' => $params['sähköposti'],
            'password' => $params['salasana'],
            'address' => $params['osoite'],
            'phone' => $params['puhelin']
        );
        $customer = new Customer($attributes);
        $errors = $customer->errors();

        if (count($errors) == 0) {
            $customer->save();
            Redirect::to('/', array('message' => 'Kirjaudu sisään!'));
        } else {
            View::make('customer/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['nimi'],
            'email' => $params['sähköposti'],
            'password' => $params['salasana'],
            'address' => $params['osoite'],
            'phone' => $params['puhelin']
        );
        $customer = new Customer($attributes);
        $errors = $customer->errors();

        if (count($errors) == 0) {
            $customer->update();
            Redirect::to('/customers/' . $customer->id, array('message' => 'Tietojen muokkaus onnistui!'));
        } else {
            View::make('customer/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        $customer = Customer::find($id);
        View::make('customer/edit.html', array('customer' => $customer));
    }

    public static function destroy($id) {
        $tuote = new Customer(array('id' => $id));
        $tuote->destroy();
        Redirect::to('/customers', array('message' => 'Tilisi on sulettu!'));
    }

    public static function login() {
        View::make('customer/login.html');
    }
    
    public static function handle_login() {
        $params = $_POST;
        $customer = Customer::authenticate($params['email'], $params['password']);
        $_SESSION['admin'] = null;
        $_SESSION['user'] = null;

        if (!$customer) {
            View::make('customer/login.html', array('message' => 'Väärä käyttäjätunnus tai salasana!', 'email' => $params['email']));
        } else {
            $_SESSION['user'] = $customer->email;
            Redirect::to('/', array('message' => 'Tervetuloa ' . $customer->name . '!'));
        }
    }
}
