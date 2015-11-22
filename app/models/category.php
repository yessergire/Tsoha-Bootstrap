<?php

class Category extends BaseModel {

    public $id, $name, $description;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

    public static function all() {
        $query = DB::connection()->prepare('Select * from TuoteLuokka');
        $query->execute();
        $rows = $query->fetchAll();
        $categories = array();
        foreach ($rows as $row) {
            $categories[] = new Category(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus']
            ));
        }
        return $categories;
    }

    public static function destroy_item_references($id) {
        $query = DB::connection()->prepare('DELETE FROM TuotteenLuokat WHERE tuoteluokka_id = :id');
        $query->execute(array('id' => $id));
    }
    
    public static function get_items($id) {
        $query = DB::connection()->prepare('SELECT tuote_id  FROM TuotteenLuokat '.
                'WHERE tuoteluokka_id= :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $items = array();
        foreach ($rows as $row) {
            $items[] = Item::find($row['tuote_id']);
        }
        return $items;
    }

    public static function find($id, $get_items=true) {
        $query = DB::connection()->prepare('Select * from TuoteLuokka Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $category = new Category(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'description' => $row['kuvaus']
            ));
            if ($get_items) {
                $category->items = self::get_items($id);
            }
            return $category;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO TuoteLuokka (nimi, kuvaus) ' .
                'VALUES (:name, :description) RETURNING id');
        $query->execute(array(
            'name' => $this->name,
            'description' => $this->description
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE TuoteLuokka '.
                'SET nimi = :name, kuvaus = :description WHERE id = :id');
        $query->execute(array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ));
    }

    public function destroy() {
        self::destroy_item_references($this->id);
        $query = DB::connection()->prepare('DELETE FROM TuoteLuokka WHERE ID = :id');
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

}
