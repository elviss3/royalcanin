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



{if count($customFields)>0 }
    {foreach from=$customFields item=customField}

        {if $customField['type'] == 'text'}
           <div>
               <h2>{$customField['name']}</h2>
               <div class="row">
                   <div class="col-md-6">
                       {if $customField['translatable']}
                           <div class="translations tabbable" >
                              <div class="translationsFields tab-content">
                                  {foreach from=$languages item=language }
                                      <div class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}show active{/if}">
                                          <input type="text" name="{$customField['technical_name']}_{$language.id_lang}" class="edit js-edit form-control" value="{$customField['value'][$language.id_lang]}">
                                      </div>
                                  {/foreach}
                              </div>
                           </div>
                       {else}
                           <fieldset class="form-group">
                               <!--<label class="form-control-label" for="exampleInput1">{$customField['name']}</label>-->
                               <input type="text" name="{$customField['technical_name']}" class="form-control" value="{$customField['value']}"></input>
                           </fieldset>
                       {/if}
                       <span class="small font-secondary">{$customField['description']}</span>
                   </div>
               </div>
           </div>



       {else if $customField['type'] == 'textarea'}
           <div>
               <h2>{$customField['name']}</h2>
               <div class="row">
                   <div class="col-md-6">
                       {if $customField['translatable']}
                           <div class="translations tabbable" >
                              <div class="translationsFields tab-content">
                                  {foreach from=$languages item=language }
                                      <div class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}active{/if}">
                                          <textarea name="{$customField['technical_name']}_{$language.id_lang}" class="edit js-edit form-control">{$customField['value'][$language.id_lang]}</textarea>
                                      </div>
                                  {/foreach}
                              </div>
                           </div>
                       {else}
                           <fieldset class="form-group">
                               <!--<label class="form-control-label" for="exampleInput1">{$customField['name']}</label>-->
                               <textarea name="{$customField['technical_name']}" class="form-control">{$customField['value']}</textarea>
                           </fieldset>
                       {/if}
                       <span class="small font-secondary">{$customField['description']}</span>
                   </div>
               </div>
           </div>



       {else if $customField['type'] == 'editor'}
           <div>
               <h2>{$customField['name']}</h2>
               <div class="row">
                   <div class="col-md-6">
                       {if $customField['translatable']}
                           <div class="translations tabbable" >
                              <div class="translationsFields tab-content">
                                  {foreach from=$languages item=language }
                                      <div class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}active{/if}">
                                          <textarea name="{$customField['technical_name']}_{$language.id_lang}" class=" autoload_rte edit js-edit form-control">{$customField['value'][$language.id_lang]}</textarea>
                                      </div>
                                  {/foreach}
                              </div>
                           </div>
                       {else}
                           <fieldset class="form-group">
                               <!--<label class="form-control-label" for="exampleInput1">{$customField['name']}</label>-->
                               <textarea name="{$customField['technical_name']}" class="form-control autoload_rte">{$customField['value']}</textarea>
                           </fieldset>
                       {/if}
                       <span class="small font-secondary">{$customField['description']}</span>
                   </div>
               </div>
           </div>



       {else if $customField['type'] == 'select'}
          <div>
              <h2>{$customField['name']}</h2>
              <div class="row">
                  <div class="col-md-6">
                      <fieldset class="form-group">
                          <select data-toggle="select2" name="{$customField['technical_name']}">
                              {if $customField['allow_empty_select']}<option>...</option>{/if}
                              {foreach from=$customField['available_values'] item=option}
                                  <option value="{$option['value']}" {if $option['value']==$customField['value']}selected="selected"{/if}>{$option['label']}</option>
                              {/foreach}
                          </select>
                          <span class="small font-secondary">{$customField['description']}</span>
                      </fieldset>
                  </div>
              </div>
          </div>




       {else if $customField['type'] == 'radio'}
          <div>
              <h2>{$customField['name']}</h2>
              <div class="row">
                  <div class="col-md-6">
                      <fieldset class="form-group">
                          <div class="radio">
                              {foreach from=$customField['available_values'] item=option}
                                  <label><input type="radio" name="{$customField['technical_name']}" value="{$option['value']}" {if $option['value']==$customField['value']}checked="checked"{/if}> {$option['label']}</label>
                              {/foreach}
                          </div>
                          <span class="small font-secondary">{$customField['description']}</span>
                      </fieldset>
                  </div>
              </div>
          </div>



       {else if $customField['type'] == 'checklist'}
          <div>
              <h2>{$customField['name']}</h2>
              <div class="row">
                  <div class="col-md-6">
                      {foreach from=$customField['available_values'] item=option}
                          <div class="checkbox">
                              <label class="">
                                  <input type="checkbox" name="{$customField['technical_name']}_{$option['value']}" value="on" {foreach from=$customField['value'] item=value}{if $value['value']==$option['value']}checked="checked"{/if}{/foreach}>
                                  {$option['label']}
                              </label>
                          </div>
                      {/foreach}
                      <span class="small font-secondary">{$customField['description']}</span>
                  </div>
              </div>
          </div>



       {else if $customField['type'] == 'checkbox'}
          <div>
              <h2>{$customField['name']}</h2>
              <div class="row">
                  <div class="col-md-6">
                      <div class="checkbox">
                          <label class="">
                              <input type="checkbox" name="{$customField['technical_name']}_on" value="on" {if $customField['value']=='on'}checked="checked"{/if}>
                              {$customField['single_label']}
                          </label>
                      </div>
                      <span class="small font-secondary">{$customField['description']}</span>
                  </div>
              </div>
          </div>



       {else if $customField['type'] == 'switch'}
           <div>
               <h2>{$customField['name']}</h2>
               <div class="row">
                   <div class="col-md-6">
                        <input class="switch-input-lg" data-toggle="switch" type="checkbox" name="{$customField['technical_name']}" value="1" {if $customField['value']==1}checked="checked"{/if}>
                        <br><span class="small font-secondary">{$customField['description']}</span>
                   </div>
               </div>
           </div>



       {else if $customField['type'] == 'date'}
          <div>
              <h2>{$customField['name']}</h2>
              <div class="row">
                  <div class="col-md-6">
                      <!--<label class="form-control-label">Availability date</label>-->
                      <div class="input-group datepicker">
                          <input type="text" class="form-control" {*id="form_step3_available_date"*} name="{$customField['technical_name']}" placeholder="YYYY-MM-DD" value="{$customField['value']}">
                          <div class="input-group-append"><div class="input-group-text"><i class="material-icons">date_range</i></div></div>
                      </div>
                      <span class="small font-secondary">{$customField['description']}</span>
                  </div>
              </div>
          </div>


       {/if}





    {/foreach}
    <br><br>
{/if}
