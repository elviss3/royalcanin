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
$(document).ready(function(){

  // run once on load
  showLocationHooks($('#advancedcustomfields #location').val());

  // init an on change event
  $('#advancedcustomfields #location').change(function(){
      // show location hooks
      showLocationHooks($(this).val());
  });

  // showLocationHooks
  function showLocationHooks(location)
  {
    // for product
    if (location=='product'){
      // show
      $('.form-group.admin-product-hook-option').show();
      $('.form-group.display-frontend-options').show();
      $('.form-group.type-admin-form-option').show();
      // hide
      $('.form-group.required-option').hide();
      $('.form-group.editable-frontend-option').hide();
      $('.form-group.type-frontend-form-option').hide();
    }
    // for category
    if (location=='category'){
      // show
      $('.form-group.required-option').show();
      $('.form-group.display-frontend-options').show();
      $('.form-group.type-admin-form-option').show();
      // hide
      $('.form-group.editable-frontend-option').hide();
      $('.form-group.admin-product-hook-option').hide();
      $('.form-group.type-frontend-form-option').hide();
    }
    // for customer
    if (location=='customer'){
      // hide
      $('.form-group.admin-product-hook-option').hide();
      // show
      $('.form-group.required-option').show();
      $('.form-group.editable-frontend-option').show();

      // show field types according to editable option
      if ($('#advancedcustomfields #editable_frontend_on').is(':checked')){
        $('.form-group.type-admin-form-option').hide();
        $('.form-group.translatable-option').hide();
        $('.form-group.type-frontend-form-option').show();
      }
      if ($('#advancedcustomfields #editable_frontend_off').is(':checked')){
        $('.form-group.type-admin-form-option').show();
        $('.form-group.translatable-option').show();
        $('.form-group.type-frontend-form-option').hide();
      }
    }

  }

  // get field types if editable option changed
  $('#advancedcustomfields input[name=editable_frontend]').click(function(){
    // if editable on
    if ($('#advancedcustomfields input[name=editable_frontend]:checked').val() == 1){
      $('.form-group.type-admin-form-option').hide();
      $('.form-group.translatable-option').hide();
      $('.form-group.type-frontend-form-option').show();
    }
    // if editable off
    if ($('#advancedcustomfields input[name=editable_frontend]:checked').val() == 0){
      $('.form-group.type-admin-form-option').show();
      $('.form-group.translatable-option').show();
      $('.form-group.type-frontend-form-option').hide();
    }
  });







  // run once on load
  if ($('#advancedcustomfields #type_frontend_form').is(":visible")){
      showFieldOptions($('#advancedcustomfields #type_frontend_form').val());
  }
  if ($('#advancedcustomfields #type_admin_form').is(":visible")){
      showFieldOptions($('#advancedcustomfields #type_admin_form').val());
  }

  // init an on change event
  $('#advancedcustomfields #type_admin_form').change(function(){
      // show location hooks
      showFieldOptions($(this).val());
  });
  // init an on change event
  $('#advancedcustomfields #type_frontend_form').change(function(){
      // show location hooks
      showFieldOptions($(this).val());
  });

  // showFieldOptions
  function showFieldOptions(fieldType)
  {
    // translatable option
    if (fieldType == 'text' || fieldType == 'textarea' || fieldType == 'editor'){
      if ($('#advancedcustomfields #editable_frontend_off').is(':checked')){
        $('.form-group.translatable-option').show();
      }
    } else {
      $('.form-group.translatable-option').hide();
    }
    // available & default values options
    if (fieldType == 'checklist' || fieldType == 'select' || fieldType == 'radio'){
      $('.form-group.available-values-option').show();
      $('.form-group.default-value-option').show();
    } else {
      $('.form-group.available-values-option').hide();
      $('.form-group.default-value-option').hide();
    }
    // allow empty option for select fields
    if (fieldType == 'select'){
      $('.form-group.allow-empty-select-option').show();
    } else {
      $('.form-group.allow-empty-select-option').hide();
    }

    // default & default values options
    if (fieldType == 'checkbox' || fieldType == 'switch'){
      $('.form-group.default-status-option').show();
    } else {
      $('.form-group.default-status-option').hide();
    }
    // default & default values options
    if (fieldType == 'checkbox'){
      $('.form-group.single-label-option').show();
    } else {
      $('.form-group.single-label-option').hide();
    }
    // default status option
    if($('#advancedcustomfields #location').val()!='product'){
      if (fieldType == 'switch'){
        $('.form-group.required-option').hide();
      } else {
        $('.form-group.required-option').show();
      }
    }
  }



});
