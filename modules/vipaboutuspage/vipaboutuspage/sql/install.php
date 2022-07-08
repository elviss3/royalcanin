<?php
	$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vipaboutuspage` (
  `id_vipaboutuspage` int(11) NOT NULL auto_increment,
	PRIMARY KEY (`id_vipaboutuspage`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vipaboutuspage_lang` (
  `id_vipaboutuspage` int(11) NOT NULL auto_increment,
  `id_lang` int(11) NOT NULL ,
  `content` text NOT NULL,
  PRIMARY KEY (`id_vipaboutuspage`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

	$sql[] = 'INSERT INTO `'._DB_PREFIX_.'vipaboutuspage` (id_vipaboutuspage) VALUES (\'1\')';
$languages = Language::getLanguages(false);
foreach ($languages as $lang) {
	$sql[] = 'INSERT INTO `'._DB_PREFIX_.'vipaboutuspage_lang` (id_vipaboutuspage, id_lang, content) VALUES (\'1\', '.(int)$lang['id_lang'].', \'type Your content HERE\')';
}
?>
