<?php

include 'config.php';
include 'include/init.php';

HtmlDocument::getInstance()->PageHeader->addMenuElement('blog', 'Блог');
HtmlDocument::getInstance()->PageHeader->addMenuElement('log', 'Логи');

if (isset($_GET['autorisation'])) {
    Autorisation::getInstance()->controller();
}

$blog = new BlogMass(2);
//$blog->setModeEdit();
$blog_new = new BlogMass();
$page = new PageInfo();
$page->page_title = 'Заголовок страницы';
$page->info_mass = 'Это некоторое информационное сообщение страницы';

if ($_GET['mod'] == 'blog') {
    HtmlDocument::getInstance()->addContent($blog);
}

if ($_GET['mod'] == 'log') {
    HtmlDocument::getInstance()->addContent($blog_new);
}

//HtmlDocument::getInstance()->addContent($blog_new);
//HtmlDocument::getInstance()->addContent('<a href="?omg=1&qq=2&ppc=3">qq</a>');

HtmlDocument::getInstance()->printDocument();

?>