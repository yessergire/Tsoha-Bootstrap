<?php

class Item extends BaseModel {

    public $id, $name, $description, $pictureURL, $price;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_price');
    }

    public static function all() {
        $query = DB::connection()->prepare('Select * from Tuote');
        $query->execute();
        $rows = $query->fetchAll();
        $items = array();
        foreach ($rows as $row) {
            $item = new Item(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'pictureURL' => $row['kuva'],
                'price' => $row['hinta']
            ));
            $item->auction = Auction::findByItem($item->id);
            $items[] = $item;
        }
        return $items;
    }

    private static function get_categories($id) {
        $query = DB::connection()->prepare('SELECT tuoteluokka_id FROM TuotteenLuokat '.
                'WHERE tuote_id = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $categories = array();
        foreach ($rows as $row) {
            $categories[] = Category::find($row['tuoteluokka_id'], false);
        }
        return $categories;
    }

    private static function destroy_category_references($id) {
        $query = DB::connection()->prepare('DELETE FROM TuotteenLuokat WHERE tuote_id = :id');
        $query->execute(array('id' => $id));
    }

    public static function set_categories($id, $category_ids) {
        self::destroy_category_references($id);
        $query = DB::connection()->prepare('INSERT INTO TuotteenLuokat (tuote_id, tuoteluokka_id) '.
                'VALUES (:id, :category_id)');
        
        foreach ($category_ids as $category_id) {
            $query->execute(array('id' => $id, 'category_id' => $category_id));
        }
    }

    public static function find($id, $lazy = false) {
        $query = DB::connection()->prepare('Select * from Tuote Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $item = new Item(array(
                'id' => $id,
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'pictureURL' => $row['kuva'],
                'price' => $row['hinta']
            ));
            if (!$lazy) {
                $item->categories = self::get_categories($id);
                $item->auction = Auction::findByItem($id);
            }
            return $item;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tuote (nimi, kuvaus, kuva, hinta) ' .
                'VALUES (:name, :description, :pictureURL, :price) RETURNING id');
        $query->execute(array(
            'name' => $this->name,
            'description' => $this->description,
            'pictureURL' => $this->pictureURL,
            'price' => floatval($this->price)
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Tuote '.
                'SET nimi = :name, kuvaus = :description, kuva = :pictureURL, hinta = :price ' .
                'WHERE id = :id');
        $query->execute(array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'pictureURL' => $this->pictureURL,
            'price' => $this->price
        ));
    }

    public function destroy() {
        self::destroy_category_references($this->id);
        $query = DB::connection()->prepare('DELETE FROM Tuote WHERE ID = :id');
        $query->execute(array(
            'id' => $this->id
        ));
    }
    
    public function validate_name() {
        $errors = array();
        if (!$this->validate_length('name', 3)) {
            $errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
        }
        return $errors;
    }
    
    public function validate_price() {
        $errors = array();
        if (!$this->validate_min('price', 1)) {
            $errors[] = 'Hinnan tulee olla vähintään 1€!';
        }
        return $errors;
    }

}
