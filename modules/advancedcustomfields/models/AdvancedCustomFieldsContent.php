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

class AdvancedCustomFieldsContentModel extends ObjectModel
{


    /*
    * definition
    */
    public $id_custom_field_content;
    public $id_store;
    public $id_custom_field;
    public $resource_id;
    public $value;
    public $lang_value;


    public static $definition = array(
                                        'table' => 'advanced_custom_fields_content',
                                        'primary' => 'id_custom_field_content',
                                        'multishop' => true,
                                        'multilang' => true,
                                        'fields' => array(
                                                            'id_store' => array(
                                                                            'type' => self::TYPE_INT,
                                                                            'validate' => 'isUnsignedInt',
                                                                            'required' => true
                                                                        ),
                                                            'id_custom_field' => array(
                                                                            'type' => self::TYPE_INT,
                                                                            'validate' => 'isUnsignedInt',
                                                                            'required' => true
                                                                        ),
                                                            'resource_id' => array(
                                                                            'type' => self::TYPE_INT,
                                                                            'validate' => 'isUnsignedInt',
                                                                            'required' => true
                                                                        ),
                                                            'value' => array(
                                                                        'type' => self::TYPE_HTML,
                                                                        ),
                                                            'lang_value' => array(
                                                                        'type' => self::TYPE_HTML,
                                                                        'lang' => true
                                                                        ),



                                                        ),
                                    );


    /*
    * createSchema for current Model
    */
    public static function createSchema()
    {
        return ( AdvancedCustomFieldsContentModel::createAdvancedCustomFieldsContentTable() && AdvancedCustomFieldsContentModel::createAdvancedCustomFieldsContentLanguageTable() );
    }




    /*
    * dropSchema for current Model
    */
    public static function dropSchema()
    {
        $sql = 'DROP TABLE `'._DB_PREFIX_.self::$definition['table'].'`,
                           `'._DB_PREFIX_.self::$definition['table'].'_lang`';
        $result = Db::getInstance()->execute($sql);
        return $result;
    }




    /*
    * createAdvancedCustomFieldsContentTable
    */
    public static function createAdvancedCustomFieldsContentTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'`(
                `id_custom_field_content` int(10) unsigned NOT NULL auto_increment,
                `id_store` int(10) unsigned NOT NULL default \'1\',
                `id_custom_field` int(10) unsigned NOT NULL,
                `resource_id` int(10) unsigned NOT NULL,
                `value` text NOT NULL,
                PRIMARY KEY (`id_custom_field_content`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

        return Db::getInstance()->execute($sql);
    }

    /*
    * createAdvancedCustomFieldsContentLanguageTable
    */
    public static function createAdvancedCustomFieldsContentLanguageTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'_lang` (
                      `id_custom_field_content` int(10) unsigned NOT NULL,
                      `id_lang` int(10) unsigned NOT NULL,
                      `lang_value` text NOT NULL,
                      PRIMARY KEY (`id_custom_field_content`,`id_lang`)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

        return Db::getInstance()->execute($sql);
    }




    /*
    * clearContent
    */
    public static function clearContent($store, $resource_id, $id_custom_field)
    {
        // ask database
        $sql = 'SELECT `id_custom_field_content`
                FROM `'._DB_PREFIX_.self::$definition['table'].'`
                WHERE `id_custom_field` = "'.(int)$id_custom_field.'" AND `resource_id` = "'.(int)$resource_id.'" AND `id_store` = "'.(int)$store.'"';
        $res =  Db::getInstance()->ExecuteS($sql);
        // if content ids found
        if ($res) {
            foreach ($res as $row) {
                // get content id
                $contentID = $row['id_custom_field_content'];
                // delete main data
                $sql = 'DELETE FROM `'._DB_PREFIX_.self::$definition['table'].'` WHERE `id_custom_field_content` = "'.(int)$contentID.'" ';
                Db::getInstance()->Execute($sql);
                // delete language data
                $sql = 'DELETE FROM `'._DB_PREFIX_.self::$definition['table'].'_lang` WHERE `id_custom_field_content` = "'.(int)$contentID.'" ';
                Db::getInstance()->Execute($sql);
            }
        }
        return;
    }



    /*
    * getContentID
    */
    public static function getContentID($store, $id_custom_field, $resource_id)
    {
        // ask database for simple value
        $sql = 'SELECT `cfc`.`id_custom_field_content`
                FROM `'._DB_PREFIX_.self::$definition['table'].'` as `cfc`
                WHERE `cfc`.`id_custom_field` = "'.(int)$id_custom_field.'" AND `cfc`.`resource_id` = "'.(int)$resource_id.'" AND `cfc`.`id_store` = "'.(int)$store.'"';
        $row = Db::getInstance()->getRow($sql);
        if ($row['id_custom_field_content']!='') {
            // return result
            return $row['id_custom_field_content'];
        }
        // return
        return false;
    }


    /*
    * deleteCustomFieldContent
    */
    public static function deleteCustomFieldContent($customFieldID)
    {
        // ask database
        $sql = 'SELECT `id_custom_field_content`
                FROM `'._DB_PREFIX_.self::$definition['table'].'`
                WHERE `id_custom_field` = "'.(int)$customFieldID.'" ';
        $res =  Db::getInstance()->ExecuteS($sql);
        // if content ids found
        if ($res) {
            foreach ($res as $row) {
                // clear main table
                $sql = 'DELETE FROM `'._DB_PREFIX_.self::$definition['table'].'` WHERE `id_custom_field_content` = "'.(int)$row['id_custom_field_content'].'" ';
                Db::getInstance()->Execute($sql);
                // clear lang table
                $sql = 'DELETE FROM `'._DB_PREFIX_.self::$definition['table'].'_lang` WHERE `id_custom_field_content` = "'.(int)$row['id_custom_field_content'].'" ';
                Db::getInstance()->Execute($sql);
            }
        }
        // return
        return;
    }



    /*
    * getProductCustomFieldsContent
    */
    public static function getProductCustomFieldsContent($store, $resource_id)
    {
        $response = array();
        // ask database for simple value
        $sql = 'SELECT `cfc`.*
                FROM `'._DB_PREFIX_.self::$definition['table'].'` as `cfc`
                INNER JOIN `'._DB_PREFIX_.'advanced_custom_fields` as `cf` ON `cf`.`id_custom_field` = `cfc`.`id_custom_field`
                WHERE `cf`.`location` = "product" AND `cfc`.`resource_id` = "'.(int)$resource_id.'" AND `cfc`.`id_store` = "'.(int)$store.'"';
        $res =  Db::getInstance()->ExecuteS($sql);
        // if content found
        if ($res) {
            return $res;
        }
        // return
        return;
    }

    /*
    * End of File
    */
}
