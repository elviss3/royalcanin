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
  for (var i = 0; i < acfFields.length; i++) {
    $('input[name="'+acfFields[i]+'"]').attr('checked','checked');
  }
});
