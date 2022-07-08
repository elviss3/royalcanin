<?php

// Security
if (!defined('_PS_VERSION_'))
	exit;


class viplogo extends Module {
    private $_html = '';
    private $_postErrors = array();

    public function __construct() {
        $this->name = 'viplogo';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'desig4VIP';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7');
    	$this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('VIP Logo');
        $this->description = $this->l('block config');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }



    public function install()
	{
                Configuration::updateValue($this->name . '_auto', 0);
                Configuration::updateValue($this->name . '_speed_slide', '3000');
                Configuration::updateValue($this->name . '_a_speed', '600');
                Configuration::updateValue($this->name . '_qty_products', 9);
                Configuration::updateValue($this->name . '_show_nextback', 1);
                Configuration::updateValue($this->name . '_show_control', 0);
                Configuration::updateValue($this->name . '_itemsDesktop', 5);
                Configuration::updateValue($this->name . '_itemsDesktopSmall', 4);
                Configuration::updateValue($this->name . '_itemsTablet', 3);
                Configuration::updateValue($this->name . '_itemsMobile', 2);

		// Set some defaults
        return parent::install() &&
         $this->registerHook('top')&&
		 $this->registerHook('displayBrandSlider')&&
         $this->registerHook('displayFooter')&&
		 $this->registerHook('displayHeader');

	}

        public function uninstall() {

				Configuration::deleteByName($this->name . '_auto');
                Configuration::deleteByName($this->name . '_speed_slide');
                Configuration::deleteByName($this->name . '_a_speed');
                Configuration::deleteByName($this->name . '_qty_products');
                Configuration::deleteByName($this->name . '_qty_items');
                Configuration::deleteByName($this->name . '_show_nextback');
                Configuration::deleteByName($this->name . '_show_control');
	        	Configuration::deleteByName($this->name . '_itemsDesktop');
	        	Configuration::deleteByName($this->name . '_itemsDesktopSmall');
	        	Configuration::deleteByName($this->name . '_itemsTablet');
	        	Configuration::deleteByName($this->name . '_itemsMobile');


		if (!parent::uninstall())
			return false;
		// !$this->unregisterHook('actionObjectExampleDataAddAfter')
		return true;
        }



	private function postProcess()
	{

		var_dump (Tools::getValue($this->name . '_auto'));

            Configuration::updateValue($this->name . '_auto', Tools::getValue($this->name . '_auto'));
            Configuration::updateValue($this->name . '_speed_slide', Tools::getValue($this->name . '_speed_slide'));
            Configuration::updateValue($this->name . '_a_speed', Tools::getValue($this->name . '_a_speed'));
            Configuration::updateValue($this->name . '_qty_products', Tools::getValue($this->name . '_qty_products'));
            Configuration::updateValue($this->name . '_show_nextback', Tools::getValue($this->name . '_show_nextback'));
            Configuration::updateValue($this->name . '_show_control', Tools::getValue($this->name . '_show_control'));
            Configuration::updateValue($this->name . '_itemsDesktop', Tools::getValue($this->name . '_itemsDesktop'));
            Configuration::updateValue($this->name . '_itemsDesktopSmall', Tools::getValue($this->name . '_itemsDesktopSmall'));
            Configuration::updateValue($this->name . '_itemsTablet', Tools::getValue($this->name . '_itemsTablet'));
            Configuration::updateValue($this->name . '_itemsMobile', Tools::getValue($this->name . '_itemsMobile'));
	}

	public function getContent()
	{
		$output = '';

		if (Tools::isSubmit('submit')) {
			$this->postProcess();
		}

		$output .= $this->displayConfirmation($this->l('Settings updated'));

		return $output . $this->displayForm();

	}

        public function getLogo() {

			 $manufacturers = Manufacturer::getManufacturers ();

        	return $manufacturers;
        }

	public function displayForm()
	{
		$fields_form = array(
		    'form' => array(
		        'legend' => array(
		            'title' => $this->l('Settings'),
		            'icon' => 'icon-cogs'
		        ),
		        'input' => array(

		        	array(
						'type' => 'hr',
						'title' => $this->l('Slider'),
						'name' => $this->l('Slider'),
						'icon' => 'icon-calendar'
					),

					array(
						'type' => 'switch',
						'label' => $this->l('Auto slide'),
						'name' => $this->name . '_auto',
					//	'desc' => $this->l('Show Firstname, one letter of Surname and city'),
						'required' => false,
						'values' => array(
						    array(
						        'id' => 'active_on',
						        'value' => 1,
						        'label' => $this->l('YES')
						    ),
						    array(
						        'id' => 'active_off',
						        'value' => 0,
						        'label' => $this->l('NO')
						    )
						),
					),

		        	array(
		        		'type' => 'text',
		        		'lang' => false,
		        		'label' => $this->l('Slideshow speed', array()),
		        		'name' => $this->name . '_speed_slide',
		        		'class' => 'input fixed-width-md'
		        	),

		        	array(
		        		'type' => 'text',
		        		'lang' => false,
		        		'label' => $this->l('Pause', array()),
		        		'name' => $this->name . '_a_speed',
		        		'class' => 'input fixed-width-md'
		        	),

		        	array(
						'type' => 'switch',
						'label' => $this->l('Show Next/Back control'),
						'name' => $this->name . '_show_nextback',
					//	'desc' => $this->l('Show Firstname, one letter of Surname and city'),
						'required' => false,
						'values' => array(
						    array(
						        'id' => 'active_on',
						        'value' => 1,
						        'label' => $this->l('YES')
						    ),
						    array(
						        'id' => 'active_off',
						        'value' => 0,
						        'label' => $this->l('NO')
						    )
						),
					),

		        	array(
						'type' => 'switch',
						'label' => $this->l('Show navigation control'),
						'name' => $this->name . '_show_control',
					//	'desc' => $this->l('Show Firstname, one letter of Surname and city'),
						'required' => false,
						'values' => array(
						    array(
						        'id' => 'active_on',
						        'value' => 1,
						        'label' => $this->l('YES')
						    ),
						    array(
						        'id' => 'active_off',
						        'value' => 0,
						        'label' => $this->l('NO')
						    )
						),
					),

		        	array(
						'type' => 'hr',
						'title' => $this->l('Items'),
						'name' => $this->l('Items'),
						'icon' => 'icon-calendar'
					),

		            array(
		                'type' => 'text',
		                'lang' => false,
		                'label' => $this->l('High Resolution'),
		                'desc' => $this->l('Resoution over 1200px'),
		                'name' => $this->name . '_qty_products',
		                'class' => 'input fixed-width-sm'
		            ),

		        	array(
		        	    'type' => 'text',
		        	    'lang' => false,
		        	    'label' => $this->l('Desktop'),
		        	    'desc' => $this->l('Resoution 912-1199px - [LG]'),
		        	    'name' => $this->name . '_itemsDesktop',
		        	    'class' => 'input fixed-width-sm'
		        	),

		        	array(
		        	    'type' => 'text',
		        	    'lang' => false,
		        	    'label' => $this->l('Small desktop'),
		        	    'desc' => $this->l('Resoution 768-911px - [MD]'),
		        	    'name' => $this->name . '_itemsDesktopSmall',
		        	    'class' => 'input fixed-width-sm'
		        	),

		        	array(
		        	    'type' => 'text',
		        	    'lang' => false,
		        	    'label' => $this->l('Tablet'),
		        	    'desc' => $this->l('Resoution 361-767px - [SM]'),
		        	    'name' => $this->name . '_itemsTablet',
		        	    'class' => 'input fixed-width-sm'
		        	),

		        	array(
		        	    'type' => 'text',
		        	    'lang' => false,
		        	    'label' => $this->l('Mobile'),
		        	    'desc' => $this->l('Resolution below 36opx - [XS]'),
		        	    'name' => $this->name . '_itemsMobile',
		        	    'class' => 'input fixed-width-sm'
		        	),




		        ),
		        'submit' => array(
		            'title' => $this->l('Save')
		        )
		    ),
		);

		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
		    'uri' => $this->getPathUri(),
		    'fields_value' => $this->getConfigFieldsValues(),
		    'languages' => $this->context->controller->getLanguages(),
		    'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{

		$fields = array();

		$fields[$this->name . '_auto'] = Tools::getValue($this->name . '_auto', Configuration::get($this->name . '_auto'));
		$fields[$this->name . '_speed_slide'] = Tools::getValue($this->name . '_speed_slide', Configuration::get($this->name . '_speed_slide'));
		$fields[$this->name . '_a_speed'] = Tools::getValue($this->name . '_a_speed', Configuration::get($this->name . '_a_speed'));
		$fields[$this->name . '_qty_products'] = Tools::getValue($this->name . '_qty_products', Configuration::get($this->name . '_qty_products'));
		$fields[$this->name . '_show_nextback'] = Tools::getValue($this->name . '_show_nextback', Configuration::get($this->name . '_show_nextback'));
		$fields[$this->name . '_show_control'] = Tools::getValue($this->name . '_show_control', Configuration::get($this->name . '_show_control'));
		$fields[$this->name . '_itemsDesktop'] = Tools::getValue($this->name . '_itemsDesktop', Configuration::get($this->name . '_itemsDesktop'));
		$fields[$this->name . '_itemsDesktopSmall'] = Tools::getValue($this->name . '_itemsDesktopSmall', Configuration::get($this->name . '_itemsDesktopSmall'));
		$fields[$this->name . '_itemsTablet'] = Tools::getValue($this->name . '_itemsTablet', Configuration::get($this->name . '_itemsTablet'));
		$fields[$this->name . '_itemsMobile'] = Tools::getValue($this->name . '_itemsMobile', Configuration::get($this->name . '_itemsMobile'));

		return $fields;
	}

        public function hookDisplayHeader()
        {
             //$this->context->controller->addCSS(($this->_path) . 'css/logo.css', 'all');
            // $this->context->controller->addJS($this->_path . 'js/pos.bxslider.min.js');
        }


	function hookDisplayBrandSlider($params) {

		$auto='false';
		if (Configuration::get($this->name . '_auto')==1) {
			$auto='true';
		}else{
			$auto='false';
		}

		$options = array(
                'auto' => $auto,
                'speed_slide' => Configuration::get($this->name . '_speed_slide'),
                'a_speed' => Configuration::get($this->name . '_a_speed'),
                'qty_products' => Configuration::get($this->name . '_qty_products'),
                'show_nexback' => Configuration::get($this->name . '_show_nextback'),
                'show_control' => Configuration::get($this->name . '_show_control'),
                'itemsDesktop' => Configuration::get($this->name . '_itemsDesktop'),
                'itemsDesktopSmall' => Configuration::get($this->name . '_itemsDesktopSmall'),
                'itemsTablet' => Configuration::get($this->name . '_itemsTablet'),
                'itemsMobile' => Configuration::get($this->name . '_itemsMobile'),
            );


            $logos = $this->getLogo();
            if(count($logos)<1) return NULL;
            $this->context->smarty->assign('slideOptions', $options);
            $this->context->smarty->assign('logos', $logos);
            return $this->display(__FILE__, 'logo.tpl');
	}



}