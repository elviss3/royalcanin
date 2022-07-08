<?php


class ms_richcontent extends Module {

	public function __construct() {
		$this->name = 'ms_richcontent';
        $this->tab = 'administration';
        $this->author = 'Sebastian Koziołek - Millenium Studio';
        $this->version = '1.3';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Dodatkowy opis HTML na stronie produktu');
        $this->description = $this->l('Dodaj dodatkowy opis zewnętrzny HTML na stronie produktu');
        $this->ps_versions_compliancy = array('min' => '1.7.1', 'max' => _PS_VERSION_);
    }

    public function install() {
        if (!parent::install() || !$this->_installSql()
                || ! $this->registerHook('displayRichContentProduct')
                || ! $this->registerHook('displayBackOfficeHeader')
                || ! $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')
                || ! $this->registerHook('DisplayProductExtraContent')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall() {
        return parent::uninstall() && $this->_unInstallSql();
    }

    protected function _installSql() {
        $sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
                . " ADD rich_content_field VARCHAR(255) NULL,
                    ADD composition_field VARCHAR(255) NULL,
                    ADD nutri_values_field VARCHAR(255) NULL,
                    ADD nutri_information_field VARCHAR(255) NULL";

        $sqlInstallLang = "ALTER TABLE " . _DB_PREFIX_ . "product_lang "
                . " ADD rich_content_field_lang_wysiwyg TEXT NULL,
                    ADD composition_field_lang_wysiwyg TEXT NULL,
                    ADD nutri_values_field_lang_wysiwyg TEXT NULL,
                    ADD nutri_information_field_lang_wysiwyg TEXT NULL";

        $returnSql = Db::getInstance()->execute($sqlInstall);
        $returnSqlLang = Db::getInstance()->execute($sqlInstallLang);

        return $returnSql && $returnSqlLang;
    }

    protected function _unInstallSql() {
        $sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
                . " DROP rich_content_field, 
                    DROP composition_field, 
                    DROP nutri_values_field,
                    DROP nutri_information_field;";

        $sqlInstallLang = "ALTER TABLE " . _DB_PREFIX_ . "product_lang "
                . " DROP COLUMN rich_content_field_lang_wysiwyg,
                    DROP composition_field_lang_wysiwyg, 
                    DROP nutri_values_field_lang_wysiwyg, 
                    DROP nutri_information_field_lang_wysiwyg;";

    	$returnSql = Db::getInstance()->execute($sqlInstall);
    	$returnSqlLang = Db::getInstance()->execute($sqlInstallLang);


        return $returnSql && $returnSqlLang;
    }

    public function hookDisplayBackOfficeHeader()
	{
		$this->context->controller->addCSS($this->_path.'assets/css/style.css', 'all');
        $this->context->controller->addJS($this->_path.'assets/js/back.js');
	}

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params) {
        
        $product = new Product($params['id_product']);

        $modcontrol = Context::getContext()->link->getModuleLink('agilenewsletters', 'newsletterdetail', array('nid' => $id), true);

        $languages = Language::getLanguages(false);
        $this->context->smarty->assign(array(

            'directories' => $this->get_dirs('../rich_content'),

            'modcontrol' => $modcontrol,

            'rich_content_field' => $product->rich_content_field,
            'rich_content_field_lang_wysiwyg' => $product->rich_content_field_lang_wysiwyg,

            'composition_field' => $product->composition_field,
            'composition_field_lang_wysiwyg' => $product->composition_field_lang_wysiwyg,

            'nutri_values_field' => $product->nutri_values_field,
            'nutri_values_field_lang_wysiwyg' => $product->nutri_values_field_lang_wysiwyg,

            'nutri_information_field' => $product->nutri_information_field,
            'nutri_information_field_lang_wysiwyg' => $product->nutri_information_field_lang_wysiwyg,

			'module_dir' => $this->_path,
            'languages' => $languages,
			'id_lang' => $this->context->language->id,
            'default_language' => $this->context->employee->id_lang,
            'id_language' => $this->context->language->id,
            )
           );

        return $this->display(__FILE__, 'views/templates/hook/ms_richcontent-admin.tpl');
    }

    public function get_dirs($path = '.') {
        $dirs = array();
    
        foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDir() && !$file->isDot() && $file!='__MACOSX') {
                $dirs[] = $file->getFilename();
            }
        }
        return $dirs;
    }

	public function hookDisplayRichContentProduct($params) {

		$product = new Product((int)Tools::getValue('id_product'));
		$languages = Language::getLanguages(false);

        $url = 'rich_content/'.$product->rich_content_field_lang_wysiwyg[$this->context->language->id].'/index.html';

        $exists = file_exists($url);
        
        if (!$exists) {
               return; 
        }

		$this->context->smarty->assign(array(
            

            'link_txt' => $product->rich_content_field_lang_wysiwyg,

            'rich_content_field' => $product->rich_content_field,
            'rich_content_field_lang_wysiwyg' => $product->rich_content_field_lang_wysiwyg,

            'composition_field' => $product->composition_field,
            'composition_field_lang_wysiwyg' => $product->composition_field_lang_wysiwyg,

            'nutri_values_field' => $product->nutri_values_field,
            'nutri_values_field_lang_wysiwyg' => $product->nutri_values_field_lang_wysiwyg,

            'nutri_information_field' => $product->nutri_information_field,
            'nutri_information_field_lang_wysiwyg' => $product->nutri_information_field_lang_wysiwyg,

			'languages' => $languages,
			'id_lang' => $this->context->language->id,
            'id_language' => $this->context->language->id,
            )
		);
		return $this->display(__FILE__, 'views/templates/hook/ms_richcontent-iframe.tpl');
	}

    public function hookDisplayProductExtraContent($params)
    {

        $product = new Product((int)Tools::getValue('id_product'));
		$languages = Language::getLanguages(false);
		$this->context->smarty->assign(array(
            'rich_content_field' => $product->rich_content_field,
            'rich_content_field_lang_wysiwyg' => $product->rich_content_field_lang_wysiwyg,

            'composition_field' => $product->composition_field,
            'composition_field_lang_wysiwyg' => $product->composition_field_lang_wysiwyg,

            'nutri_values_field' => $product->nutri_values_field,
            'nutri_values_field_lang_wysiwyg' => $product->nutri_values_field_lang_wysiwyg,

            'nutri_information_field' => $product->nutri_information_field,
            'nutri_information_field_lang_wysiwyg' => $product->nutri_information_field_lang_wysiwyg,

			'languages' => $languages,
			'id_lang' => $this->context->language->id,
            'id_language' => $this->context->language->id,
            )
		);

        $tab_contents = array($this->l('Skład') => $product->composition_field_lang_wysiwyg, $this->l('Wartości odżywcze') => $product->nutri_values_field_lang_wysiwyg, $this->l('Informacje żywieniowe') => $product->nutri_information_field_lang_wysiwyg);

        $tab = array();

        foreach($tab_contents as $key=>$tab_content){

            if($tab_content[$this->context->language->id] && $tab_content[$this->context->language->id] != ''){
                $tab[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                ->setTitle($key)
                ->setContent($tab_content[$this->context->language->id]);
            }
        }

        return $tab;
    }

	
}
