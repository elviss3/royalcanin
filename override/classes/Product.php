<?php
/**
 * Override Class ProductCore
 */
class Product extends ProductCore {

	public $rich_content_field;
	public $rich_content_field_lang_wysiwyg;

	public $composition_field;
	public $composition_field_lang_wysiwyg;

	public $nutri_values_field;
	public $nutri_values_field_lang_wysiwyg;

	public $nutri_information_field_;
	public $nutri_information_field_lang_wysiwyg;


	public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null){

			self::$definition['fields']['rich_content_field'] = [
	            'type' => self::TYPE_STRING,
	            'required' => false, 'size' => 255
	        ];

	        self::$definition['fields']['rich_content_field_lang_wysiwyg'] = [
				// 'type' => self::TYPE_STRING,
	            // 'lang' => true,
	            'required' => false,

				'type' => self::TYPE_HTML, 
				'lang' => true, 
				'validate' => 'isString'
	        ];

			self::$definition['fields']['composition_field'] = [
	            'type' => self::TYPE_STRING,
	            'required' => false, 'size' => 255
	        ];

	        self::$definition['fields']['composition_field_lang_wysiwyg'] = [
	            'type' => self::TYPE_HTML,
	            'lang' => true,
	            'required' => false,
	            'validate' => 'isCleanHtml'
	        ];

			self::$definition['fields']['nutri_values_field'] = [
	            'type' => self::TYPE_STRING,
	            'required' => false, 'size' => 255
	        ];

	        self::$definition['fields']['nutri_values_field_lang_wysiwyg'] = [
	            'type' => self::TYPE_HTML,
	            'lang' => true,
	            'required' => false,
	            'validate' => 'isCleanHtml'
	        ];

			self::$definition['fields']['nutri_information_field'] = [
	            'type' => self::TYPE_STRING,
	            'required' => false, 'size' => 255
	        ];

	        self::$definition['fields']['nutri_information_field_lang_wysiwyg'] = [
	            'type' => self::TYPE_HTML,
	            'lang' => true,
	            'required' => false,
	            'validate' => 'isCleanHtml'
	        ];


	        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
	}
}