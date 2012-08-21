<?php

include 'config.php';
include 'include/init.php';

HtmlDocument::getInstance()->PageHeader->addMenuElement('blog', 'Блог');

if (isset($_GET['autorisation'])) {
    Autorisation::getInstance()->controller();
}

$blog = new BlogMass(2);
//$blog->setModeEdit();
$blog_new = new BlogMass();
$page = new PageInfo();
$page->page_title = 'Заголовок страницы';
$page->info_mass = 'Это некоторое информационное сообщение страницы';

HtmlDocument::getInstance()->addContent($blog);
HtmlDocument::getInstance()->addContent($page);
HtmlDocument::getInstance()->addContent($blog_new);

HtmlDocument::getInstance()->printDocument();

?>