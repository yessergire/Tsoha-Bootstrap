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
            $items[] = new Item(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'pictureURL' => $row['kuva'],
                'price' => $row['hinta']
            ));
        }
        return $items;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('Select * from Tuote Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $item = new Item(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus'],
                'pictureURL' => $row['kuva'],
                'price' => $row['hinta']
            ));
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
