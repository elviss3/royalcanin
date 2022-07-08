<?php

class AdminThemeConfigLinkController extends ModuleAdminController
{
    public function __construct()
    {
        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules').'&configure=ms_themeconfigurator');
    }
}

