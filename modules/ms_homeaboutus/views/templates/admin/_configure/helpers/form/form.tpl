{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'file_lang'}
		<div class="col-lg-9">
			{foreach from=$languages item=language}
				{if $languages|count > 1}
					<div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
				{/if}
				<div class="form-group">
					<div class="col-lg-6">
						<input id="{$input.name}_{$language.id_lang}" type="file" name="{$input.name}_{$language.id_lang}" class="hide" />
						<div class="dummyfile input-group">
							<span class="input-group-addon"><i class="icon-file"></i></span>
							<input id="{$input.name}_{$language.id_lang}-name" type="text" class="disabled" name="filename" readonly />
							<span class="input-group-btn">
								<button id="{$input.name}_{$language.id_lang}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
									<i class="icon-folder-open"></i> {l s='Choose a file' d='Modules.Banner.Shop'}
								</button>
							</span>
						</div>
					</div>
					{if $languages|count > 1}
						<div class="col-lg-2">
							<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
								{$language.iso_code}
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								{foreach from=$languages item=lang}
								<li><a href="javascript:hideOtherLanguage({$lang.id_lang});" tabindex="-1">{$lang.name}</a></li>
								{/foreach}
							</ul>
						</div>
					{/if}
				</div>
				<div class="form-group">
					{if isset($fields_value[$input.name][$language.id_lang]) && $fields_value[$input.name][$language.id_lang] != ''}
					<div id="{$input.name}-{$language.id_lang}-images-thumbnails" class="col-lg-12">
						<img src="{$uri}img/{$fields_value[$input.name][$language.id_lang]}" class="img-thumbnail"/>
					</div>
					{/if}
				</div>
				{if $languages|count > 1}
					</div>
				{/if}
				<script>
				$(document).ready(function(){
					$('#{$input.name}_{$language.id_lang}-selectbutton').click(function(e){
						$('#{$input.name}_{$language.id_lang}').trigger('click');
					});
					$('#{$input.name}_{$language.id_lang}').change(function(e){
						var val = $(this).val();
						var file = val.split(/[\\/]/);
						$('#{$input.name}_{$language.id_lang}-name').val(file[file.length-1]);
					});
				});
			</script>
			{/foreach}
			{if isset($input.desc) && !empty($input.desc)}
				<p class="help-block">
					{$input.desc}
				</p>
			{/if}
		</div>
	{elseif $input.type == 'images'}
		<div class="col-xs-12">

			{foreach $input.values as $value}
					<div class="radio {if isset($input.class)}"{$input.class}"{/if} col-lg-6" style="padding: 20px; display:block; height:auto; vertical-align:middle;">
						<label>
							<input type="radio"	name="{$input.name}" id="{$value.id}" value="{$value.value|escape:'html':'UTF-8'}" style="height:100px;"
								{if $fields_value[$input.name] == $value.value}checked="checked"{/if}
								{if isset($input.disabled) && $input.disabled}disabled="disabled"{/if} />
							<img src="{$uri}img/{$value.label}.jpg" style="max-width:385px; width:100%;{if $fields_value[$input.name] == $value.value}border:3px solid #8BC954;{/if}">

								{*$fields_value[$input.name]}{$value.value*}

						</label>
					</div>
				{if isset($value.p) && $value.p}<p class="help-block">{$value.p}</p>{/if}
			{/foreach}

		</div>

	{elseif $input.type == 'hr'}
                        <hr>
						<h4>
                            {if isset($input.icon)}<i class="{$input.icon}"></i>{/if}
                            {$input.title}
                        </h4>
                        <hr>
	{elseif $input.type == 'copy'}

	<div class="col-sm-12 col-lg-4">
		<a target="_blank" href="http://4vip.pl"><img style="margin-right: 30px;" src="{$uri}img/copy.png"></a>
	</div>
	<div class="col-sm-12 col-lg-8">
		<font color="#644f40">Distributing, copying, use code, and code modifications without permission is prohibited<br><br> copyright © 2015 design4VIP - </font>
		<a href="mailto:design@4vip.pl?subject=Pytanie dotyczące modułu {$input.mod_name}"><font color="#FF0000"><b>{l s='Contact'}</b></font></a>
	</div>

	{else}
		{$smarty.block.parent}
	{/if}
{/block}
