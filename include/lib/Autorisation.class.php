<?php

/**
 * Description of Autorisation
 *
 * @author dedal.qq
 */
class Autorisation {
    
    private static $object = NULL;
    
    /**
     * @var User 
     */
    private $user;
    private $is_login;
    
    /**
     * @var Tpl
     */
    private $tpl;
    
    private function __construct() {
        
        session_start();
        
        $this->tpl = Tpl::getInstance();
        
        $this->getStatus();
    }
    
    public function getUser() {
        return $this->user;
    }
    
    /**
     * @return Autorisation
     */
    static public function getInstance() {
        if (!(self::$object != NULL && self::$object instanceof self)) {
            self::$object = new self;
        }
        return self::$object;
    }
    
    public function controller() {
        $page = new PageInfo;
        $this->user = new User();
        
        $page->page_title = 'Авторизация';
        
        $action = htmlspecialchars($_GET['autorisation']);
        
        if ($action == 'login')
        {
            $login = htmlspecialchars($_POST['login']);
            $password = md5(htmlspecialchars($_POST['password']));
            
            $this->user->load("`login`='".$login."' AND `password`='".$password."'");
            
            if ($this->user->getId() > 0)
            {
                $page->info_mass = 'Вы успешно авторизировались!';
                
                $_SESSION['auth']['user_id'] = $this->user->getId();
            }
            else {
                $page->info_mass = 'Не верное сочитание логина и пароля!';
            }
        }
        elseif ($action == 'exit') {
            $_SESSION['auth']['user_id'] = 0;
            $page->info_mass = 'До скорой встречи!';
        }
        
        $this->getStatus();
        
        HtmlDocument::getInstance()->addContent($page);
    }
    
    public function __toString() {
        return $this->tpl->echo_tpl('autorisation.html');
    }
    
    public function getStatus() {
        if (!isset($_SESSION['auth']['user_id'])) {
            $_SESSION['auth']['user_id'] = false;
        }

        if ((bool)$_SESSION['auth']['user_id']) {
                        
            $this->tpl->block('auth_on', 1);
            $this->tpl->block('auth_off', 0);
            $this->is_login = true;
            
            $this->user = new User((int)$_SESSION['auth']['user_id']);
            
            $this->tpl->setValue('user_name', $this->user->login);
        }
        else {
            $this->tpl->block('auth_off', 1);
            $this->tpl->block('auth_on', 0);
            $this->is_login = false;
        }
    }
    
    public function isLogin() {
        return (bool)$this->user->id;
    }
}

?>
