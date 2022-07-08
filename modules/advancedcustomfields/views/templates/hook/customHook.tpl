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
{if $value=='acf-checked'}
    {if $show_label}{$label}: {/if}<i class="material-icons">done</i>
{else if $value=='acf-not-checked'}
    {if $show_label}{$label}: {/if}<i class="material-icons">clear</i>
{else}
    {if $show_label}{$label}: {/if}{$value nofilter}
{/if}
