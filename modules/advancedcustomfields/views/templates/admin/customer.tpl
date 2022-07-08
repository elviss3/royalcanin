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
<div class="panel">
    <div class="panel-heading">
		    <i class="icon-user"></i> {l s='Additional Customer Info' mod='advancedcustomfields'}
  	</div>


	  <div class="row">
		    <div class="col-xs-12 col-sm-4">
            <span class="acf-customer-info">{l s='Editable fields' mod='advancedcustomfields'}</span>
            {foreach from=$editableCustomFields item=customField}
                <div class="row">
        						<label class="control-label col-lg-4">{$customField['name']}:</label>
        						<div class="col-lg-7">
    							       <p class="form-control-static">
                           {if $customField['value']=='acf-checked'}
                              <i class="icon-check"></i>
                           {else if $customField['value']=='acf-not-checked'}
                              <i class="icon-remove"></i>
                           {else}
                              {$customField['value']}
                           {/if}
                         </p>
        						</div>
      					</div>
            {/foreach}
		     </div>
         <div class="col-xs-12  col-sm-4">
             <span class="acf-customer-info">{l s='Public fields' mod='advancedcustomfields'}</span>
             {foreach from=$publicCustomFields item=customField}
                 <div class="row">
         						<label class="control-label col-lg-4">{$customField['name']}:</label>
         						<div class="col-lg-7">
     							       <p class="form-control-static">
                            {if $customField['value']=='acf-checked'}
                               <i class="icon-check"></i>
                            {else if $customField['value']=='acf-not-checked'}
                               <i class="icon-remove"></i>
                            {else}
                               {$customField['value']}
                            {/if}
                          </p>
         						</div>
       					</div>
             {/foreach}
 		     </div>
         <div class="col-xs-12  col-sm-4">
             <span class="acf-customer-info">{l s='Admin only fields' mod='advancedcustomfields'}</span>
             {foreach from=$adminOnlyCustomFields item=customField}
                 <div class="row">
         						<label class="control-label col-lg-4">{$customField['name']}:</label>
         						<div class="col-lg-7">
     							       <p class="form-control-static">
                            {if $customField['value']=='acf-checked'}
                               <i class="icon-check"></i>
                            {else if $customField['value']=='acf-not-checked'}
                               <i class="icon-remove"></i>
                            {else}
                               {$customField['value']}
                            {/if}
                          </p>
         						</div>
       					</div>
             {/foreach}
 		     </div>
	   </div>
</div>
