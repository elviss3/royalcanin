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
	<div class="col-xs-12 ">
		{if $saveSuccess}
			<div class="alert alert-success">{l s='Your settings have been saved successfully!' mod='advancedcustomfields'}</div>
		{/if}
		{if count($errors)>0}
				<div class="alert alert-danger">
						<ul>
								{foreach from=$errors item=error}
										<li>{$error}</li>
								{/foreach}
					</ul>
				</div>
		{/if}
		{$form}
	</div>

</div>
