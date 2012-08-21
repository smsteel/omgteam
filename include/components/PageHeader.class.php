<?php

/**
 * Description of PageHeader
 *
 * @author dedal.qq
 */
class PageHeader extends HtmlElement {
    
    private $menu_elements = array();
    
    public function __toString() {
        //bug($this->tpl);
        $this->tpl->value('form_login', (string)Autorisation::getInstance());
        
        if (count($this->menu_elements) > 0) {
            $this->tpl->block('menu_itm');
        }
        
        foreach($this->menu_elements as $i => $v) {
            $this->tpl->value('menu_itm_mod', $i);
            $this->tpl->value('menu_itm_text', $v);
        }
        
        return $this->tpl->echo_tpl('header.html');
    }
    
    public function addMenuElement($mod, $value) {
        $this->menu_elements[$mod] = $value;
    }
}

?>
