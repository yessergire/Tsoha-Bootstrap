<?php

class User extends BaseModel {

    public $id, $name, $email, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_email', 'validate_password');
    }
    
    public static function authenticate($email, $password) {
        $query = DB::connection()->prepare('Select * from Meklari WHERE sähköposti = :email AND salasana = :password Limit 1');
        $query->execute(array('email' => $email, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public static function find($id) {
        $query = DB::connection()->prepare('Select * from Meklari Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana']
            ));
            return $user;
        }
        return null;
    }
    
    
}
