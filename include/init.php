<?php

function __autoload($class_name) {
    if (file_exists('include/lib/'.$class_name.'.class.php')) {
        include_once 'include/lib/'.$class_name.'.class.php';
    }
    elseif (file_exists('include/components/'.$class_name.'.class.php')) {
        include_once 'include/components/'.$class_name.'.class.php';
    }
}

function bug($var, $stop = false) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if ($stop) exit;
}

include 'functions.php';

ini_set('display_errors', '1');

Tpl::getInstance($GLOBALS['config']['tpl_folder']);

MySql::getInstance(
        $GLOBALS['config']['db_host'],
        $GLOBALS['config']['db_login'],
        $GLOBALS['config']['db_password'],
        $GLOBALS['config']['db_databane'],
        $GLOBALS['config']['db_prefix']
        );

MySql::getInstance()->char_set($GLOBALS['config']['encoding']);

Autorisation::getInstance();

?>