<?php
/**
 * Jokaiseen tuotteeseen liittyy 0..1 kauppa.
 * Kaupan alku on ennen päättymis ajan kohtaa.
 * Jokaiseen kauppaan liittyy 0..* Bid eli Tarjousta.
 */
class Auction extends BaseModel {
    public $id, $starts, $ends, $item_id, $item, $closed;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_time');
    }

    public static function all() {
        $query = DB::connection()->prepare('Select * from Kauppa');
        $query->execute();
        $rows = $query->fetchAll();
        $auctions = array();
        foreach ($rows as $row) {
            $auction = new Auction(array(
                'id' => $row['id'],
                'starts' => $row['alkaa'],
                'ends' => $row['päättyy'],
                'closed' => $row['suljettu'],
                'item_id' => $row['tuote_id']
            ));
            $auction->item = Item::find($row['tuote_id']);
            $auctions[] = $auction;
        }
        return $auctions;
    }
    
    public static function find($id, $lazy = false) {
        $query = DB::connection()->prepare('Select * from Kauppa Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $auction = new Auction(array(
                'id' => $row['id'],
                'starts' => $row['alkaa'],
                'ends' => $row['päättyy'],
                'closed' => $row['suljettu'],
                'item' => Item::find($row['tuote_id'], $lazy=$lazy)
            ));
            return $auction;
        }
        return null;
    }
    
    public static function findByItem($item_id) {
        $query = DB::connection()->prepare('Select * from Kauppa Where tuote_id = :item_id Limit 1');
        $query->execute(array('item_id' => $item_id));
        $row = $query->fetch();

        if ($row) {
            $auction = new Auction(array(
                'id' => $row['id'],
                'starts' => $row['alkaa'],
                'ends' => $row['päättyy'],
                'closed' => $row['suljettu'],
                'item_id' => $row['tuote_id']
            ));
            $auction->bids = Bid::findByAuction($auction->id);
            $auction->max_price = $auction->maxPrice();
            $auction->can_bid = true;
            $now = date('Y-m-d');
            if (strtotime($now) < strtotime($auction->starts)
                    ||strtotime($now) > strtotime($auction->ends)) {
                $auction->can_bid = false;
            }

            return $auction;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kauppa (alkaa, päättyy, tuote_id) ' .
                'VALUES (:starts, :ends, :item_id) RETURNING id');
        $query->execute(array(
            'starts' => $this->starts,
            'ends' => $this->ends,
            'item_id' => $this->item_id
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Kauppa '.
                'SET alkaa = :starts, päättyy = :ends ' .
                'WHERE id = :id');
        $query->execute(array(
            'id' => $this->id,
            'starts' => $this->starts,
            'ends' => $this->ends
        ));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Kauppa WHERE ID = :id');
        $query->execute(array(
            'id' => $this->id
        ));
    }

    public function close() {
        $query = DB::connection()->prepare('UPDATE Kauppa SET suljettu = true WHERE id = :id');
        $query->execute(array(
            'id' => $this->id
        ));
    }
    
    public function countBids() {
        $query = DB::connection()->prepare('Select count(*) as tarjousten_lkm from Tarjous '.
                'WHERE kauppa_id = :id');
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();
        return $row['tarjousten_lkm'];
    }
    
    public function maxPrice() {
        $query = DB::connection()->prepare('Select COALESCE(MAX(hinta), 0) as suurin from Tarjous '.
                'WHERE kauppa_id = :id');
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();
        return $row['suurin'];
    }

    public function validate_time() {
        $errors = array();
        
        if (strtotime($this->ends) <= strtotime($this->starts)) {
            $errors[] = 'Huutokauppa ei voi päättyä ennen kuin se on alkanut!';
        }
        return $errors;
    }
}
