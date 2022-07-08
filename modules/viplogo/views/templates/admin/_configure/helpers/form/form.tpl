{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'images'}
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

	{elseif $input.type == 'copy'}

	<div class="col-sm-12 col-lg-4">
		<a target="_blank" href="http://4vip.pl"><img style="margin-right: 30px;" src="{$uri}img/copy.png"></a>
	</div>
	<div class="col-sm-12 col-lg-8">
		<font color="#644f40">Distributing, copying, use code, and code modifications without permission is prohibited<br><br> copyright © 2015 design4VIP - </font>
		<a href="mailto:design@4vip.pl?subject=Pytanie dotyczące modułu {$input.mod_name}"><font color="#FF0000"><b>{l s='Contact'}</b></font></a>
	</div>

{*
	<table style="width="100%"><div>
		<tr>
			<td>
				<a target="_blank" href="http://4vip.pl"><img style="margin-right: 30px;" src="{$uri}img/copy.png"></a>
			</td>
			<td>
				<font color="#644f40">Distributing, copying, use code, and code modifications without permission is prohibited<br><br> copyright © 2013 design4VIP - </font>
				<a href="mailto:design@4vip.pl?subject=Pytanie dotyczące modułu {$mod_name}"><font color="#FF0000"><b>{l s='Contact'}</b></font></a>
			</td>
		</tr>
	</table>
*}

	{else}
		{$smarty.block.parent}
	{/if}
{/block}
