{*
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
*}
<div id="container" class="row">
    <div class="col-xs-12 welcome">
       {l s='Welcome to Advanced Custom Fields!' mod='advancedcustomfields'}
    </div>
  	<div class="col-xs-12 ">
        <div class="dashboard-item col-xs-3">
            <a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=customFieldForm" >
                <i class="material-icons">add_circle</i><br>
                {l s='Add a custom field' mod='advancedcustomfields'}
            </a>
        </div>
        <div class="dashboard-item col-xs-3">
            <a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=getCustomFields&location=product" >
                <i class="material-icons">style</i><br>
                {l s='Product fields' mod='advancedcustomfields'}
            </a>
        </div>
        <div class="dashboard-item col-xs-3">
            <a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=getCustomFields&location=category" >
                <i class="material-icons">folder</i><br>
                {l s='Category fields' mod='advancedcustomfields'}
            </a>
        </div>
        <div class="dashboard-item col-xs-3">
            <a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=getCustomFields&location=customer" >
                <i class="material-icons">account_circle</i><br>
                {l s='Customer fields' mod='advancedcustomfields'}
            </a>
        </div>
  	</div>
</div>
