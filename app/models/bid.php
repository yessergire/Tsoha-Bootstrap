<?php

class Bid extends BaseModel {

    public $user, $customer, $auction, $price, $time;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_auction_is_open', 'validate_price', 'validate_time');
    }

    public static function all() {
        $query = DB::connection()->prepare('Select * from Tarjous');
        $query->execute();
        $rows = $query->fetchAll();
        $bids = array();
        foreach ($rows as $row) {
            $bids[] = new Bid(array(
                'user' => $row['meklari_id'],
                'customer' => Customer::find($row['asiakas_id']),
                'auction' => $row['kauppa_id'],
                'price' => $row['hinta'],
                'time' => $row['ajankohta']
            ));
        }
        return $bids;
    }

    
    public static function findByAuction($auction_id) {
        $query = DB::connection()->prepare('Select * from Tarjous '.
                'WHERE kauppa_id = :auction_id '.
                'ORDER BY hinta DESC');
        $query->execute(array('auction_id' => $auction_id));
        $rows = $query->fetchAll();
        $bids = array();
        foreach ($rows as $row) {
            $bids[] = new Bid(array(
                'user' => $row['meklari_id'],
                'customer' => Customer::find($row['asiakas_id']),
                'auction' => $row['kauppa_id'],
                'price' => $row['hinta'],
                'time' => $row['ajankohta']
            ));
        }
        return $bids;
    }
    
    public static function findByCustomer($customer_id) {
        $query = DB::connection()->prepare('Select * from Tarjous '.
                'WHERE asiakas_id = :customer_id '.
                'ORDER BY hinta DESC');
        $query->execute(array('customer_id' => $customer_id));
        $rows = $query->fetchAll();
        $bids = array();
        foreach ($rows as $row) {
            $bids[] = new Bid(array(
                'user' => $row['meklari_id'],
                'auction' => Auction::find($row['kauppa_id'], $lazy=true),
                'price' => $row['hinta'],
                'time' => $row['ajankohta'],
                'customer_id' => $customer_id
            ));
        }
        return $bids;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO '.
                'Tarjous (asiakas_id, kauppa_id, hinta) ' .
                'VALUES (:customer, :auction, :price)');
        $query->execute(array(
            'customer' => $this->customer,
            'auction' => $this->auction,
            'price' => $this->price
        ));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Tarjous WHERE ajankohta = :time');
        $query->execute(array(
            'time' => $this->time
        ));
    }

    public function validate_auction_is_open() {
        $errors = array();
        $auction = Auction::find($this->auction, true);
        if ($auction->closed) {
            $errors[] = 'Kauppa on suljettu!';
        }
        return $errors;
    }

    public function validate_price() {
        $errors = array();
        $auction = Auction::find($this->auction, true);
        $max = $auction->maxPrice();
        if ($max + 5 > $this->price) {
            $errors[] = 'Tarjouksen on oltava suurempi kuin ' . ($max + 5) . ' €!';
        }
        return $errors;
    }

    public function validate_time() {
        $errors = array();
        $auction = Auction::find($this->auction, true);
        
        $now = date('Y-m-d');
        if (strtotime($now) < strtotime($auction->starts)) {
            $errors[] = 'Huutokauppa ei ole vielä alkanut!';
        }

        if (strtotime($now) > strtotime($auction->ends)) {
            $errors[] = 'Huutokauppa on päättynyt!';
        }
        return $errors;
    }
    
    
}
