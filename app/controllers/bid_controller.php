<?php

class BidController extends BaseController {

    public static function store() {
        $params = $_POST;
        $attributes = array(
            'customer' => self::get_user_logged_in()->id,
            'auction' => $params['kauppa_id'],
            'price' => $params['hinta']
        );
        $bid = new Bid($attributes);
        $errors = $bid->errors();

        if (count($errors) == 0) {
            $bid->save();
            Redirect::to('/items/' . $params['tuote_id'], array('message' => 'Uusi tarjous on lisätty tietokantaan!'));
        } else {
            Redirect::to('/items/' . $params['tuote_id'], array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function destroy($customer, $auction, $time) {
        $bid = new Bid(array(
            'customer' => $customer,
            'auction' => $auction,
            'time' => $time));
        $bid->destroy();
        Redirect::to('/', array('message' => 'Tarjous on poistettu tietokannasta!'));
    }

}
