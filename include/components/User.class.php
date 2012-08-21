<?php

/**
 * Description of User
 *
 * @author dedal.qq
 */
class User extends Object {
    
    public $login;
    public $password;
    public $date;
    
    public function __construct($id = 0) {
        $this->id = $id;
        parent::__construct();
    }
    
    protected function getTableName() {
        return 'users';
    }
}

?>
