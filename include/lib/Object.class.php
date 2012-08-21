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
     * ��������� ������ � ���� ������
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
     * ����� �������� ������� �� ����
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
     * ����� ��������� ������ ������ �������
     */
    public function getData() {
        return get_object_vars($this);
    }
    
    /**
     * ���������� ������ ������� �� ������
     * @param type $array 
     */
    public function setData($array) {
        foreach(get_object_vars($this) as $i => $v) {
            $this->$i = $array[0][$i];
        }
        return true;
    }
    
    /**
     * ��������� ������ ���� ������� ������� ��������� � http request
     * @param type $method 
     */
    public function parsHttpData($method = 'post') {
        
    }
    
    public function __destruct() {
        //$this->save();
    }
    
}
/**
 * @todo �������� ����� setData ��� ���� ������� ��� ��������� ����� ��� �������� �� ����,
 * � ��� �� ������� �����, ��� ������� ��� ��������� ��������� ����� ������,
 * ��� �������� ����� ���� �����, ��� ��������� ������� ������ ��� ��������� ����� ��������
 * �� ������ �������� �� ���� ��������� ������ �������
 * 
 * @todo ������ ����� ���� ������� ���������� ��� �� ������������������ ��������
 * ���� ��� ����� ��������� ������ ����� �������������, ������� ����� � ����
 * �������� ����� �������, ���� ���������� ��� ����� ��� ����,
 * ����  ��������� ������� ������� ����� ���������� ������� ����������, � ��������
 * �������� ���������� ����� �������� � ������ �������� ������� �� ����, �� ���
 * ��������� ������������������ ����� ������� �������
 */
?>
