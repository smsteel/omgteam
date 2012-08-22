<?php

include 'config.php';
include 'include/init.php';

HtmlDocument::getInstance()->PageHeader->addMenuElement('blog', '����');
HtmlDocument::getInstance()->PageHeader->addMenuElement('log', '����');

if (isset($_GET['autorisation'])) {
    Autorisation::getInstance()->controller();
}

$blog = new BlogMass(2);
//$blog->setModeEdit();
$blog_new = new BlogMass();
$page = new PageInfo();
$page->page_title = '��������� ��������';
$page->info_mass = '��� ��������� �������������� ��������� ��������';

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