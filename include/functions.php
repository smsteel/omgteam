<?php

function raplace_uri_parametrs($parametrs = array(), $uri = '') {
    if ($uri == '') {
        $uri = $_SERVER['REQUEST_URI'];
    }
    list($script, $parametrs_list) = explode('?', $uri);
    $parametrs_list = explode('&', $parametrs_list);
    $parametrs_array = array();
    $result_parametrs = array();
    foreach ($parametrs_list as $v) {
        list($name, $value) = explode('=', $v);
        if (isset($parametrs[$name])) {
            if ($parametrs[$name] == '') {
                continue;
            }
            else {
                $result_parametrs[] = $name.'='.$parametrs[$name];
            }
        }
        else {
            $result_parametrs[] = $name.'='.$value;
        }
    }
    
    return $script.'?'.implode('&', $result_parametrs);
}

?>
