<?php

class Admin extends BaseModel {

    public $id, $name, $email, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_email', 'validate_password');
    }
    
    public function is_admin() {
        return true;
    }
    
    public function is_customer() {
        return false;
    }
    
    public static function authenticate($email, $password) {
        $query = DB::connection()->prepare('Select * from Meklari WHERE sähköposti = :email AND salasana = :password Limit 1');
        $query->execute(array('email' => $email, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            $admin = new Admin(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana']
            ));
            return $admin;
        } else {
            return null;
        }
    }

    public static function find($id) {
        $query = DB::connection()->prepare('Select * from Meklari Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $admin = new Admin(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana']
            ));
            return $admin;
        }
        return null;
    }

    public static function findByEmail($email) {
        $query = DB::connection()->prepare('Select * from Meklari Where sähköposti = :email Limit 1');
        $query->execute(array('email' => $email));
        $row = $query->fetch();

        if ($row) {
            $admin = new Admin(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana']
            ));
            return $admin;
        }
        return null;
    }
    
    
}
