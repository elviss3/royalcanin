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

class Ms_homebanners extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'ms_homebanners';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Sebastian Koziołek - Millenium Studio';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Blok banerów na stornie głownej ');
        $this->description = $this->l('Wyświetla 4 banery na stronie głównej');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        for($i=1; $i<=4; $i++) {
            Configuration::updateValue('MS_homebanners_'.$i.'_IMG', '');
            Configuration::updateValue('MS_homebanners_'.$i.'_TXT', '');
            Configuration::updateValue('MS_homebanners_'.$i.'_LNK', '');
            Configuration::updateValue('MS_homebanners_TITLE', '');
        }

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        for($i=1; $i<=4; $i++) {
            Configuration::deleteByName('MS_homebanners_'.$i.'_IMG');
            Configuration::deleteByName('MS_homebanners_'.$i.'_TXT');
            Configuration::deleteByName('MS_homebanners_'.$i.'_LNK');
            Configuration::deleteByName('MS_homebanners_TITLE');
        }

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitMs_homebannersModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->renderForm();
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
        $helper->submit_action = 'submitMs_homebannersModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
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
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(

                    array(
                        'type' => 'file_lang',
                       // 'label' => 'Obrazek',
                        'name' => 'MS_homebanners_1_IMG',
                        //'desc' => 'Obrazek powinien mieć rozdzielczość 350 x 314 px',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Podpis',
                        'name' => 'MS_homebanners_1_TXT',
                        'desc' => 'Podpis obrazka "alt"'
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Link',
                        'name' => 'MS_homebanners_1_LNK'
                    ),

                    array(
                        'type' => 'hr',
                        'lang' => false,
                        'title' => 'Obrazek 2',
                        'name' => 'HR'
                    ),

                    array(
                        'type' => 'file_lang',
                       // 'label' => 'Obrazek',
                        'name' => 'MS_homebanners_2_IMG',
                        //'desc' => 'Obrazek powinien mieć rozdzielczość 350 x 314 px',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Podpis',
                        'name' => 'MS_homebanners_2_TXT',
                        'desc' => 'Podpis obrazka "alt"'
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Link',
                        'name' => 'MS_homebanners_2_LNK'
                    ),

                    array(
                        'type' => 'hr',
                        'lang' => false,
                        'title' => 'Obrazek 3',
                        'name' => 'HR'
                    ),

                    array(
                        'type' => 'file_lang',
                    //    'label' => 'Obrazek',
                        'name' => 'MS_homebanners_3_IMG',
                        //'desc' => 'Obrazek powinien mieć rozdzielczość 350 x 314 px',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Podpis',
                        'name' => 'MS_homebanners_3_TXT',
                        'desc' => 'Podpis obrazka "alt"'
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Link',
                        'name' => 'MS_homebanners_3_LNK'
                    ),

                    array(
                        'type' => 'hr',
                        'lang' => false,
                        'title' => 'Obrazek 4',
                        'name' => 'HR'
                    ),

                    array(
                        'type' => 'file_lang',
                     //   'label' => 'Obrazek',
                        'name' => 'MS_homebanners_4_IMG',
                        //'desc' => 'Obrazek powinien mieć rozdzielczość 350 x 314 px',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Podpis',
                        'name' => 'MS_homebanners_4_TXT',
                        'desc' => 'Podpis obrazka "alt"'
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Link',
                        'name' => 'MS_homebanners_4_LNK'
                    ),
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
        $languages = Language::getLanguages(false);
        $fields = array();

        for($i=1; $i<=4; $i++) {    
            foreach ($languages as $lang) {
                $fields['MS_homebanners_'.$i.'_IMG'][$lang['id_lang']] = Tools::getValue('MS_homebanners_'.$i.'_IMG_'.$lang['id_lang'], Configuration::get('MS_homebanners_'.$i.'_IMG', $lang['id_lang']));
                $fields['MS_homebanners_'.$i.'_TXT'][$lang['id_lang']] = Tools::getValue('MS_homebanners_'.$i.'_TXT_'.$lang['id_lang'], Configuration::get('MS_homebanners_'.$i.'_TXT', $lang['id_lang']));
                $fields['MS_homebanners_'.$i.'_LNK'][$lang['id_lang']] = Tools::getValue('MS_homebanners_'.$i.'_LNK_'.$lang['id_lang'], Configuration::get('MS_homebanners_'.$i.'_LNK', $lang['id_lang']));
            }
        }

        return $fields;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {

        if (Tools::isSubmit('submitMs_homebannersModule')) {
            $languages = Language::getLanguages(false);
            $values = array();
            $update_images_values = false;

            for($i=1; $i<=4; $i++) {
                foreach ($languages as $lang) {
                    if (isset($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']])
                        && isset($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']]['tmp_name'])
                        && !empty($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']]['tmp_name'])) {
                        if ($error = ImageManager::validateUpload($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']], 4000000)) {
                            return $this->displayError($error);
                        } else {
                            $ext = substr($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']]['name'], strrpos($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']]['name'], '.') + 1);
                            $file_name = md5($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']]['name']).'.'.$ext;

                            if (!move_uploaded_file($_FILES['MS_homebanners_'.$i.'_IMG_'.$lang['id_lang']]['tmp_name'], dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$file_name)) {
                                return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
                            } else {
                                if (Configuration::hasContext('MS_homebanners_'.$i.'_IMG', $lang['id_lang'], Shop::getContext())
                                    && Configuration::get('MS_homebanners_'.$i.'_IMG', $lang['id_lang']) != $file_name) {
                                    @unlink(dirname(__FILE__).DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . Configuration::get('MS_homebanners_'.$i.'_IMG', $lang['id_lang']));
                                }

                                $values['MS_homebanners_'.$i.'_IMG'][$lang['id_lang']] = $file_name;
                            }
                        }

                        $update_images_values = true;
                    }

                    $values['MS_homebanners_'.$i.'_TXT'][$lang['id_lang']] = Tools::getValue('MS_homebanners_'.$i.'_TXT_'.$lang['id_lang']);
                    $values['MS_homebanners_'.$i.'_LNK'][$lang['id_lang']] = Tools::getValue('MS_homebanners_'.$i.'_LNK_'.$lang['id_lang']);
                    
                }

                if ($update_images_values) {
                    Configuration::updateValue('MS_homebanners_'.$i.'_IMG', $values['MS_homebanners_'.$i.'_IMG']);
                }

                Configuration::updateValue('MS_homebanners_'.$i.'_TXT', $values['MS_homebanners_'.$i.'_TXT'], true);
                Configuration::updateValue('MS_homebanners_'.$i.'_LNK', $values['MS_homebanners_'.$i.'_LNK']);
                
            }

            foreach ($languages as $lang) {
                Configuration::updateValue('MS_homebanners_TITLE_'.$lang['id_lang'], Tools::getValue('MS_homebanners_TITLE_'.$lang['id_lang']));
            }

            //$this->_clearCache($this->templateFile);

            return $this->displayConfirmation($this->trans('The settings have been updated.', array(), 'Admin.Notifications.Success'));
        }

        return '';
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayHome()
    {
        
        for($i=1; $i<=4; $i++) {
            $imgname = Configuration::get('MS_homebanners_'.$i.'_IMG', $this->context->language->id);

            if ($imgname && file_exists(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$imgname)) {
                $this->smarty->assign('newest_img_'.$i, $this->context->link->protocol_content . Tools::getMediaServer($imgname) . $this->_path . 'img/' . $imgname);
            }
 
            $newest_link = Configuration::get('MS_homebanners_'.$i.'_LNK', $this->context->language->id);
            if (!$newest_link) {
                $newest_link = $this->context->link->getPageLink('index');
            }

            $this->context->smarty->assign(array(
                'newest_link_'.$i => $newest_link,
                'newest_text_'.$i => Configuration::get('MS_homebanners_'.$i.'_TXT', $this->context->language->id)
            ));
        }

		return $this->display(__FILE__, 'ms_homebanners.tpl');

    }

}
