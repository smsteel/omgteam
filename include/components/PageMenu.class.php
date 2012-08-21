<?php

/**
 * Description of PageMenu
 *
 * @author dedal.qq
 */
class PageMenu extends HtmlElement {
    
    public $menuTitle;
    
    public function __construct() {
        parent::__construct();
        
        $this->menuTitle = 'Μενώ';
    }


    public function __toString() {
        $this->tpl->value('menu_title', $this->menuTitle);
        $this->tpl->block('menu');
        
        return $this->tpl->echo_tpl('left_menu.html');
    }
}

?>
