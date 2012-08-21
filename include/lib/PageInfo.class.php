<?php

/**
 * Description of PageInfo
 *
 * @author dedal.qq
 */
class PageInfo extends HtmlElement {
    
    public $page_title;
    
    public $info_mass;
    
    function __toString() {
        if (!empty($this->page_title)) {
            $this->tpl->value('page_title', $this->page_title);
            $this->tpl->block('page_title');
        }
        
        if (!empty($this->info_mass)) {
            $this->tpl->value('info_mass', $this->info_mass);
            $this->tpl->block('info_mass');
        }
        //bug(debug_backtrace(), true);
        return $this->tpl->echo_tpl('info_page.html');
    }
}

?>
