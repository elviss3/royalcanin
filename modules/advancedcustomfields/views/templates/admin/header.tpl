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

<div id="advancedcustomfields" class="bootstrap">
	<div class="advancedcustomfields-nav">

		<div class="pull-left mod-title">
			<img class="logo" src="{$logoSrc|escape:'htmlall':'UTF-8'}">
			<a href="{$uri|escape:'htmlall':'UTF-8'}" >Advanced Custom Fields</a>
		</div>

		{if !$dashboard}
				<div class="pull-right advancedcustomfields-menu">
					<div class="menu-item">
							<div class="menu-item-icon">
									<a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=getCustomFields&location=customer" ><i class="material-icons">account_circle</i>
							</div>
							<div class="menu-item-text">
									{l s='Customer fields' mod='advancedcustomfields'}</a>
							</div>
					</div>
					<div class="menu-item">
							<div class="menu-item-icon">
									<a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=getCustomFields&location=category" ><i class="material-icons">folder</i>
							</div>
							<div class="menu-item-text">
									{l s='Category fields' mod='advancedcustomfields'}</a>
							</div>
					</div>
					<div class="menu-item">
							<div class="menu-item-icon">
									<a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=getCustomFields&location=product" ><i class="material-icons">style</i>
							</div>
							<div class="menu-item-text">
									{l s='Product fields' mod='advancedcustomfields'}</a>
							</div>
					</div>
					<div class="menu-item">
						 	<div class="menu-item-icon">
									<a href="{$uri|escape:'htmlall':'UTF-8'}&moduleController=customFieldForm" ><i class="material-icons">add_circle</i>
							</div>
							<div class="menu-item-text">
									{l s='Add a custom field' mod='advancedcustomfields'}</a>
							</div>
					</div>
				</div>
		{/if}

	</div>
	<br>
	{if $displayCurrentStore}
		<div class="advancedcustomfields-current-store-title">
			{l s='Current Store' mod='advancedcustomfields'}: {$storeName|escape:'htmlall':'UTF-8'}
		</div>
	{/if}
	<div class="advancedcustomfields-content">
