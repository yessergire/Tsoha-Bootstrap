<?php
/**
 * Jokaiseen tuotteeseen liittyy 0..1 kauppa.
 * Kaupan alku on ennen päättymis ajan kohtaa.
 * Jokaiseen kauppaan liittyy 0..* Bid eli Tarjousta.
 */
class Auction extends BaseModel {
    public $id, $starts, $ends, $item;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('Select * from Kauppa');
        $query->execute();
        $rows = $query->fetchAll();
        $auctions = array();
        foreach ($rows as $row) {
            $item = Item::find($row['tuote_id']);
            $auctions[] = new Auction(array(
                'id' => $row['id'],
                'starts' => $row['alkaa'],
                'end' => $row['päättyy'],
                'item' => Item::find($row['tuote_id'])
            ));
        }
        return $auctions;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('Select * from Kauppa Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $auction = new Auction(array(
                'id' => $row['id'],
                'starts' => $row['alkaa'],
                'end' => $row['päättyy'],
                'item_id' => $row['tuote_id']
            ));
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
        self::destroy_category_references($this->id);
        $query = DB::connection()->prepare('DELETE FROM Kauppa WHERE ID = :id');
        $query->execute(array(
            'id' => $this->id
        ));
    }
}
