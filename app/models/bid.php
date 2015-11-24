<?php

class Bid extends BaseModel {

    public $id, $user, $customer, $auction, $price, $time;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

    public static function all() {
        $query = DB::connection()->prepare('Select * from Tarjous');
        $query->execute();
        $rows = $query->fetchAll();
        $bids = array();
        foreach ($rows as $row) {
            $bids[] = new Bid(array(
                'id' => $row['id'],
                'user' => $row['meklari_id'],
                'customer' => $row['asiakas_id'],
                'auction' => $row['kauppa_id'],
                'price' => $row['hinta'],
                'time' => $row['ajankohta']
            ));
        }
        return $bids;
    }

    public static function find($id, $get_items=true) {
        $query = DB::connection()->prepare('Select * from Tarjous Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $bid = new Bid(array(
                'id' => $row['id'],
                'user' => $row['meklari_id'],
                'customer' => $row['asiakas_id'],
                'auction' => $row['kauppa_id'],
                'price' => $row['hinta'],
                'time' => $row['ajankohta']
            ));
            return $bid;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO '.
                'Tarjous (meklari_id, asiakas_id, kauppa_id, hinta, ajankohta) ' .
                'VALUES (:user, :customer, :auction, :price, :time) RETURNING id');
        $query->execute(array(
            'user' => $this->user,
            'customer' => $this->customer,
            'auction' => $this->auction,
            'price' => $this->price,
            'time' => $this->time
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Tarjous '.
                'SET meklari_id = :user, asiakas_id = :customer, kauppa_id = :auction, '.
                'hinta = :price, ajankohta = :time WHERE id = :id');
        $query->execute(array(
            'id' => $this->id,
            'user' => $this->user,
            'customer' => $this->customer,
            'auction' => $this->auction,
            'price' => $this->price,
            'time' => $this->time
        ));
    }

    public function destroy() {
        self::destroy_item_references($this->id);
        $query = DB::connection()->prepare('DELETE FROM Tarjous WHERE id = :id');
        $query->execute(array(
            'id' => $this->id
        ));
    }

}
