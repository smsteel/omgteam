<?php

/**
 * Description of BlogMass
 *
 * @author dedal.qq
 */
class BlogMass extends Object {
    
    public $subject;
    public $text;
    public $user_id;
    public $date;
    
    /**
     * @var User
     */
    private $user;
    
    private $printMode;

    public function __construct($id = 0) {
        $this->id = $id;
        parent::__construct();
        
        $this->user = new User($this->user_id);
        $this->printMode = 1;
    }
    
    protected function getTableName() {
        return 'blogs';
    }
    
    public function setModeEdit() {
        $this->printMode = 2;
    }

    public function __toString() {
        $tpl = Tpl::getInstance();
        
        $tpl->value('blogs_subject', $this->subject);
        $tpl->value('blogs_login', $this->user->login);
        $tpl->value('blogs_date', $this->date);
        $tpl->value('blogs_text', $this->text);
        $tpl->value('blogs_id', $this->getId());
        
        if (!(bool)$this->id || $this->printMode == 2) {
            $tpl->block('blog_form');
            
            if ((bool)$this->getId()) {
                $tpl->value('editor_mod', 'Редактировать запись');
            }
            else {
                $tpl->value('editor_mod', 'Создать новую запись');
            }
        }
        else {
            $tpl->block('blog');
        }
        
        return $tpl->echo_tpl('blog_mass.html');
    }
}

?>
