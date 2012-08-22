<?php

/**
 * Description of Tpl
 *
 * @author dedal.qq
 */
class Tpl {

    private $show = array();
    private $value = array();
    private $file_folder;
    private $file;
    private $defolt = 0;
    private static $object;
    private $result;
    
    public static $init_file_folder;

    private function __construct($file_folder, $defolt = 0) {
        $this->file_folder = $file_folder;
        $this->defolt = $defolt;
    }

    /**
     * @param type $file_folder
     * @return Tpl 
     */
    static public function getInstance($file_folder = '') {
//        if (!(self::$object != NULL && self::$object instanceof self)) {
//            self::$object = new self($GLOBALS['config']['tpl_folder']);
//        }
//        return self::$object;
        if ($file_folder != '') {
            self::$init_file_folder = $file_folder;
        }
        return new self(self::$init_file_folder);
    }

    function value($name, $value) {
        if (isset($this->value[$name])) {
            if (is_array($this->value[$name])) {
                $this->value[$name][] = $value;
            } else {
                $this->value[$name] = array($this->value[$name], $value);
            }
        } else {
            $this->value[$name] = $value;
        }
    }

    function setValue($name, $value) {
        $this->value[$name] = $value;
    }

    function block($name, $show = 1) {
        $this->show[$name] = $show;
    }

    private function f_include($include_file) {
        $file_temp = $this->file;

        $this->file = @fopen($this->file_folder . "//" . $include_file, "r");

        if ($this->file == false) {
            echo "Can't include file: " . $this->file_folder . "//" . $file_name;
            return 0;
        }

        $this->hendler();

        $this->file = $file_temp;
    }

    private function no_print($id) {
        while (!feof($this->file)) {
            $str = fgets($this->file);
            if (substr($str, 0, (13 + strlen($id))) == "<!-- END " . $id . " -->")
                break;
        }
    }

    private function simpl_yes_print($id, $i) {
        while (!feof($this->file)) {
            $str = fgets($this->file);

            if (substr($str, 0, (13 + strlen($id))) == "<!-- END " . $id . " -->") {
                break;
            }

            $temp_num = $this->string_rps($str, $i);
        }
    }

    private function yes_print($id) {
        $num = 1;
        $pols = ftell($this->file);

        for ($i = 0; $i < $num; $i++) {
            $num = $this->hendler($i, $id);

            if (($i + 1) == $num)
                return 0; else
                fseek($this->file, $pols);
        }
    }

    private function string_rps($str, $id = 0) {
        $num = 0;

        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] == "{") {
                $index = "";

                while (1) {
                    $i++;

                    if ($str[$i] == "}") {
                        $i++;
                        break;
                    }
                    $index .= $str[$i];
                }

                if (isset($this->value[$index]) == false)
                    $this->value[$index] = '';

                if (gettype($this->value[$index]) == 'array') {
                    if ($num < count($this->value[$index])) {
                        $num = count($this->value[$index]);
                    }

                    if (isset($this->value[$index][$id]) == false)
                        $this->value[$index][$id] = '';
                    $this->result.= $this->value[$index][$id];
                }
                else {
                    $this->result.= $this->value[$index];
                }

                $index = "";
            }

            $this->result.= $str[$i];
        }

        return $num;
    }

    private function name($text) {
        $return_text = "";

        for ($i = 7; $i < strlen($text); $i++) {

            if ($text[$i] == " ")
                while ($i < strlen($text)) {
                    $i++;
                    if ($text[$i] != " ")
                        $return_text .= $text[$i]; else
                        break;
                }

            if ($return_text != "")
                break;
        }

        return $return_text;
    }

    private function hendler($i = 0, $old_id = '') {
        $num = 1;

        while (!feof($this->file)) {
            $str = fgets($this->file);

            if (substr($str, 0, 11) == "<!-- BEGIN ") {
                $id = $this->name($str);

                if (isset($this->show[$id]) == false)
                    $this->show[$id] = $this->defolt;

                if (is_array($this->show[$id])) {
                    if ($this->show[$id][$i] == 0) {
                        $this->no_print($id);
                    } else {
                        $this->simpl_yes_print($id, $i);
                    }
                } else {

                    if ($this->show[$id] == 0) {
                        $this->no_print($id);
                        //return 0;
                    } else {
                        $this->yes_print($id);
                        //return 0;
                    }
                }
            } elseif (substr($str, 0, 13) == "<!-- INCLUDE ") {
                $new_file = $this->name($str);

                $this->f_include($new_file);
            } elseif (substr($str, 0, (13 + strlen($old_id))) == "<!-- END " . $old_id . " -->") {
                return $num;
            } else {
                $temp_num = $this->string_rps($str, $i);
                if ($num < $temp_num)
                    $num = $temp_num;
            }
        }
    }

    function echo_tpl($file_name) {
        $this->result = '';
        //header("charset=utf-8");

        $this->file = @fopen($this->file_folder . "//" . $file_name, "r");

        if ($this->file == false) {
            return "Can't load file: " . $this->file_folder . "//" . $file_name;
        }

        $this->hendler();

        //$this->show = array();
        //$this->value = array();

        return $this->result;
    }

}

/**
 * @todo Переделать инициализацию этого класа, всетаки лучше когда параметр с
 * папоко передается именно в файле init.php там вызывать либо гетинстанс
 * либо метод инициализации 
 */
?>
