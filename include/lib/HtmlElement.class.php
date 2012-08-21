<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HtmlElement
 *
 * @author dedal.qq
 */
class HtmlElement {
    
    /**
     * @var Tpl
     */
    protected $tpl;
    
    /**
     * @var string
     */
    protected $html = '';
    
    public function __construct() {
        $this->tpl = Tpl::getInstance();
    }

    public function __toString() {
        return $this->html;
    }
}

?>
