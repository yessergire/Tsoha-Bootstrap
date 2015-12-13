<?php

class Customer extends BaseModel {

    public $id, $name, $email, $password, $address, $phone;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_email', 'validate_password', 'validate_address', 'validate_phone');
    }
    
    public function is_admin() {
        return false;
    }
    
    public function is_customer() {
        return true;
    }

    public static function all() {
        $query = DB::connection()->prepare('Select * from Asiakas');
        $query->execute();
        $rows = $query->fetchAll();
        $customers = array();
        foreach ($rows as $row) {
            $customers[] = new Customer(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana'],
                'address' => $row['osoite'],
                'phone' => $row['puhelin']
            ));
        }
        return $customers;
    }
    
    public static function authenticate($email, $password) {
        $query = DB::connection()->prepare('Select * from Asiakas WHERE sähköposti = :email AND salasana = :password Limit 1');
        $query->execute(array('email' => $email, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            $customer = new Customer(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana'],
                'address' => $row['osoite'],
                'phone' => $row['puhelin']
            ));
            return $customer;
        } else {
            return null;
        }
    }


    public static function find($id) {
        $query = DB::connection()->prepare('Select * from Asiakas Where id = :id Limit 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $customer = new Customer(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana'],
                'address' => $row['osoite'],
                'phone' => $row['puhelin']
            ));
            $customer->bids = Bid::findByCustomer($customer->id);
            return $customer;
        }
        return null;
    }

    public static function findByEmail($email) {
        $query = DB::connection()->prepare('Select * from Asiakas Where sähköposti = :email Limit 1');
        $query->execute(array('email' => $email));
        $row = $query->fetch();

        if ($row) {
            $customer = new Customer(array(
                'id' => $row['id'],
                'name' => $row['nimi'],
                'email' => $row['sähköposti'],
                'password' => $row['salasana'],
                'address' => $row['osoite'],
                'phone' => $row['puhelin']
            ));
            return $customer;
        }
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Asiakas (nimi, sähköposti, salasana, osoite, puhelin) ' .
                'VALUES (:name, :email, :password, :address, :phone) RETURNING id');
        $query->execute(array(
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'address' => $this->address,
            'phone' => $this->phone
        ));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Asiakas '.
                'SET nimi = :name, sähköposti = :email, salasana = :password, '.
                'osoite = :address, puhelin = :phone ' .
                'WHERE id = :id');
        $query->execute(array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'address' => $this->address,
            'phone' => $this->phone
        ));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Asiakas WHERE ID = :id');
        $query->execute(array(
            'id' => $this->id
        ));
    }
    
    public function validate_name() {
        $errors = array();
        if (!$this->validate_length('name', 3)) {
            $errors[] = 'Nimen pituuden tulee olla vähintään 3 merkkiä!';
        }
        return $errors;
    }
    
    public function validate_email() {
        $errors = array();
        if (!$this->validate_length('email', 5)) {
            $errors[] = 'Sähköpostin pituuden tulee olla vähintään 5 merkkiä!';
        }
        return $errors;
    }
    
    public function validate_password() {
        $errors = array();
        if (!$this->validate_length('password', 6)) {
            $errors[] = 'Salasanan pituuden tulee olla vähintään 6 merkkiä!';
        }
        return $errors;
    }
    public function validate_address() {
        $errors = array();
        if (!$this->validate_length('address', 10)) {
            $errors[] = 'Osoitteen pituuden tulee olla vähintään 10 merkkiä!';
        }
        return $errors;
    }
    public function validate_phone() {
        $errors = array();
        if (!$this->validate_length('phone', 10)) {
            $errors[] = 'Puhelin numeron pituuden tulee olla vähintään 10 merkkiä!';
        }
        return $errors;
    }
}