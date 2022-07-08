<div class="rich_content-container mb-3 row">
    <div class="translations tabbable custom-tabs col-sm-12 ">
        <div class="translationsFields tab-content">
            {foreach from=$languages item=language }
            <div class="tab-pane translation-label-{$language.iso_code} tab-pane{if $id_language == $language.id_lang} show active{/if} translation-field  translation-label-{$language.iso_code}" data-locale="{$language.iso_code}">
                <h2 name="rich_content_field_lang_{$language.id_lang}">{l s='Rich Content (Zewnętrzny opis HTML)'}</h2>
            </div>
            {/foreach}
        </div>
    </div>
    <div class="translationsFields tab-content custom-field px-3 col-sm-12 mb-2">
            {foreach from=$languages item=language }
            <div class="translation-label-{$language.iso_code} tab-pane{if $id_language == $language.id_lang} show active{/if} translation-field  translation-label-{$language.iso_code}" data-locale="{$language.iso_code}">
                <input readonly type="text" name="rich_content_field_lang_wysiwyg_{$language.id_lang}" id="rich_content_field_lang_wysiwyg_{$language.id_lang}" value="{if isset({$rich_content_field_lang_wysiwyg[$language.id_lang]}) && {$rich_content_field_lang_wysiwyg[$language.id_lang]} != ''}{$rich_content_field_lang_wysiwyg[$language.id_lang]}{/if}" class="form-control">
            </div>
            {/foreach}
            <small class="form-text text-muted text-right maxLength maxType">{l s='Wybierz istniejący opis z listy rozwijanej lub wgraj plik .ZIP poniżej' mod='ms_rich_contentfield'}</small>
    </div>
    <div class="form-group block_serwer_files col-sm-12">

            <select id="select_directories" class="form-control custom-select">
                <option value="">-- Wybierz istniejący opis --</option>
                {foreach $directories as $dir}
                    <option value="{$dir}">{$dir}</option>
                {/foreach}
            </select>

            <div class="form-control mt-3">
                <label for="zipFile">Wybierz plik:</label>
                <input type="file" id="zipFile" name="zipFile" class="ml-1"> 
                <span class="btn btn-outline-secondary addfile ml-2">Wgraj plik</span>
                <small class="form-text text-muted text-right maxLength maxType mt-2 col-sm-12">{l s='Wgraj tylko pliki .ZIP spakowanych opisów wraz z katalogiem, ZAPISZ PRODUKT!' mod='ms_rich_contentfield'}</small>
            </div>
            <span class="rounded bg-success text-white p-3 px-5 position-absolute mt-2 d-none" id="zip_success">{l s='Plik został wgrany' mod='ms_rich_contentfield'}</span>
    </div>
</div>

<div class="composition-container mb-3">
    <div class="translations tabbable custom-tabs">
        <div class="translationsFields tab-content ">
            {foreach from=$languages item=language }
            <div class="tab-pane translation-label-{$language.iso_code} tab-pane{if $id_language == $language.id_lang} show active{/if} translation-field  translation-label-{$language.iso_code}" data-locale="{$language.iso_code}">
                <h2 name="composition_field_lang_{$language.id_lang}">{l s='Skład'}</h2>
            </div>
            {/foreach}
        </div>
    </div>
    <div class="translationsFields tab-content custom-field">
        {foreach from=$languages item=language}
			{if $languages|count > 1}
			<div class="translatable-field tab-pane translation-label-{$language.iso_code} {if $id_lang_default == $language.id_lang}active{/if}">													
			{/if}
				<textarea id="composition_field_lang_wysiwyg_{$language.id_lang|intval}" name="composition_field_lang_wysiwyg[{$language.id_lang|intval}]" class="autoload_rte form-control">{$composition_field_lang_wysiwyg[$language.id_lang]}</textarea>				
			{if $languages|count > 1}
			</div>													
			{/if}	
		{/foreach}
    </div>		
</div>

<div class="nutri_values-container mb-3">
    <div class="translations tabbable custom-tabs">
        <div class="translationsFields tab-content ">
            {foreach from=$languages item=language }
            <div class="tab-pane translation-label-{$language.iso_code} tab-pane{if $id_language == $language.id_lang} show active{/if} translation-field  translation-label-{$language.iso_code}" data-locale="{$language.iso_code}">
                <h2 name="nutri_values_field_lang_{$language.id_lang}">{l s='Wartości odżywcze'}</h2>
            </div>
            {/foreach}
        </div>
    </div>
    <div class="translationsFields tab-content custom-field">
        {foreach from=$languages item=language}
			{if $languages|count > 1}
			<div class="translatable-field tab-pane translation-label-{$language.iso_code} {if $id_lang_default == $language.id_lang}active{/if}">													
			{/if}
				<textarea id="nutri_values_field_lang_wysiwyg_{$language.id_lang|intval}" name="nutri_values_field_lang_wysiwyg[{$language.id_lang|intval}]" class="autoload_rte form-control">{$nutri_values_field_lang_wysiwyg[$language.id_lang]}</textarea>				
			{if $languages|count > 1}
			</div>													
			{/if}	
		{/foreach}
    </div>
</div>

<div class="nutri_information mb-3">
    <div class="translations tabbable custom-tabs">
        <div class="translationsFields tab-content ">
            {foreach from=$languages item=language }
            <div class="tab-pane translation-label-{$language.iso_code} tab-pane{if $id_language == $language.id_lang} show active{/if} translation-field  translation-label-{$language.iso_code}" data-locale="{$language.iso_code}">
                <h2 name="nutri_information_field_lang_{$language.id_lang}">{l s='Informacje żywieniowe'}</h2>
            </div>
            {/foreach}
        </div>
    </div>
    <div class="translationsFields tab-content custom-field">
        {foreach from=$languages item=language}
			{if $languages|count > 1}
			<div class="translatable-field tab-pane translation-label-{$language.iso_code} {if $id_lang_default == $language.id_lang}active{/if}">													
			{/if}
				<textarea id="nutri_information_field_lang_wysiwyg_{$language.id_lang|intval}" name="nutri_information_field_lang_wysiwyg[{$language.id_lang|intval}]" class="autoload_rte form-control">{$nutri_information_field_lang_wysiwyg[$language.id_lang]}</textarea>				
			{if $languages|count > 1}
			</div>													
			{/if}	
		{/foreach}
    </div>		
</div>

{literal}
    <script>
    $("#select_directories").change(function() {
        var $value = this.value;
        $("#rich_content_field_lang_wysiwyg_{/literal}{$language.id_lang}{literal}").val($value);
    });
    </script>
{/literal}

<input type="hidden" id="link_richcontent" value="{$module_dir}" />
