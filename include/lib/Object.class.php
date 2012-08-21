<?php

/**
 * Description of object
 *
 * @author dedal.qq
 */
abstract class Object {
    
    protected $id;
    
    public function __construct() {
        if ($this->id != 0) {
            $this->load();
        }
    }
    
    abstract protected function getTableName();

    /**
     * Сохраняет объект в базе данных
     */
    public function save() {
        $sql = MySql::getInstance();
        if ($this->id == 0) {            
            $this->id = $sql->db_insert($this->getTableName(), $this->getData());
        }
        else {
            $sql->db_update($this->getTableName(), '`id`='.$this->id, $this->getData());
        }
        return $this->id;
    }
    
    /**
     * Метод загрузки объекта из базы
     */
    public function load($where = '') {
        if ($where == '') {
            if ((bool)$this->id) {
                $where = '`id`='.$this->id;
            }
            else {
                $this->id = null;
                return false;
            }
        }
        $sql = MySql::getInstance();

        $sql->db_select_table($this->getTableName());
        $sql->db_select_where($where);
        $sql->db_select_limit(0, 1);

        $data = $sql->db_exec();
        if (isset($data[0]['id']) && (bool)$data[0]['id']) {
            return $this->setData($data);
        }
        else {
            $this->id = null;
            return false;
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    /**
     * Метод получения масива данных объекта
     */
    public function getData() {
        return get_object_vars($this);
    }
    
    /**
     * Установить объект данными из масива
     * @param type $array 
     */
    public function setData($array) {
        foreach(get_object_vars($this) as $i => $v) {
            $this->$i = $array[0][$i];
        }
        return true;
    }
    
    /**
     * Загрузить объект теми данными которые находятся а http request
     * @param type $method 
     */
    public function parsHttpData($method = 'post') {
        
    }
    
    public function __destruct() {
        //$this->save();
    }
    
}
/**
 * @todo изменить метод setData что быон понимал как двумерный масив при загрузки из базы,
 * а так же обычный масив, или научить его принимать парамметр этого масива,
 * так наверное будет даже лучше, что выводимый элемент списка мог создавать пачки обьектов
 * из одного результа из базы передавая разные строчки
 * 
 * @todo помимо этого надо научить композиции как то инициализироваться полность
 * либо лдя этого прийжется делать метод инициализации, который будет у всех
 * вызывать метод сетДата, либо переделать сам метод сет даты,
 * либо  создавать фабрику которая будет возвращать готовую композицию, в принципи
 * элементы композиции можно получить и просто догрузив остатки из базы, но для
 * повышения производительности лучше заюзать фабрику
 */
?>
