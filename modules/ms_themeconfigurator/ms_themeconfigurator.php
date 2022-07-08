<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ms_themeconfigurator extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'ms_themeconfigurator';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Sebastian Koziołek - Millenium Studio';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Konfigurator szablonu');
        $this->description = $this->l('Konfiguracja kolorów i głównych ustawień szablonu sklepu');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('MS_THEMECONFIGURATOR_GENERAL_COLOR', '#0292c9');

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('themeStyle') &&
            $this->registerHook('displayBackOfficeHeader') && 
            $this->installTab();
    }

    public function uninstall()
    {
        Configuration::deleteByName('MS_THEMECONFIGURATOR_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        $this->uninstallTab();

        return parent::uninstall();
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab()
        ;
    }

    public function disable($force_all = false)
    {
        return parent::disable($force_all)
            && $this->uninstallTab()
        ;
    }

        private function installTab()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminThemeConfigLink');
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = 'AdminThemeConfigLink';
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Konfiguracja szablonu', array(), 'Modules.ms_themeconfigurator.Admin', $lang['locale']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('CONFIGURE');
        $tab->module = $this->name;

        return $tab->save();
    }


    private function uninstallTab()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminThemeConfigLink');
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
    }


    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitMs_themeconfiguratorModule')) == true) {
            $this->postProcess();
        }

        $products_link = $this->context->link->getAdminLink('AdminProducts'); 
        $module_link = $this->context->link->getAdminLink('AdminModules');
        $module_token = Tools::getAdminToken((int) $this->context->employee->id);
        $themes_link =  $this->context->link->getAdminLink('AdminThemes');
        $contacts_link =  $this->context->link->getAdminLink('AdminStores');
        $search_link =  $this->context->link->getAdminLink('AdminSearchConf');
        $brands_link =  $this->context->link->getAdminLink('AdminManufacturers');
        $categories_link =  $this->context->link->getAdminLink('AdminCategories');

        $modules_array = ['ps_mainmenu','ms_manufacturerslider','ms_homebanners','ms_homeaboutus','is_imageslider','ps_socialfollow'];
        foreach($modules_array as $module) {
            $m_link = $module.'_link';
            $module_link = $this->context->link->getAdminLink('AdminModules').'&configure='.$module;
            $this->context->smarty->assign($m_link,  $module_link);  
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('module_link', $module_link);
        $this->context->smarty->assign('products_link', $products_link);
        $this->context->smarty->assign('module_token', $module_token);
        $this->context->smarty->assign('themes_link', $themes_link);
        $this->context->smarty->assign('contacts_link', $contacts_link);
        $this->context->smarty->assign('search_link', $search_link);
        $this->context->smarty->assign('brands_link', $brands_link);
        $this->context->smarty->assign('categories_link', $categories_link);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $this->renderForm().$output;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMs_themeconfiguratorModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Ustawienia'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(

                    array(
						'type' => 'hr',
						'title' => $this->l('Ustawienia kolorów'),
						'name' => $this->l('Ustawienia kolorów'),
						'icon' => 'icon-picture'
					),

                    array(
                        'type' => 'color',
                        'label' => $this->l('Główny kolor szablonu'),
                        'name' => 'MS_THEMECONFIGURATOR_GENERAL_COLOR',
                    ), 

                    array(
						'type' => 'hr',
						'title' => $this->l('Ustawienia główne'),
						'name' => $this->l('Ustawienia główne'),
						'icon' => 'icon-cogs'
					),

                    array(
                        'type' => 'conf_button', 
                        'label' => $this->l('Adres kontaktowy, adres sklepu(ów)'),
                        'href' => $this->context->link->getAdminLink('AdminStores'),   
                        'title' =>  $this->l('Przejdź do konfiguracji'),
                        'name' => 'address_conf',
                        'id'   => 'address_conf',
                        'class' => '',                
                    ),

                    array(
                        'type' => 'conf_button', 
                        'label' => $this->l('Ustawienia wysyłki email'),
                        'href' => $this->context->link->getAdminLink('AdminEmails'),   
                        'title' =>  $this->l('Przejdź do konfiguracji'),
                        'name' => 'emails_conf',
                        'id'   => 'emails_conf',
                        'class' => '',                
                    ),

                    // array(
					// 	'type' => 'hr',
					// 	'title' => $this->l(''),
					// 	'name' => $this->l(''),
					// 	'icon' => 'icon-picture'
					// ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'MS_THEMECONFIGURATOR_GENERAL_COLOR' => Configuration::get('MS_THEMECONFIGURATOR_GENERAL_COLOR', '#0292c9'),

        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayBackOfficeHeader()
    {   
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css'); 
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookThemeStyle($params)
    {
        $this->smarty->assign(array(
            'g_color' => Configuration::get('MS_THEMECONFIGURATOR_GENERAL_COLOR', '#0292c9'),
        ));

		return $this->display(__FILE__, 'views/templates/hook/themestyle.tpl');
    }
}
