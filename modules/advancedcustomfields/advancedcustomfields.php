<?php
/**
*
*
*    Advanced Custom Fields
*    Copyright 2018  Inno-mods.io
*
*    @author    Inno-mods.io
*    @copyright Inno-mods.io
*    @version   1.1
*    Visit us at http://www.inno-mods.io
*
*
**/

/*
* check presta
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

/*
* require our models
*/
require_once _PS_MODULE_DIR_.'advancedcustomfields/models/AdvancedCustomFields.php';
require_once _PS_MODULE_DIR_.'advancedcustomfields/models/AdvancedCustomFieldsContent.php';


/*
* Prestapylon Module
*/
class AdvancedCustomFields extends Module
{

    /*
    * constructor
    */
    public function __construct()
    {
        $this->name = 'advancedcustomfields';
        $this->tab = 'administration';
        $this->version = '1.1';
        $this->author = 'Inno-mods.io';
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => '1.7.10.10' );
        $this->bootstrap = true;

        parent::__construct(); //needed for translations

        $this->displayName = $this->l('Advanced Custom Fields');
        $this->description = $this->l($this->displayName.' is a plugin that adds custom fields to Prestashop!! ');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall '.$this->displayName.'?');


        // to be deleted in the future
        // it is put here in order to add the hook without uninstalling
        $this->registerHook('actionProductAdd');
    }



    /*
    * install
    */
    public function install()
    {
        if (Shop::isFeatureActive()) { //  check whether the multistore feature is active or not, and if at least two stores are presently activated
            Shop::setContext(Shop::CONTEXT_ALL); // change the context in order  to apply coming changes to all existing stores instead of only the current store
        }

        if (!parent::install() ||
            !AdvancedCustomFieldsModel::createSchema() || // acf configuration
            !AdvancedCustomFieldsContentModel::createSchema() || // acf content
            !$this->registerHook('displayHeader') || // register front office header hook
            !$this->registerHook('displayBackOfficeHeader') || // register admin header hook
            !$this->registerHook('actionAdminCustomersFormModifier') || // admin: customer form hook
            !$this->registerHook('actionAdminCustomersControllerSaveAfter') || // admin: customer save
            !$this->registerHook('displayAdminCustomers') || // register admin view customer page hook
            !$this->registerHook('actionAdminCategoriesFormModifier') || // admin: category form hook
            !$this->registerHook('actionAdminCategoriesControllerSaveAfter') || // admin: category save
            !$this->registerHook('displayAdminProductsMainStepLeftColumnMiddle') || // admin: product page
            !$this->registerHook('displayAdminProductsMainStepLeftColumnBottom') || // admin: product page
            !$this->registerHook('displayAdminProductsMainStepRightColumnBottom') || // admin: product page
            !$this->registerHook('displayAdminProductsQuantitiesStepBottom') || // admin: product page
            !$this->registerHook('displayAdminProductsPriceStepBottom') || // admin: product page
            !$this->registerHook('displayAdminProductsOptionsStepTop') || // admin: product page
            !$this->registerHook('displayAdminProductsOptionsStepBottom') || // admin: product
            !$this->registerHook('displayAdminProductsSeoStepBottom') || // admin: product page
            !$this->registerHook('displayAdminProductsMainStepRightColumnBottom') || // register admin product page hook
            !$this->registerHook('actionProductSave') || // register admin product save action
            !$this->registerHook('actionProductAdd') || // register admin product duplicate action
            !$this->registerHook('additionalCustomerFormFields') || // front: customer form hook
            !$this->registerHook('actionObjectCustomerAddAfter') || // front: action add customer
            !$this->registerHook('actionObjectCustomerUpdateAfter') || // front: action update customer
            !$this->registerHook('validateCustomerFormFields') // front: validate customer fields
        ){ // install, else return false
            return false;
        }


        // return true
        return true;
    }



    /*
    * uninstall
    */
    public function uninstall()
    {

        // uninstall, if something fails, return false
        if (!parent::uninstall() ||
            !AdvancedCustomFieldsModel::DropSchema() || // drop acf configuration
            !AdvancedCustomFieldsContentModel::DropSchema() // drop acf content
        ){  // uninstall
            return false;
        }

        // return
        return true;
    }


    /*
    * psVersion
    */
    public static function psVersion()
    {
        $version = _PS_VERSION_;
        $exp = explode('.', $version);
        return $exp[0].'.'.$exp[1];
    }




    /*
    * with a little help from __call we create the callback functions for the hooks
    */
    public function __call($method, $args)
    {
        // get hookName
        $hookName = lcfirst(Tools::substr($method, 4));
        if (!Validate::isHookName($hookName)) {
            return false;
        }

        // return hook content
        return $this->displayHook($hookName);
    }


    /*
    * displayHook
    */
    public function displayHook($hook)
    {
        // get custom field from technical name
        $customField = AdvancedCustomFieldsModel::getCustomFieldFromTechnicalName($hook, $this->context->language->id);

        // get current controller (check for category and product pages)
        $currentLocation = $this->context->controller->php_self;
        // get resource id according to location
        if ($customField['location'] == 'category' && $currentLocation == 'category' ){
            $resource_id = Tools::getValue('id_category');
        }
        // get resource id according to location
        if ($customField['location'] == 'product' && $currentLocation == 'product'){
            $resource_id = Tools::getValue('id_product');
        }
        // get current customer
        if ($customField['location'] == 'customer' && $this->context->customer->id!=''){
            $resource_id = $this->context->customer->id;
        }

        // if field is active and dispalyable
        if ($customField['active'] && $customField['display_frontend']){

            if ($resource_id!=0){
                // check if a stored value exists
                $contentID = AdvancedCustomFieldsContentModel::getContentID($this->context->shop->id, $customField['id_custom_field'], $resource_id);
                // get stored value
                $content = new AdvancedCustomFieldsContentModel($contentID);
            } else {
                $contentID = false;
            }

            if ($contentID){
                // get displayable value
                $customField['value'] = $this->getDisplayableValue($customField,$content);

                // assign to smarty
                $this->smarty->assign(array(
                                            'show_label'           => $customField['display_label_frontend'],
                                            'label'                => $customField['name'],
                                            'value'                => $customField['value']
                                          ));

                // return
                return $this->display(__FILE__, '/views/templates/hook/customHook.tpl');
            }
        } else {
            return;
        }
    }



    /*
    * back office includes
    */
    public function hookDisplayBackOfficeHeader($params)
    {
        // add jquery
        $this->context->controller->addJquery();
        // add styling
        if ($this->psVersion() == '1.7') {
            $this->context->controller->addCSS($this->_path.'/views/css/advancedcustomfields-backend.css', 'all');
            $this->context->controller->addJS($this->_path.'/views/js/advancedcustomfields-backend.js', 'all');
        }
    }



    /*
    * getContent - actually it works like a module index page
    */
    public function getContent()
    {
        $output = null;

        // get controller
        $controller = Tools::getValue('moduleController');

        // baseAdminModuleUrl
        $baseAdminModuleUrl = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'';

    		/*
    		* save configuration
    		*/
        if (Tools::getValue('submitCustomFieldForm')) {

            // get languages
            $langs = $this->getLanguagesList();

            /*
            * update case
            */
            if ((int)Tools::getValue('id_custom_field')) {

                // get field
                $customField = new AdvancedCustomFieldsModel((int)Tools::getValue('id_custom_field'));

                // get shop list ids
                $shopListIds = $this->getShopsListIds();
                // assign shop only if it user has access on it
                if (!in_array($this->context->shop->id, $shopListIds)) {
                    // redirect , otherwise.. because employee is not allowed to edit this shop
                    Tools::redirectAdmin($baseAdminModuleUrl.'&moduleController=customFieldForm');
                    die;
                }

                // initialize an error array
                $currentErrors = array();

                // if name is blank ( in even one of the available languages ), add an error
                $tempError = false;
                // if name is blank ( in even one of the available languages ), redirect with error
                foreach ($langs as $lang) {
                    if (Tools::getValue('name_'.$lang['id_lang']) == '') {
                      $tempError = true;
                    }
                }
                if ($tempError){
                    $currentErrors[] = 'The name of the field is required in all available languages!';
                }

                // check that location in not empty
                if(Tools::getValue('location')==''){
                    $currentErrors[] = 'The location of the field is required!';
                }

                // check that tecnical name in not empty
                if(Tools::getValue('technical_name')==''){
                    $currentErrors[] = 'The technical name of the field is required!';
                }

                // check that technical name is unique
                if (AdvancedCustomFieldsModel::checkIfTechnicalNameExists(Tools::getValue('technical_name'),$customField->id_custom_field)){
                    $currentErrors[] = 'The technical name of the field already exists! Please type a different one!';
                }

                // check that only letters - numbers - underscore are allowed in the technical name
                if (!AdvancedCustomFieldsModel::isTechnicalName(Tools::getValue('technical_name'))){
                    $currentErrors[] = 'Only letters, numbers and the underscore character (_) are allowed for technical name!';
                }

                // check that admin product hook in not empty in case of product location
                if (Tools::getValue('location')=='product' && Tools::getValue('admin_product_hook')==''){
                    $currentErrors[] = 'Please choose an admin hook for the custom field!';
                }


                // if we have errors
                if (count($currentErrors)>0){
                    // keep them in context
                    $this->context->controller->errors = array_merge($this->context->controller->errors,$currentErrors);

                } else {
                    // if technical name got updated, reset custom hook
                    if ($customField->technical_name != Tools::getValue('technical_name')){
                        // unregister previous custom hook
                        $this->unregisterHook($customField->technical_name);
                        // register custom hook for field display
                        $this->registerHook(Tools::getValue('technical_name'));
                    }

                    // init an empty array for each multilanguage attribute
                    $name = array();
                    $description = array();
                    $available_values = array();
                    $default_value = array();
                    $single_label = array();
                    // foreach shop language populate array
                    foreach ($langs as $lang) {
                        $name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
                        $description[$lang['id_lang']] = Tools::getValue('description_'.$lang['id_lang']);
                        $available_values[$lang['id_lang']] = Tools::getValue('available_values_'.$lang['id_lang']);
                        $default_value[$lang['id_lang']] = Tools::getValue('default_value_'.$lang['id_lang']);
                        $single_label[$lang['id_lang']] = Tools::getValue('single_label_'.$lang['id_lang']);
                    }
                    // assign multilanguage attributes
                    $customField->name = $name;
                    $customField->description = $description;
                    $customField->available_values = $available_values;
                    $customField->default_value = $default_value;
                    $customField->single_label = $single_label;

                    // assign technical name
                    $customField->technical_name = Tools::getValue('technical_name');
                    // assign required
                    $customField->required = (int)Tools::getValue('required');
                    // assign active
                    $customField->active = (int)Tools::getValue('active');
                    // assign display_frontend
                    $customField->display_frontend = (int)Tools::getValue('display_frontend');
                    // assign display_label_frontend
                    $customField->display_label_frontend = (int)Tools::getValue('display_label_frontend');
                    // assign editable_frontend
                    $customField->editable_frontend = (int)Tools::getValue('editable_frontend');
                    // assign type
                    if (Tools::getValue('location')=='customer' && Tools::getValue('editable_frontend')){
                        $customField->type = Tools::getValue('type_frontend_form');
                    } else {
                        $customField->type = Tools::getValue('type_admin_form');
                    }
                    // assign translatable
                    $customField->translatable = Tools::getValue('translatable');
                    // assign allow_empty_select
                    $customField->allow_empty_select = Tools::getValue('allow_empty_select');
                    // assign default_status
                    $customField->default_status = Tools::getValue('default_status');
                    // assign location
                    $customField->location = Tools::getValue('location');
                    // for products
                    if (Tools::getValue('location')=='product'){
                        // assign admin hook
                        $customField->admin_hook = Tools::getValue('admin_product_hook');
                    }

                    // save tab
                    $customField->save();

                    // redirect
                    Tools::redirectAdmin($baseAdminModuleUrl.'&moduleController=customFieldForm&id_custom_field='.$customField->id.'&msg=success');
                }

            /*
            * insert case
            */
            } else {

                // get shop list ids
                $shopListIds = $this->getShopsListIds();
                // assign shop only if it user has access on it
                if (!in_array($this->context->shop->id, $shopListIds)) {
                    // redirect , otherwise.. because employee is not allowed to edit this shop
                    Tools::redirectAdmin($baseAdminModuleUrl.'&moduleController=customFieldForm');
                    die;
                }

                // initialize an error array
                $currentErrors = array();

                // if name is blank ( in even one of the available languages ), add an error
                $tempError = false;
                foreach ($langs as $lang) {
                    if (Tools::getValue('name_'.$lang['id_lang']) == '') {
                        $tempError = true;
                    }
                }
                if ($tempError){
                    $currentErrors[] = 'The name of the field is required in all available languages!';
                }

                // check that location in not empty
                if(Tools::getValue('location')==''){
                    $currentErrors[] = 'The location of the field is required!';
                }

                // check that tecnical name in not empty
                if(Tools::getValue('technical_name')==''){
                    $currentErrors[] = 'The technical name of the field is required!';
                }

                // check that technical name is unique
                if (AdvancedCustomFieldsModel::checkIfTechnicalNameExists(Tools::getValue('technical_name'))){
                    $currentErrors[] = 'The technical name of the field already exists! Please type a different one!';
                }

                // check that only letters - numbers - underscore are allowed
                if (!AdvancedCustomFieldsModel::isTechnicalName(Tools::getValue('technical_name'))){
                    $currentErrors[] = 'Only letters, numbers and the underscore character (_) are allowed for technical name!';
                }

                // check that admin product hook in not empty in case of product location
                if (Tools::getValue('location')=='product' && Tools::getValue('admin_product_hook')==''){
                    $currentErrors[] = 'Please choose an admin hook for the custom field!';
                }

                // if we have errors
                if (count($currentErrors)>0){
                    // keep them in context
                    $this->context->controller->errors = array_merge($this->context->controller->errors,$currentErrors);

                } else {

                    // create new field
                    $customField = new AdvancedCustomFieldsModel();

                    // init an empty array for each multilanguage attribute
                    $name = array();
                    $description = array();
                    $available_values = array();
                    $default_value = array();
                    $single_label = array();
                    // foreach shop language populate array
                    foreach ($langs as $lang) {
                        $name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
                        $description[$lang['id_lang']] = Tools::getValue('description_'.$lang['id_lang']);
                        $available_values[$lang['id_lang']] = Tools::getValue('available_values_'.$lang['id_lang']);
                        $default_value[$lang['id_lang']] = Tools::getValue('default_value_'.$lang['id_lang']);
                        $single_label[$lang['id_lang']] = Tools::getValue('single_label_'.$lang['id_lang']);
                    }
                    // assign multilanguage attributes
                    $customField->name = $name;
                    $customField->description = $description;
                    $customField->available_values = $available_values;
                    $customField->default_value = $default_value;
                    $customField->single_label = $single_label;

                    // assign technical name
                    $customField->technical_name = Tools::getValue('technical_name');
                    // assign required
                    $customField->required = (int)Tools::getValue('required');
                    // assign active
                    $customField->active = (int)Tools::getValue('active');
                    // assign display_frontend
                    $customField->display_frontend = (int)Tools::getValue('display_frontend');
                    // assign display_label_frontend
                    $customField->display_label_frontend = (int)Tools::getValue('display_label_frontend');
                    // assign editable_frontend
                    $customField->editable_frontend = (int)Tools::getValue('editable_frontend');
                    // assign type
                    if (Tools::getValue('location')=='customer' && Tools::getValue('editable_frontend')){
                        $customField->type = Tools::getValue('type_frontend_form');
                    } else {
                        $customField->type = Tools::getValue('type_admin_form');
                    }
                    // assign translatable
                    $customField->translatable = Tools::getValue('translatable');
                    // assign allow_empty_select
                    $customField->allow_empty_select = Tools::getValue('allow_empty_select');
                    // assign default_status
                    $customField->default_status = Tools::getValue('default_status');
                    // assign location
                    $customField->location = Tools::getValue('location');
                    // assign store
                    $customField->id_store = $this->context->shop->id;

                    // for products
                    if (Tools::getValue('location')=='product'){
                        // assign admin hook
                        $customField->admin_hook = Tools::getValue('admin_product_hook');
                    }

                    // save tab
                    $customField->save();

                    // register custom hook for field display
                    $this->registerHook(Tools::getValue('technical_name'));

                    // redirect
                    Tools::redirectAdmin($baseAdminModuleUrl.'&moduleController=customFieldForm&id_custom_field='.$customField->id.'&msg=success');

                }

            }

        }








        if ($controller=='customFieldForm'){

            // delete the custom field
            if (Tools::getIsset('delete') && Tools::getValue('id_custom_field')!=''){
                // delete the custom field
                AdvancedCustomFieldsModel::deleteCustomField(Tools::getValue('id_custom_field'));
                // delete all related content
                AdvancedCustomFieldsContentModel::deleteCustomFieldContent(Tools::getValue('id_custom_field'));
                // redirect to previous page
                Tools::redirectAdmin($baseAdminModuleUrl.'&moduleController=getCustomFields&location='.Tools::getValue('location').'');
                die;

            // add-edit a field form
            } else {
                // add-edit a field form
                $output = $this->FieldForm();
            }

        } else if ($controller=='getCustomFields'){
            // get customer custom fields
            $output = $this->LocationFieldsList(Tools::getValue('location'));
        } else {
            // get module dashboard page
            $output = $this->Dashboard();
        }

        // return
        return $this->getHeader().$output.$this->getFooter();
    }







    /*
    * Dashboard
    */
    public function Dashboard()
    {
        // current url
        $currentUrl = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        // assign to smarty
        $this->smarty->assign(array(
                                    'uri'       => $currentUrl
                                    ));
        // return template
        return $this->display(__FILE__, '/views/templates/admin/dashboard.tpl');
    }




    /*
    * FieldForm
    */
    public function FieldForm()
    {

        // Get default Language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Get custom field id
        $customFieldID = Tools::getValue('id_custom_field');


    		// current url
    		$currentUrl = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;


        // Init Fields form array
    		$fields_form = array();

        // check if id exists...
        if (Tools::getValue('id_custom_field')!=''){
            // set title
            $title = $this->l('Edit Custom Field');
            // get custom field
            $customField = new AdvancedCustomFieldsModel($customFieldID);
            // add info box
            $customHookInfoBox = '<div class="alert alert-info">'.$this->l('Add:').' <b>{hook h=\''.$customField->technical_name.'\'}</b> '.$this->l('into your theme template, in order to display the custom field!').'</div>';
        } else {
            // set title
            $title = $this->l('Add a new Custom Field');
            // unset info box
            $customHookInfoBox = '';
        }


				/*
				* general
				*/
		    $fields_form['general']['form'] = array(
		        'legend' => array(
		            'title' => $title,
		            'icon' => 'icon-cogs'
		        ),
		        'input' => array(
        		                array(
        		                    'type'      => 'hidden',
        		                    'name'      => 'customFieldForm',
        		                ),
                            array(
                                'type'     => 'hidden',
                                'name'     => 'id_custom_field',
                            ),
                            array(
                                    'type' => 'select',
                                    'label' => $this->l('Location'),
                                    'name' => 'location',
           			                    'required' => true,
                                    'options' => array(
                                        'query' => $this->availableCustomFieldLocations(),
                                        'id' => 'id',
                                        'name' => 'name'
                                    ),
                                    'desc'     => $this->l('Please choose the location of your custom field.').'<br>'.$this->l('Keep in mind that configuration options for each location are slightly different.')
                            ),
                            array(
          			                   'type'     => 'text',
          			                   'label'    => $this->l('Technical name'),
          			                   'name'     => 'technical_name',
          			                   'required' => true,
                                   'class'    => 'input fixed-width-xxl',
                                   'hint'     => $this->trans('Only letters, numbers and the underscore character (_) are allowed.'),
          			                   'desc'     => $this->l('Only visible in the backend').$customHookInfoBox,
          			            ),
                            array(
          			                   'type'     => 'text',
          			                   'label'    => $this->l('Name'),
          			                   'name'     => 'name',
          			                   'required' => true,
          			                   'desc'     => $this->l('You should fill in the name of the field in all available languages'),
                                   'lang'     => true,
          			            ),
                            array(
                                    'type'     => 'textarea',
                                    'label'    => $this->l('Description'),
                                    'name'     => 'description',
                                    'desc'     => $this->l('You can write instructions or a description of the field.'),
                                    'lang'     => true
                            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Required'),
          			                    'name'      => 'required',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'disable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'disable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
                                    'form_group_class' => 'required-option',
          			            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Active'),
          			                    'name'      => 'active',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'disable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'disable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
          			            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Display at frontend'),
          			                    'name'      => 'display_frontend',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'disable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'disable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
                                    'desc'     => $this->l('Choose if you want to display the field at the respective custom hook at frontend.'),
                                    'form_group_class' => 'display-frontend-options',
          			            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Display label at frontend'),
          			                    'name'      => 'display_label_frontend',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'disable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'disable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
                                    'desc'     => $this->l('Choose if you want to display the field\'s label at the respective custom hook at frontend.'),
                                    'form_group_class' => 'display-frontend-options',
          			            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Editable by customer'),
          			                    'name'      => 'editable_frontend',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'disable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'disable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
                                    'desc'     => $this->l('Choose if you want the field to be editable by the customer.').'<br>'.$this->l('There are more custom field types available for admin only fields.'),
                                    'form_group_class' => 'editable-frontend-option',
          			            ),
                            array(
                                    'type' => 'select',
                                    'label' => $this->l('Field type'),
                                    'name' => 'type_frontend_form',
                                    'options' => array(
                                        'query' => $this->availableCustomFieldTypesFrontend(),
                                        'id' => 'id',
                                        'name' => 'name'
                                    ),
                                    'form_group_class' => 'type-frontend-form-option',
                            ),
                            array(
                                    'type' => 'select',
                                    'label' => $this->l('Field type'),
                                    'name' => 'type_admin_form',
                                    'options' => array(
                                        'query' => $this->availableCustomFieldTypesAdmin(),
                                        'id' => 'id',
                                        'name' => 'name'
                                    ),
                                    'form_group_class' => 'type-admin-form-option',
                            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Translatable'),
          			                    'name'      => 'translatable',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'translatable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'translatable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
                                    'form_group_class' => 'translatable-option'
          			            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Add an empty row in the beginning'),
          			                    'name'      => 'allow_empty_select',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'translatable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'translatable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
                                    'form_group_class' => 'allow-empty-select-option'
          			            ),
                            array(
          			                    'type'      =>  'switch',
          			                    'label'     => $this->l('Enabled or checked by default'),
          			                    'name'      => 'default_status',
          			                    'class'     => 't',
          			                    'is_bool'   => true,
          			                    'values'    => array(
          			                                        array(
          			                                            'id' => 'translatable_on',
          			                                            'value' => 1,
          			                                            'label' => $this->l('Yes')
          			                                        ),
          			                                        array(
          			                                            'id' => 'translatable_off',
          			                                            'value' => 0,
          			                                            'label' => $this->l('No')
          			                                        )
          			                    ),
                                    'form_group_class' => 'default-status-option'
          			            ),
                            array(
          			                   'type'     => 'textarea',
          			                   'label'    => $this->l('Available values'),
          			                   'name'     => 'available_values',
          			                   'desc'     => $this->l('Format: label1:value1,label2:value2,label3:value3'),
                                   'lang'     => true,
                                   'form_group_class' => 'available-values-option'
          			            ),
                            array(
          			                   'type'     => 'textarea',
          			                   'label'    => $this->l('Default value'),
          			                   'name'     => 'default_value',
          			                   'desc'     => $this->l('For checklists, use comma separated values'),
                                   'lang'     => true,
                                   'form_group_class' => 'default-value-option'
          			            ),
                            array(
          			                   'type'     => 'text',
          			                   'label'    => $this->l('Label'),
          			                   'name'     => 'single_label',
                                   'lang'     => true,
                                   'form_group_class' => 'single-label-option'
          			            ),
                            array(
                                    'type' => 'radio',
                                    'label' => $this->l('Admin product hook'),
                                    'name' => 'admin_product_hook',
                                    'values' =>  $this->availableAdminProductHooks(),
                                    'form_group_class' => 'admin-product-hook-option',
           			                    'desc'     => $this->l('Please choose one location for the custom field in the admin product edit screen.'),
                            ),
		        ),
		        'submit' => array(
		            'title' => $this->l('Save'),
		            'class' => 'button'
		        )
		    );




        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&moduleController=customFieldForm';

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;


        // Toolbar
        $helper->show_toolbar = false;        // false -> remove toolbar
        $helper->toolbar_scroll = false;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submitCustomFieldForm';
        $helper->toolbar_btn = array(
                                    'save' => array(
                                                    'desc' => $this->l('Save'),
                                                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                                                    '&token='.Tools::getAdminTokenLite('AdminModules'),
                                                ),
                                    'back' => array(
                                        'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'',
                                        'desc' => $this->l('Back to list')
                                    )
                                );


        // get custom field info
        if ($customFieldID != '') {
            $config = $this->getCustomField($customFieldID);
        // or set default field values
        } else {
            $config = $this->getCustomFieldDefaultSettings();
        }

        $helper->tpl_vars = array(
            'fields_value' =>  $config ,
            'languages' => $this->getLanguagesList(),
            'id_language' => $this->context->language->id,
        );



        $this->smarty->assign(array(
                                    'uri'         				=> $currentUrl,
                                    'errors'              => $this->context->controller->errors,
                                    'saveSuccess'				=> Tools::getValue('msg')=='success'?true:false,
	                                  'form' 						=> $helper->generateForm($fields_form),
                                    ));
        // reset errors
        $this->context->controller->errors = '';
        // return template
        return $this->display(__FILE__, '/views/templates/admin/field-form.tpl');

    }


    /*
    * get default values
    */
    public function getCustomFieldDefaultSettings()
    {

        // get languages
        $langs = $this->getLanguagesList();
        // init an empty array for each multilanguage attribute
        $name = array();
        $description = array();
        $available_values = array();
        $default_value = array();
        $single_label = array();
        // foreach shop language populate array
        foreach ($langs as $lang) {
            $name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
            $description[$lang['id_lang']] = Tools::getValue('description_'.$lang['id_lang']);
            $available_values[$lang['id_lang']] = Tools::getValue('available_values_'.$lang['id_lang']);
            $default_value[$lang['id_lang']] = Tools::getValue('default_value_'.$lang['id_lang']);
            $single_label[$lang['id_lang']] = Tools::getValue('single_label_'.$lang['id_lang']);
        }

        // assign values
        $settings = array(
            'customFieldForm'             => true,
            'id_custom_field'             => null,
            'technical_name'              => Tools::getValue('technical_name')?Tools::getValue('technical_name'):null,
            'name'                        => count($name)>0?$name:null,
            'description'                 => count($description)>0?$description:null,
            'active'                      => Tools::getValue('active')?Tools::getValue('active'):0,
            'display_frontend'            => Tools::getValue('display_frontend')?Tools::getValue('display_frontend'):0,
            'display_label_frontend'      => Tools::getValue('display_label_frontend')?Tools::getValue('display_label_frontend'):0,
            'editable_frontend'           => Tools::getValue('editable_frontend')?Tools::getValue('editable_frontend'):0,
            'type_frontend_form'          => Tools::getValue('type')?Tools::getValue('type'):0,
            'type_admin_form'             => Tools::getValue('type')?Tools::getValue('type'):0,
            'required'                    => Tools::getValue('required')?Tools::getValue('required'):0,
            'translatable'                => Tools::getValue('translatable')?Tools::getValue('translatable'):0,
            'available_values'            => count($available_values)>0?$available_values:null,
            'default_value'               => count($default_value)>0?$default_value:null,
            'single_label'                => count($single_label)>0?$single_label:null,
            'allow_empty_select'          => Tools::getValue('allow_empty_select')?Tools::getValue('allow_empty_select'):0,
            'default_status'              => Tools::getValue('default_status')?Tools::getValue('default_status'):0,
            'location'                    => Tools::getValue('location')?Tools::getValue('location'):null,
            'admin_hook'                  => Tools::getValue('admin_hook')?Tools::getValue('admin_hook'):null,
        );

        if (Tools::getValue('location') == 'product'){
            $settings['admin_product_hook'] = Tools::getValue('admin_product_hook');
        } else {
            $settings['admin_product_hook'] = null;
        }

        return $settings;
    }


    /*
    * get custom field configuration
    */
    public function getCustomField($customFieldID)
    {
        // get custom field
        $customField = new AdvancedCustomFieldsModel($customFieldID);

        // assign values
        $settings = array(
            'customFieldForm'             => true,
            'id_custom_field'             => $customField->id_custom_field,
            'name'                        => $customField->name,
            'technical_name'              => $customField->technical_name,
            'description'                 => $customField->description,
            'active'                      => $customField->active,
            'display_frontend'            => $customField->display_frontend,
            'display_label_frontend'      => $customField->display_label_frontend,
            'editable_frontend'           => $customField->editable_frontend,
            'type'                        => $customField->type,
            'type_frontend_form'          => $customField->type,
            'type_admin_form'             => $customField->type,
            'required'                    => $customField->required,
            'translatable'                => $customField->translatable,
            'available_values'            => $customField->available_values,
            'single_label'                => $customField->single_label,
            'default_value'               => $customField->default_value,
            'allow_empty_select'          => $customField->allow_empty_select,
            'default_status'              => $customField->default_status,
            'location'                    => $customField->location,
        );

        if ($customField->location == 'product'){
            $settings['admin_product_hook'] = $customField->admin_hook;
        } else {
            $settings['admin_product_hook'] = null;
        }

        // return
        return $settings;
    }


    /*
    * availableCustomFieldTypesFrontend
    */
    public function availableCustomFieldTypesFrontend()
    {
        $options = array (
                          array (
                                  'id' => 'text',
                                  'name' => 'Text'
                                ),
                          array (
                                  'id' => 'checkbox',
                                  'name' => 'Checkbox'
                                ),
                          array (
                                  'id' => 'radio',
                                  'name' => 'Radio'
                                ),
                          array (
                                  'id' => 'date',
                                  'name' => 'Date'
                                ),
                          array (
                                  'id' => 'select',
                                  'name' => 'Select'
                                ),
                          );
        return $options;

    }

    /*
    * availableCustomFieldTypesAdmin
    */
    public function availableCustomFieldTypesAdmin()
    {
        $options = array (
                          array (
                                  'id' => 'text',
                                  'name' => 'Text'
                                ),
                          array (
                                  'id' => 'textarea',
                                  'name' => 'Textarea'
                                ),
                          array (
                                  'id' => 'editor',
                                  'name' => 'Editor'
                                ),
                          array (
                                  'id' => 'switch',
                                  'name' => 'Switch'
                                ),
                          array (
                                  'id' => 'checkbox',
                                  'name' => 'Checkbox'
                                ),
                          array (
                                  'id' => 'checklist',
                                  'name' => 'Checklist'
                                ),
                          array (
                                  'id' => 'radio',
                                  'name' => 'Radio'
                                ),
                          array (
                                  'id' => 'date',
                                  'name' => 'Date'
                                ),
                          array (
                                  'id' => 'select',
                                  'name' => 'Select'
                                ),
                          );
        return $options;

    }

    /*
    * availableCustomFieldLocations
    */
    public function availableCustomFieldLocations()
    {
        $options = array (
                          array (
                                  'id' => 'product',
                                  'name' => 'Product'
                                ),
                          array (
                                  'id' => 'customer',
                                  'name' => 'Customer'
                                ),
                          array (
                                  'id' => 'category',
                                  'name' => 'Category'
                                ),
                          );
        return $options;

    }


    /*
    * availableAdminProductHooks
    */
    public function availableAdminProductHooks()
    {
        $hooks = array (
                          array (
                                  'id'   => 'displayAdminProductsMainStepLeftColumnMiddle',
                                  'value' => 'displayAdminProductsMainStepLeftColumnMiddle',
                                  'label' => 'Basic settings left column middle'
                                ),
                          array (
                                  'id'   => 'displayAdminProductsMainStepLeftColumnBottom',
                                  'value' => 'displayAdminProductsMainStepLeftColumnBottom',
                                  'label' => 'Basic settings left column bottom'
                                ),
                          array (
                                  'id'   => 'displayAdminProductsMainStepRightColumnBottom',
                                  'value' => 'displayAdminProductsMainStepRightColumnBottom',
                                  'label' => 'Basic settings right column bottom'
                                ),
                          array (
                                  'id'   => 'displayAdminProductsQuantitiesStepBottom',
                                  'value' => 'displayAdminProductsQuantitiesStepBottom',
                                  'label' => 'Quantities Bottom'
                                ),
                          array (
                                  'id'   => 'displayAdminProductsPriceStepBottom',
                                  'value' => 'displayAdminProductsPriceStepBottom',
                                  'label' => 'Pricing bottom'
                                ),
                          array (
                                  'id'   => 'displayAdminProductsOptionsStepTop',
                                  'value' => 'displayAdminProductsOptionsStepTop',
                                  'label' => 'Options Top'
                                ),
                          array (
                                  'id'   => 'displayAdminProductsOptionsStepBottom',
                                  'value' => 'displayAdminProductsOptionsStepBottom',
                                  'label' => 'Options Bottom'
                                ),
                          array (
                                  'id'   => 'displayAdminProductsSeoStepBottom',
                                  'value' => 'displayAdminProductsSeoStepBottom',
                                  'label' => 'Seo Bottom'
                                ),
                          );
        return $hooks;
    }


    /*
    * getShopsListIds
    */
    private function getShopsListIds()
    {
        $shops_list = array();
        $shops = Shop::getShops();
        foreach ($shops as $shop) {
            $shops_list[] = $shop['id_shop'];
        }

        // return shop ids
        return $shops_list;
    }


    /*
    * getHeader in admin
    */
    public function getHeader()
    {
        // get controller
        $controller = Tools::getValue('moduleController');
        // check if dashboard
        if ($controller==''){
            $dashboard = true;
        } else {
            $dashboard = false;
        }
        // show current store only if multistore is enabled
        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            $displayCurrentStore = true;
        } else {
            $displayCurrentStore = false;
        }

        // assign to smarty
        $this->smarty->assign(array(
                                    'shop'                => $this->context->shop->id,
                                    'storeName'           => $this->context->shop->name ,
                                    'logoSrc'             => _PS_BASE_URL_.$this->_path.'/views/img/logo-white.png',
                                    'moduleImageDir'      => _PS_BASE_URL_.$this->_path.'/views/img/',
                                    'psVersion'           => $this->psVersion(),
                                    'displayCurrentStore' => $displayCurrentStore,
                                    'dashboard'           => $dashboard,
                                    'uri'                 => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.''
                                    ));
        // return
        return $this->display(__FILE__, '/views/templates/admin/header.tpl');
    }




    /*
    * getFooter in Admin
    */
    public function getFooter()
    {
        $this->smarty->assign(array(
                                    'moduleVersion'       => $this->version ,
                                    ));
        return $this->display(__FILE__, '/views/templates/admin/footer.tpl');
    }


    /*
    * getLanguagesList
    */
    public function getLanguagesList()
    {
        $languages_list = array();
        $langs = Language::getLanguages(true, $this->context->shop->id);

        foreach ($langs as $lang) {
            if ($lang['id_lang'] == $this->context->language->id) {
                $isDefault = 1;
            } else {
                $isDefault = 0;
            }

            $languages_list[] = array(
                                        'id_lang' => $lang['id_lang'],
                                        'name' => $lang['name'],
                                        'is_default' => $isDefault,
                                        'iso_code' => $lang['iso_code'],
                                    );
        }

        return $languages_list;
    }





    /*
    * getLanguagesList
    */
    public function LocationFieldsList($location)
    {

        // set title according to location
        if ($location == 'customer'){
            $title = $this->l('Customer Custom Fields');
        } else if ($location == 'product'){
            $title = $this->l('Product Custom Fields');
        } else if ($location == 'category'){
            $title = $this->l('Category Custom Fields');
        }

        // get custom fields of location
        $results = AdvancedCustomFieldsModel::getLocationCustomFields($location, $this->context->language->id);

        $this->fields_list = array(
            'id_custom_field' => array(
                'title' => $this->l('ID'),
                'width' => 140,
                'type' => 'text'
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 140,
                'type' => 'text'
            ),
            'technical_name' => array(
                'title' => $this->l('Technical name'),
                'width' => 140,
                'type' => 'text'
            ),
            'type' => array(
                'title' => $this->l('Type'),
                'width' => 'auto',
                'type' => 'text'
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'width' => 'auto',
                'align' => 'center',
                'type' => 'int',
                'callback' => 'booleanToIcon',
                'callback_object' => $this
            ),
            'translatable' => array(
                'title' => $this->l('Translatable'),
                'width' => 'auto',
                'align' => 'center',
                'type' => 'int',
                'callback' => 'booleanToIcon',
                'callback_object' => $this
            ),
            'display_frontend' =>  array(
              'title' => $this->l('Display at frontend'),
              'width' => 'auto',
              'align' => 'center',
              'type' => 'int',
              'callback' => 'booleanToIcon',
              'callback_object' => $this
              )
        );

        if ($location == 'customer'){
            $this->fields_list['editable_frontend'] =  array(
                                                            'title' => $this->l('Editable at frontend'),
                                                            'width' => 'auto',
                                                            'align' => 'center',
                                                            'type' => 'int',
                                                            'callback' => 'booleanToIcon',
                                                            'callback_object' => $this
                                                            );

        }

        $helper= new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->listTotal = count($results);
        $helper->actions = array('edit', 'delete');
        $helper->module = $this;
        $helper->identifier = 'id_custom_field';
        $helper->show_toolbar = false;
        $helper->title = $title;
        $helper->table = '&moduleController=customFieldForm';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&location='.Tools::getValue('location');
        return $helper->generateList($results,$this->fields_list);
    }


    /*
    * booleanToIcon for ΗelperList
    */
    public static function booleanToIcon($value){
        // array
        $icons = array(
                        0 => '<i class="icon-remove"></i>',
                        1 => '<i class="icon-check"></i>'
                      );
        // return
        return $icons[$value];
    }

    /*
    * hookNameToHookHtml for ΗelperList
    */
    public static function hookNameToHookHtml($value){
        // array
        $icons = array(
                        0 => '<i class="icon-remove"></i>',
                        1 => '<i class="icon-check"></i>'
                      );
        // return
        return $icons[$value];
    }



    /*
    * hook hookActionAdminCategoriesFormModifier
    * @param type $params
    */
    public function hookActionAdminCategoriesFormModifier($params)
    {
        // add custom fields to the form
        $this->formModifierBackend('category',$params);

        // get errors and set them to native controller
        $this->context->controller->errors = json_decode($this->context->cookie->acf_errors);
        // clear our Cookie
        $this->context->cookie->acf_errors = '';
    }

    /*
    * hook hookDisplayAdminCustomers
    * @param type $params
    */
    public function hookActionAdminCustomersFormModifier($params)
    {
        // add custom fields to the form
        $this->formModifierBackend('customer',$params);

        // get errors and set them to native controller
        $this->context->controller->errors = json_decode($this->context->cookie->acf_errors);
        // clear our Cookie
        $this->context->cookie->acf_errors = '';
    }


    /*
    * formModifierBackend
    */
    public function formModifierBackend($location,$params)
    {

        // get custom fields of location
        $customFields = AdvancedCustomFieldsModel::getLocationCustomFields($location, $this->context->language->id);
        // foreach custom field
        foreach ($customFields as $customField){

            // if field is active
            if ($customField['active']){
                // get HelperFromField
                $params['fields'][0]['form']['input'][] = $this->getHelperFormField($customField);

                if ($params['object'] AND $params['object']->id AND $params['object']->id!=0){
                    // check if a stored value exists
                    $contentID = AdvancedCustomFieldsContentModel::getContentID($this->context->shop->id, $customField['id_custom_field'], $params['object']->id);
                    // get stored value
                    $content = new AdvancedCustomFieldsContentModel($contentID);
                } else {
                    $contentID = false;
                }


                // checklist field
                if($customField['type']=='checklist'){
                    if ($customField['available_values']!=''){
                        // if content exists
                        if ($contentID){
                            // explode selected values
                            $selectedValues = explode(',',$content->value);
                            // foreach selected value
                            foreach ($selectedValues as $selectedValue) {
                                $params['fields_value'][$customField['technical_name'].'_'.$selectedValue] = 'on';
                            }
                        } else {
                            // explode default values
                            $defaultValues = explode(',',$customField['default_value']);
                            // foreach selected value
                            foreach ($defaultValues as $defaultValue) {
                                $params['fields_value'][$customField['technical_name'].'_'.$defaultValue] = 'on';
                            }
                        }
                    }

                // checkbox field
                } else if($customField['type']=='checkbox'){
                    // if content exists
                    if ($contentID){
                        // if it is checked
                        if ($content->value=='1'){
                            $params['fields_value'][$customField['technical_name'].'_on'] = 'on';
                        }
                    // else set the default one
                    } else {
                        if ($customField['default_status']=='1'){
                            $params['fields_value'][$customField['technical_name'].'_on'] = 'on';
                        }
                    }

                // switch field
                } else if($customField['type']=='switch'){
                    // if there is a value
                    if ($contentID){
                        $params['fields_value'][$customField['technical_name']] = $content->value;
                    // else set the default one
                    } else {
                        $params['fields_value'][$customField['technical_name']] = $customField['default_status'];
                    }

                // all other fields
                } else {
                    if ($customField['translatable']){
                        // if a value exists, then assign it
                        if ($contentID){
                            // assign multilang value
                            $params['fields_value'][$customField['technical_name']] = $content->lang_value;
                        } else {
                            // set to nothing for all langs
                            // get languages
                            $langs = $this->getLanguagesList();
                            // init an empty array for each multilanguage attribute
                            $emptyValue = array();
                            // foreach shop language populate array
                            foreach ($langs as $lang) {
                                $emptyValue[$lang['id_lang']] = '';
                            }
                            // assign
                            $params['fields_value'][$customField['technical_name']] = $emptyValue;
                        }
                    } else {
                        // if a value exists, then assign simple value
                        if ($contentID){
                            // assign the value
                            $params['fields_value'][$customField['technical_name']] = $content->value;
                        } else {
                            // else assign the default one
                            $params['fields_value'][$customField['technical_name']] = $customField['default_value'];
                        }
                    }
                }
            }
        }
    }



    /*
    * function getHelperFormField
    *
    */
    public function getHelperFormField($customField){

        // if available values exist, prepare an array out of the database string
        if ($customField['available_values']!=''){
            // initialize the final array
            $availableValues = array();
            // initial format:  label:value,label:value,label:value
            $availableValuesListUnformated = explode(',',$customField['available_values']);
            // if an empty row is allowed at the beginning, add it into available values - ( for select fields only )
            if ($customField['type']=='select' && $customField['allow_empty_select']){
                // add the empty first row
                $availableValues[] = array('id'=>'','label'=>'...','value'=>'');
            }
            // each row is formated as label:value
            foreach ($availableValuesListUnformated as $key => $val) {
                $availableValuesUnformated = explode(':',$val);
                // add to available values array
                $availableValues[] = array(
                                            'id' => $availableValuesUnformated[1],  // used for radio
                                            'label' => $availableValuesUnformated[0], // used for radio, select, checklist
                                            'value' => $availableValuesUnformated[1] // used for radio, select, checklist
                                          );
            }
        }

        // if field is active
        if ($customField['active']){

            // text field
            if ($customField['type']=='text'){
                // if translatable
                if ($customField['translatable']){ $lang = true; } else { $lang = false; }
                // add field
                return [
                        'type' => 'text',
                        'label' => $customField['name'],
                        'name' => $customField['technical_name'],
                        'required' => $customField['required'],
                        'lang'     => $lang,
                        'desc'     => $customField['description'],
                       ];

            // textarea field
            } else if ($customField['type']=='textarea'){
                // if translatable
                if ($customField['translatable']){ $lang = true; } else { $lang = false; }
                // add field
                return [
                        'type' => 'textarea',
                        'label' => $customField['name'],
                        'name' => $customField['technical_name'],
                        'required' => $customField['required'],
                        'lang'     => $lang,
                        'rows'     => 10,
                        'cols'     => 100,
                        'desc'     => $customField['description'],
                       ];


            // textarea field with editor
            } else if ($customField['type']=='editor'){
                // if translatable
                if ($customField['translatable']){ $lang = true; } else { $lang = false; }
                // add field
                return [
                        'type' => 'textarea',
                        'label' => $customField['name'],
                        'name' => $customField['technical_name'],
                        'required' => $customField['required'],
                        'lang'     => $lang,
                        'rows'     => 10,
                        'cols'     => 100,
                        'autoload_rte' => true,
                        'desc'     => $customField['description'],
                       ];

            // switch field
            } else if ($customField['type']=='switch'){
                // add field
                return [
                        'type' => 'switch',
                        'label' => $customField['name'],
                        'name' => $customField['technical_name'],
                        'desc'     => $customField['description'],
                        'required' => $customField['required'],
                        'is_bool'   => true,
                        'values'    => array(
                                            array(
                                                'id' => 'disable_on',
                                                'value' => 1,
                                                'label' => $this->l('Yes')
                                            ),
                                            array(
                                                'id' => 'disable_off',
                                                'value' => 0,
                                                'label' => $this->l('No')
                                            )
                                          )
                       ];

            // date field
            } else if ($customField['type']=='date'){
                // add field
                return [
                        'type' => 'date',
                        'label' => $customField['name'],
                        'name' => $customField['technical_name'],
                        'desc'     => $customField['description'],
                        'required' => $customField['required'],
                       ];

            // select field
            } else if ($customField['type']=='select'){
                  // add field
                  return [
                          'type' => 'select',
                          'label' => $customField['name'],
                          'name' => $customField['technical_name'],
                          'desc'     => $customField['description'],
                          'required' => $customField['required'],
                          'options' => array(
                              'query' => $availableValues,
                              'id' => 'value',
                              'name' => 'label'
                          )
                         ];

            // radio field
            } else if ($customField['type']=='radio'){
                    // add field
                    return [
                            'type' => 'radio',
                            'label' => $customField['name'],
                            'name' => $customField['technical_name'],
                            'desc'     => $customField['description'],
                            'required' => $customField['required'],
                            'values' => $availableValues
                           ];

            // checklist field
            } else if ($customField['type']=='checkbox'){
                  // add field
                  return [
                          'type' => 'checkbox',
                          'label' => $customField['name'],
                          'name' => $customField['technical_name'],
                          'desc'     => $customField['description'],
                          'required' => $customField['required'],
                          'values' => array(
                                            'query' => array(array('value'=>'on','label'=>$customField['single_label'])),
                                            'id' => 'value',
                                            'name' => 'label'
                                           )
                         ];

            // checklist field
            } else if ($customField['type']=='checklist'){
                  // add field
                  return [
                          'type' => 'checkbox',
                          'label' => $customField['name'],
                          'name' => $customField['technical_name'],
                          'desc'     => $customField['description'],
                          'required' => $customField['required'],
                          'multiple' => true,
                          'values' => array(
                                            'query' => $availableValues,
                                            'id' => 'value',
                                            'name' => 'label'
                                           )
                         ];
            } else {
                return;
            }
        } else {
            return ;
        }

    }






    /*
    * hook hookDisplayAdminCustomers
    * @param type $params
    * @return type
    */
    public function hookDisplayAdminCustomers($params)
    {
        // init the final tables
        $editableCustomFields = array();
        $adminOnlyCustomFields = array();
        $publicCustomFields = array();

        // get custom fields of location
        $customFieldsRes = AdvancedCustomFieldsModel::getLocationCustomFields('customer', $this->context->language->id);

        // foreach custom field
        foreach ($customFieldsRes as $customField){

            // if field is active
            if ($customField['active']){

                if ($this->context->customer->id!=0){
                    // check if a stored value exists
                    $contentID = AdvancedCustomFieldsContentModel::getContentID($this->context->shop->id, $customField['id_custom_field'], $this->context->customer->id);
                    // get stored value
                    $content = new AdvancedCustomFieldsContentModel($contentID);
                } else {
                    $contentID = false;
                }
                if ($contentID){
                    // get displayable value
                    $customField['value'] = $this->getDisplayableValue($customField,$content);
                    // add to appropriate tempalte array
                    if ($customField['editable_frontend']){
                      // add
                      $editableCustomFields[] = $customField;
                    } else if ($customField['display_frontend']){
                      // add
                      $publicCustomFields[] = $customField;
                    } else {
                      // add
                      $adminOnlyCustomFields[] = $customField;
                    }
                }
            }
        }

        // assign to smarty
        $this->smarty->assign(array(
                                    'publicCustomFields'                => $publicCustomFields,
                                    'adminOnlyCustomFields'             => $adminOnlyCustomFields,
                                    'editableCustomFields'              => $editableCustomFields,
                                  ));

        // return template
        return $this->display(__FILE__, '/views/templates/admin/customer.tpl');
    }



    /*
    * getDisplayableValue
    */
    public function getDisplayableValue($customField,$content)
    {

        // if available values exist
        if ($customField['available_values']!=''){
            // prepare available values in order to get Labels
            $availableValuesListUnformated = explode(',',$customField['available_values']);
            // each row is formated as label:value
            $availableValues = array();
            foreach ($availableValuesListUnformated as $key => $val) {
                $availableValuesUnformated = explode(':',$val);
                // add to available values array
                $availableValues[$availableValuesUnformated[1]] = $availableValuesUnformated[0];
            }
        }

        // checklist field
        if($customField['type']=='checklist' ){
            if ($customField['available_values']!=''){
                // if content exists
                if ($content){
                    // if not null
                    if ($content->value){
                        // explode selected values
                        $selectedValues = explode(',',$content->value);
                        // foreach selected value
                        $temp = array();
                        foreach ($selectedValues as $selectedValue) {
                            $temp[] = $availableValues[$selectedValue];
                        }
                        $value = join(', ',$temp);
                    } else {
                        $value='';
                    }
                }
            }

        // radio or select field
        } else if($customField['type']=='radio' OR $customField['type']=='select'){
            if ($customField['available_values']!=''){
                // if content exists
                if ($content){
                    // if not null
                    if ($content->value!='...'){
                        $value = $availableValues[$content->value];
                    } else {
                        $value='';
                    }
                }
            }

        // checkbox or switch field
        } else if($customField['type']=='checkbox' OR $customField['type']=='switch'){
            if ($content->value){
                $value = 'acf-checked';
            } else {
                $value = 'acf-not-checked';
            }

        // all other fields
        } else {
            if ($customField['translatable']){
                // if a value exists, then assign it
                if ($content){
                    // assign multilang value
                    $value = $content->lang_value[$this->context->language->id];
                }
            } else {
                // if a value exists, then assign simple value
                if ($content){
                    // assign the value
                    $value = $content->value;
                }
            }
        }
        // return
        return $value;
    }








    /**
    * Hook hookDisplayAdminProductsMainStepLeftColumnMiddle
    * @param type $params
    */
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params){
        return $this->hookAdminProduct($params,'displayAdminProductsMainStepLeftColumnMiddle');
    }


    /**
    * Hook hookDisplayAdminProductsMainStepLeftColumnBottom
    * @param type $params
    */
    public function hookDisplayAdminProductsMainStepLeftColumnBottom($params){
        return $this->hookAdminProduct($params,'displayAdminProductsMainStepLeftColumnBottom');
    }

    /**
    * Hook hookDisplayAdminProductsMainStepRightColumnBottom
    * @param type $params
    */
    public function hookDisplayAdminProductsMainStepRightColumnBottom($params){
        return $this->hookAdminProduct($params,'displayAdminProductsMainStepRightColumnBottom');
    }

    /**
    * Hook hookDisplayAdminProductsQuantitiesStepBottom
    * @param type $params
    */
    public function hookDisplayAdminProductsQuantitiesStepBottom($params){
        return $this->hookAdminProduct($params,'displayAdminProductsQuantitiesStepBottom');
    }

    /**
    * Hook hookDisplayAdminProductsPriceStepBottom
    * @param type $params
    */
    public function hookDisplayAdminProductsPriceStepBottom($params){
        return $this->hookAdminProduct($params,'displayAdminProductsPriceStepBottom');
    }

    /**
    * Hook hookDisplayAdminProductsOptionsStepTop
    * @param type $params
    */
    public function hookDisplayAdminProductsOptionsStepTop($params){
        return $this->hookAdminProduct($params,'displayAdminProductsOptionsStepTop');
    }

    /**
    * Hook displayAdminProductsOptionsStepBottom
    * @param type $params
    */
    public function hookDisplayAdminProductsOptionsStepBottom($params){
        return $this->hookAdminProduct($params,'displayAdminProductsOptionsStepBottom');
    }

    /**
    * Hook hookDisplayAdminProductsSeoStepBottom
    * @param type $params
    */
    public function hookDisplayAdminProductsSeoStepBottom($params){
        return $this->hookAdminProduct($params,'displayAdminProductsSeoStepBottom');
    }


    /**
    * hookAdminProduct (well, it is not a native hook - all admin product hooks trigger it)
    * @param type $params
    */
    public function hookAdminProduct($params, $hook) {

        // init an empty array
        $customFields = array();
        // get custom fields of location
        $customFieldsDB = AdvancedCustomFieldsModel::getLocationCustomFields('product', $this->context->language->id, $hook);

        // convert available_values string to array
        foreach ($customFieldsDB as $customField){
            // if available values exist, prepare an array out of the database string
            if ($customField['available_values']!=''){
                // initialize the final array
                $availableValues = array();
                // initial format:  label:value,label:value,label:value
                $availableValuesListUnformated = explode(',',$customField['available_values']);
                // each row is formated as label:value
                foreach ($availableValuesListUnformated as $key => $val) {
                    $availableValuesUnformated = explode(':',$val);
                    // add to available values array
                    $availableValues[] = array(
                                                'id' => $availableValuesUnformated[1],  // used for radio
                                                'label' => $availableValuesUnformated[0], // used for radio, select, checklist
                                                'value' => $availableValuesUnformated[1] // used for radio, select, checklist
                                              );
                }
                $customField['available_values'] = $availableValues;
            }


            if ($params['id_product'] AND $params['id_product']!=0){
                // check if a stored value exists
                $contentID = AdvancedCustomFieldsContentModel::getContentID($this->context->shop->id, $customField['id_custom_field'], $params['id_product']);
                // get stored value
                $content = new AdvancedCustomFieldsContentModel($contentID);
            } else {
                $contentID = false;
            }

            // checklist field
            if($customField['type']=='checklist'){
                if ($customField['available_values']!=''){
                    $temp = array();
                    // if content exists
                    if ($contentID){
                        // explode selected values
                        $selectedValues = explode(',',$content->value);
                        // foreach selected value
                        foreach ($selectedValues as $selectedValue) {
                            $temp[] = array(
                                            'value' => $selectedValue,
                                            'checked' => 'on'
                                           );
                        }
                    } else {
                        // explode default values
                        $defaultValues = explode(',',$customField['default_value']);
                        // foreach selected value
                        foreach ($defaultValues as $defaultValue) {
                            $temp[] = array(
                                            'value' => $defaultValue,
                                            'checked' => 'on'
                                           );
                        }
                    }
                    $customField['value'] = $temp;
                }

            // checkbox field
            } else if($customField['type']=='checkbox'){
                // if content exists
                if ($contentID){
                    // if it is checked
                    if ($content->value=='1'){
                        $customField['value'] = 'on';
                    }
                // else set the default one
                } else {
                    if ($customField['default_status']=='1'){
                        $customField['value'] = 'on';
                    }
                }

            // switch field
            } else if($customField['type']=='switch'){
                // if there is a value
                if ($contentID){
                    $customField['value'] = $content->value;
                // else set the default one
                } else {
                    $customField['value'] = $customField['default_status'];
                }

            // all other fields
            } else {
                if ($customField['translatable']){
                    // assign multilang value
                    $customField['value'] = $content->lang_value;
                } else {
                    // if a value exists, then assign simple value
                    if ($contentID){
                        // assign the value
                        $customField['value'] = $content->value;
                    } else {
                        // assign the value
                        $customField['value'] = $customField['default_value'];
                    }
                }
            }

            $customFields[] = $customField;




        }

        // assign to smarty
        $this->smarty->assign(array(
                                    'customFields' => $customFields,
                                    'languages' => $this->getLanguagesList(),
                                    'default_language' => $this->context->language->id,
                                  ));

        // return template
        return $this->display(__FILE__, '/views/templates/hook/backoffice.tpl');
    }





    /**
    * Hook actionAdminCustomersControllerSaveAfter
    * @param type $params
    * @return type
    */
    public function hookActionAdminCustomersControllerSaveAfter($params)
    {
        // save custom fields of location
        $this->saveLocationCustomFields('customer', Tools::getValue('id_customer'),$params);
    }

    /**
    * Hook hookActionAdminCategoriesControllerSaveAfter
    * @param type $params
    * @return type
    */
    public function hookActionAdminCategoriesControllerSaveAfter($params)
    {
        // save custom fields of location
        $this->saveLocationCustomFields('category', Tools::getValue('id_category'),$params);
    }

    /**
    * Hook hookActionProductSave
    * @param type $params
    * @return type
    */
    public function hookActionProductSave($params)
    {
        // check if update was triggered from product page form (and is not an activate/deactivate call from the admin listing page)
        if (Tools::getValue('form')){
            // save custom fields of location
            $this->saveLocationCustomFields('product', $params['id_product'],$params);
        }
    }



    /*
    * saveLocationCustomFields
    */
    public function saveLocationCustomFields($location, $resource_id,$params)
    {

        // get languages
        $langs = $this->getLanguagesList();

        // get custom fields of location
        $customFields = AdvancedCustomFieldsModel::getLocationCustomFields($location, $this->context->language->id);

        // initialize an error array
        $acfErrors = array();
        // foreach custom field
        foreach ($customFields as $customField) {

            // if field active
            if($customField['active']==1){
                // checklist field
                if($customField['type']=='checklist'){

                    if ($customField['available_values']!=''){
                        // initialize the final array and final string
                        $value = '';
                        $selectedValues = array();
                        // initial format:  label:value,label:value,label:value
                        $availableValuesListUnformated = explode(',',$customField['available_values']);
                        // each row is formated as label:value
                        foreach ($availableValuesListUnformated as $key => $val) {
                            $availableValuesUnformated = explode(':',$val);
                            // if value is checked
                            if (Tools::getValue($customField['technical_name'].'_'.$availableValuesUnformated[1])=='on'){
                                // keep selection
                                $selectedValues[] = $availableValuesUnformated[1];
                            }
                        }
                        $value = join(',',$selectedValues);
                    }

                // checkbox field
                } else if ($customField['type']=='checkbox'){
                    // initialize the final string
                    $value = '';
                    // if value is checked
                    if (Tools::getValue($customField['technical_name'].'_on')=='on'){
                        // keep selection
                        $value = 1;
                    } else {
                        // set nothing
                        $value = 0;
                    }

                // all other fields
                } else {

                    // if field is translatable
                    if ($customField['translatable']==1){
                        // init an empty array and an empty string
                        $value = '';
                        $data = array();
                        // foreach shop language populate array
                        foreach ($langs as $lang) {
                            $data[$lang['id_lang']] = Tools::getValue($customField['technical_name'].'_'.$lang['id_lang']);
                        }
                        // assign multilanguage data
                        $value = $data;
                    } else {
                        $value = '';
                        // assing simple data
                        $value = Tools::getValue($customField['technical_name']);
                    }
                }


                $fieldErrors = array();
                // if field is required, make the check
                if ($customField['required']){
                    // if field is translatable
                    if ($customField['translatable']==1){
                        foreach ($value as $key => $val) {
                            if ($val==''){
                                // assign error
                                $acfErrors[] = $this->l('Field').' '.$customField['name'].' '.$this->l('is required in all available languages!');
                                $fieldErrors[] = $this->l('Field').' '.$customField['name'].' '.$this->l('is required in all available languages!');
                            }
                        }
                    // simple field
                    } else {
                        // if no value
                        if($value==''){
                          // assign error
                          $acfErrors[] = $this->l('Field').' '.$customField['name'].' '.$this->l('is required!');
                          $fieldErrors[] = $this->l('Field').' '.$customField['name'].' '.$this->l('is required!');
                        }
                    }

                }

                if (count($fieldErrors)>0){
                    // do nothing ...
                } else {
                    // clear previous data from database
                    AdvancedCustomFieldsContentModel::clearContent($this->context->shop->id, $resource_id, $customField['id_custom_field']);

                    // save data
                    $customFieldContent = new AdvancedCustomFieldsContentModel();
                    // assign data
                    $customFieldContent->id_store = $this->context->shop->id;
                    $customFieldContent->id_custom_field = $customField['id_custom_field'];
                    $customFieldContent->resource_id = $resource_id;
                    // if value is multi lang
                    if (is_array($value)){
                        $customFieldContent->lang_value = $value;
                    } else {
                        $customFieldContent->value = $value;
                    }
                    // save
                    $customFieldContent->save();

                }

            }
        }
        // if errors found
        if (count($acfErrors)>0){

            // merge acf and native errors
            if (count($this->context->controller->errors)>0){
                $allErrors = array_merge($this->context->controller->errors,$acfErrors);
            } else {
                $allErrors = $acfErrors;
            }
            // set an errors cookie
            $this->context->cookie->acf_errors = json_encode($allErrors);
            // set redirect location
            if ($location == 'category'){$redirectLink = 'AdminCategories';}
            if ($location == 'customer'){$redirectLink = 'AdminCustomers';}
            // redirect according to location
            if ($location == 'customer'){
              // redirect to self
              Tools::redirectAdmin($this->context->link->getAdminLink('AdminCustomers').'&id_customer='.(int)$resource_id.'&updatecustomer');
            } else if ($location == 'category'){
              // redirect to self
              Tools::redirectAdmin($this->context->link->getAdminLink('AdminCategories').'&id_category='.(int)$resource_id.'&updatecategory');
            } else if ($location == 'product'){
               echo 'here';
            }
            die;
        }
    }



    /**
    * Hook hookΑctionProductAdd (triggered when a product is duplicated)
    * @param type $params
    */
    public function hookActionProductAdd($params){

        $clonedProduct = $params['product'];
        // if a product id is available_date
        if ($params['id_product_old']!=''){
            // get custom fields of existing products
            $oldProductCustomFields = AdvancedCustomFieldsContentModel::getProductCustomFieldsContent($this->context->shop->id,$params['id_product_old']);
            // foreach custom field
            foreach ($oldProductCustomFields as $customField){
                // load initial custom field
                $initialCustomFieldContent = new AdvancedCustomFieldsContentModel($customField['id_custom_field_content']);
                // duplicate custom field
                $clonedCustomFieldContent = new AdvancedCustomFieldsContentModel();
                // assign data
                $clonedCustomFieldContent->id_store = $initialCustomFieldContent->id_store;
                $clonedCustomFieldContent->id_custom_field = $initialCustomFieldContent->id_custom_field;
                $clonedCustomFieldContent->resource_id = $clonedProduct->id;
                $clonedCustomFieldContent->value = $initialCustomFieldContent->value;
                $clonedCustomFieldContent->lang_value = $initialCustomFieldContent->lang_value;
                // save
                $clonedCustomFieldContent->save();
            }
        }
        return ;
    }


        /**
         * Ajout d'un champ client supplémentaire en FO
         * @param type $params
         */
        public function hookAdditionalCustomerFormFields() {
            // return formModifierFrontend()
            return $this->formModifierFrontend('customer');
        }



        /*
        * formModifierFrontend
        */
        public function formModifierFrontend($location)
        {
            // init an empty array
            $fields = array();

            // get custom fields of location
            $customFields = AdvancedCustomFieldsModel::getLocationCustomFields($location, $this->context->language->id);
            // foreach custom field
            foreach ($customFields as $customField){

                // if field is active
                if ($customField['active'] && $customField['editable_frontend']){

                    if ($this->context->customer->id){
                        // check if a stored value exists
                        $contentID = AdvancedCustomFieldsContentModel::getContentID($this->context->shop->id, $customField['id_custom_field'], $this->context->customer->id);
                        // get stored value
                        $content = new AdvancedCustomFieldsContentModel($contentID);
                    } else {
                        $contentID = false;
                    }

                    // init the value
                    $value = '';

                    // checkbox field
                    if($customField['type']=='checkbox'){
                        // if content exists
                        if ($contentID){
                            // if it is checked
                            if ($content->value=='1'){
                                $value = '1';
                            }
                        // else set the default one
                        } else {
                            if ($customField['default_status']=='1'){
                                $value = '1';
                            }
                        }

                    // other fields
                    } else {
                        // if a value exists, then assign simple value
                        if ($contentID){
                            // assign the value
                            $value = $content->value;
                        } else {
                            // else assign the default one
                            $value = $customField['default_value'];
                        }
                    }

                    // get HelperFromField
                    $field = $this->getFrontendFormField($customField,$value);
                    // add to response
                    if ($field){
                        $fields[] = $field;
                    }

                }
            }
            // return
            return $fields;
        }






        /*
        * front office includes
        * info: this hook is only used to tweak the prestashop core bug that does no check, checked checkboxes
        *       if we are in registration or identity page, we should look up for checked customer checkboxes
        *       and add the js that will check them
        */
        public function hookDisplayHeader($params)
        {
            if($this->context->controller->php_self=='identity' OR ($this->context->controller->php_self=='authentication' AND Tools::getValue('create_account'))){
                // init an empty array
                $checkedCheckboxes = array();
                // get custom fields of location
                $customFields = AdvancedCustomFieldsModel::getLocationCustomFields('customer', $this->context->language->id);
                // foreach custom field
                foreach ($customFields as $customField){
                    // if field is active
                    if ($customField['active'] && $customField['editable_frontend']){

                        if ($this->context->customer->id){
                            // check if a stored value exists
                            $contentID = AdvancedCustomFieldsContentModel::getContentID($this->context->shop->id, $customField['id_custom_field'], $this->context->customer->id);
                            // get stored value
                            $content = new AdvancedCustomFieldsContentModel($contentID);
                        } else {
                            $contentID = false;
                        }
                        // checkbox field
                        if($customField['type']=='checkbox'){
                            // if content exists
                            if ($this->context->controller->php_self=='identity' AND $contentID){
                                // if it is checked
                                if ($content->value=='1'){
                                    $checkedCheckboxes[] = '"'.$customField['technical_name'].'"';
                                }
                            }
                            // if in registration with a default value
                            if ($this->context->controller->php_self=='authentication'  AND $customField['default_status']=='1'){
                                $checkedCheckboxes[] = '"'.$customField['technical_name'].'"';
                            }
                        }
                    }
                }

                // if checked checkboxes are found, add the tweak js
                if (count($checkedCheckboxes)>0){
                    $this->context->controller->addJS($this->_path.'/views/js/advancedcustomfields.js');
                }
                // return the js array var
                return '<script type="text/javascript">var acfFields = ['.join(',',$checkedCheckboxes).'];</script>';
            }
        }



        /*
        * getFrontendFormField
        */
        public function getFrontendFormField($customField,$value)
        {
            // initialize the final array
            $availableValues = array();
            // if an empty row is allowed at the beginning, add it into available values - ( for select fields only )
            if ($customField['type']=='select' && $customField['allow_empty_select']){
                // add the empty first row
                $availableValues[''] = '...';
            }
            // if available values exist, prepare an array out of the database string
            if ($customField['available_values']!=''){
                // initial format:  label:value,label:value,label:value
                $availableValuesListUnformated = explode(',',$customField['available_values']);
                // each row is formated as label:value
                foreach ($availableValuesListUnformated as $key => $val) {
                    $availableValuesUnformated = explode(':',$val);
                    // add to available values array
                    $availableValues[$availableValuesUnformated[1]] = $availableValuesUnformated[0];
                }
            }


            // if field is active
            if ($customField['active']){
                // if field is editable in frontend
                if ($customField['editable_frontend']){

                    // text field
                    if ($customField['type']=='text'){
                        // return field
                        return (new FormField)
                                ->setName($customField['technical_name'])
                                ->setType('text')
                                ->setRequired($customField['required']?true:false)
                                ->setLabel($customField['name'])
                                ->addAvailableValue('comment',$customField['description'])
                                ->setValue($value);

                    // date field
                    } else if ($customField['type']=='date'){
                        // return field
                        return (new FormField)
                                  ->setName($customField['technical_name'])
                                  ->setType('text')
                                  ->setLabel($customField['name'])
                                  ->setRequired($customField['required']?true:false)
                                  ->addAvailableValue('placeholder', Tools::getDateFormat())
                                  ->addAvailableValue(
                                      'comment',
                                      $this->l('Example:').' '.Tools::formatDateStr('31 May 1970')
                                  )
                                  ->setValue($value)
                              ;

                    // select field
                    } else if ($customField['type']=='select'){
                        // return field
                        return (new FormField)
                                ->setName($customField['technical_name'])
                                ->setType('select')
                                ->setRequired($customField['required']?true:false)
                                ->setAvailableValues($availableValues)
                                ->setLabel($customField['name'])
                                ->setValue($value);

                    // radio field
                    } else if ($customField['type']=='radio'){
                        // return field
                        return (new FormField)
                                ->setName($customField['technical_name'])
                                ->setType('radio-buttons')
                                ->setRequired($customField['required']?true:false)
                                ->setAvailableValues($availableValues)
                                ->setLabel($customField['name'])
                                ->setValue($value);

                    // checklist field
                    } else if ($customField['type']=='checkbox'){
                         // return field
                         return (new FormField)
                                 ->setName($customField['technical_name'])
                                 ->setType('checkbox')
                                 ->setRequired($customField['required']?true:false)
                                 ->setLabel($customField['name'])
                                 ->setValue($value);

                    } else {
                        return;
                    }
                } else {
                    return;
                }
            } else {
                return ;
            }
        }


        /**
        * Hook hookActionObjectCustomerAddAfter
        * @param type $params
        * @return type
        */
        public function hookActionObjectCustomerAddAfter($params)
        {
            // save custom fields of location
            $this->saveLocationCustomFieldsFrontend('customer', $params['object']->id);
        }

        /**
        * Hook hookActionObjectCustomerUpdateAfter
        * @param type $params
        * @return type
        */
        public function hookActionObjectCustomerUpdateAfter($params)
        {
            // save custom fields of location
            $this->saveLocationCustomFieldsFrontend('customer', $params['object']->id);
        }


        /*
        * saveLocationCustomFieldsFrontend
        */
        public function saveLocationCustomFieldsFrontend($location, $resource_id)
        {

            // get languages
            $langs = $this->getLanguagesList();

            // get custom fields of location
            $customFields = AdvancedCustomFieldsModel::getLocationCustomFields($location, $this->context->language->id);

            // initialize an error array
            $acfErrors = array();
            // foreach custom field
            foreach ($customFields as $customField) {

                // if field active
                if($customField['active']==1){
                    // if field is editable in frontend
                    if ($customField['editable_frontend']){

                        // if checkbox
                        if ($customField['type']=='checkbox'){
                            // initialize the final string
                            $value = '';
                            // if value is checked
                            if (Tools::getValue($customField['technical_name'])=='1'){
                                // keep selection
                                $value = 1;
                            } else {
                                // set nothing
                                $value = 0;
                            }

                        // other fields
                        } else {
                            // assing simple data
                            $value = Tools::getValue($customField['technical_name']);
                        }

                        // clear previous data from database
                        AdvancedCustomFieldsContentModel::clearContent($this->context->shop->id, $resource_id, $customField['id_custom_field']);

                        // save data
                        $customFieldContent = new AdvancedCustomFieldsContentModel();
                        // assign data
                        $customFieldContent->id_store = $this->context->shop->id;
                        $customFieldContent->id_custom_field = $customField['id_custom_field'];
                        $customFieldContent->resource_id = $resource_id;
                        // if value is multi lang
                        if (is_array($value)){
                            $customFieldContent->lang_value = $value;
                        } else {
                            $customFieldContent->value = $value;
                        }
                        // save
                        $customFieldContent->save();

                    }
                }
            }
        }



        /**
        * Hook hookValidateCustomerFormFields
        * @param type $params
        * @return type
        */
        public function hookValidateCustomerFormFields($params)
        {
            foreach ($params['fields'] as $field){

                /** @var FormField $field */
                // validate, if field is a custom field
                if (AdvancedCustomFieldsModel::checkIfTechnicalNameExists($field->getName())){
                    $fieldID = AdvancedCustomFieldsModel::getCustomFieldIdFromTechnicalName($field->getName());
                    $customField = new AdvancedCustomFieldsModel($fieldID);

                    // if field active
                    if($customField->active==1){
                        // if field is editable in frontend
                        if ($customField->editable_frontend){

                            // get value
                            $value = Tools::getValue($customField->technical_name);

                            $fieldErrors = array();
                            // if field is required, make the check
                            if ($customField->required){
                                // if no value
                                if($value==''){
                                  // assign error (it is already triggered by the form, so it is not needed here)
                                  // $field->setErrors(array($this->l('The field is required!')));
                                }
                            }

                            // if field is date, check the format
                            if ($customField->type=='date'){
                              if (!$this->isDate($value) && $value!=''){
                                // assign error
                                $field->setErrors(array($this->l('Format should be 05/31/1970.')));
                              }
                            }

                        }
                    }

                }
            }

            // return modified params
            return $params['fields'];
        }



        /**
        * isDate
        */
        public function isDate($date)
        {
            $matches = array();
            $pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
            if (!preg_match($pattern, $date, $matches)) return false;
            if (!checkdate($matches[1], $matches[2], $matches[3])) return false;
            return true;
        }
    // eof
}
