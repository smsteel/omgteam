<?php

/**
 * Description of HtmlDocument
 *
 * @author dedal.qq
 */
class HtmlDocument {
    
    private static $object = NULL;
    
    private $print_only_content;
    
    /**
     * @var PageHeader
     */
    public $PageHeader;
    
    /**
     * @var PageMenu
     */
    public $PageMenu;
    
    /**
     * @var PageFooter
     */
    public $PageFooter;
    
    /**
     * @var HtmlElement[]
     */
    private $main_content = array();
    
    private function __construct() {
        $this->ParseHttpRequst();
        
        $this->PageHeader = new PageHeader();
        $this->PageMenu = new PageMenu();
        $this->PageFooter = new PageFooter();
    }
    
    /**
     * @return HtmlDocument
     */
    static public function getInstance() {
        if (!(self::$object != NULL && self::$object instanceof self)) {
            self::$object = new self;
        }
        return self::$object;
    }
    
    private function ParseHttpRequst() {
        $this->print_only_content = isset($_REQUEST['print_only_content']) ? true : false;
    }

    public function printDocument() {
        
        $tpl = Tpl::getInstance();
        
         if ($this->print_only_content) {
             header('Content-Type: text/html;charset='.$GLOBALS['config']['encoding']);
             print $this->getContent();
         }
         else {
            $tpl->value('header', (string)$this->PageHeader);
            $tpl->value('menu', (string)$this->PageMenu);

            $tpl->value('content', $this->getContent());

            $tpl->value('footer', (string)$this->PageFooter);
            print $tpl->echo_tpl('index.html');
         }
    }
    
    private function getContent() {
        $content = '';
        foreach ($this->main_content as $i => $v) {
            /**
             * @todo тут надо как то привести в порядок 
             */
            if (is_array($v)) {
                bug($v);
            }
            if ($v != null) {
                $content.= (string)$v;
            }
        }
        return $content;
    }
    
    public function addContent($content = '') {
        $this->main_content[] = $content;
        return true;
    }
}

?>
