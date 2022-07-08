<?php
	$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ms_custompage` (
  `id_ms_custompage` int(11) NOT NULL auto_increment,
	PRIMARY KEY (`id_ms_custompage`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ms_custompage_lang` (
  `id_ms_custompage` int(11) NOT NULL auto_increment,
  `id_lang` int(11) NOT NULL ,
  `content` text NOT NULL,
  PRIMARY KEY (`id_ms_custompage`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

	$sql[] = 'INSERT INTO `'._DB_PREFIX_.'ms_custompage` (id_ms_custompage) VALUES (\'1\')';

$languages = Language::getLanguages(false);
foreach ($languages as $lang) {
	$sql[] = 'INSERT INTO `'._DB_PREFIX_.'ms_custompage_lang` (id_ms_custompage, id_lang, content) VALUES (\'1\', '.(int)$lang['id_lang'].', \'type Your content HERE\')';
}
?>
