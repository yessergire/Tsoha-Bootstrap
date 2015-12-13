<?php

class AuctionController extends BaseController {

    public static function index() {
        View::make('auction/index.html', array('auctions' => Auction::all()));
    }

    public static function show($id) {
        $auction = Auction::find($id);
        $auction->bids = Bid::findByAuction($id);
        if (isset($auction->bids[0]))
            $auction->max_price = $auction->bids[0]->price;
        View::make('auction/show.html', array('auction' => $auction));
    }

    public static function store() {
        $params = $_POST;
        $attributes = array(
            'item_id' => $params['tuote_id'],
            'starts' => $params['alkaa'],
            'ends' => $params['päättyy']
        );
        $auction = new Auction($attributes);
        $errors = $auction->errors();

        if (count($errors) == 0) {
            $auction->save();
            Redirect::to('/auctions/' . $auction->id, array('message' => 'Huutokauppa avattu!'));
        } else {
            $item = Item::find($auction->item_id);
            View::make('item/show.html', array('errors' => $errors, 'item' => $item));
        }
    }

    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'starts' => $params['alkaa'],
            'ends' => $params['päättyy']
        );
        $auction = new Auction($attributes);
        $errors = $auction->errors();

        if (count($errors) == 0) {
            $auction->update();
            Redirect::to('/auctions/' . $auction->id, array('message' => 'Huutokauppaa on muokattu onnistuneesti!'));
        } else {
            View::make('auction/edit.html', array('errors' => $errors, 'auction' => $auction));
        }
    }

    public static function edit($id) {
        View::make('auction/edit.html', array('auction' => Auction::find($id)));
    }

    public static function destroy($id) {
        $auction = new Auction(array('id' => $id));
        $auction->destroy();
        Redirect::to('/auctions', array('message' => 'Huutokauppa on poistettu tietokannasta!'));
    }

    public static function close($id) {
        $auction = new Auction(array('id' => $id));
        $auction->close();
        Redirect::to('/auctions/' . $id, array('message' => 'Huutokauppa on suljettu!'));
    }
}
