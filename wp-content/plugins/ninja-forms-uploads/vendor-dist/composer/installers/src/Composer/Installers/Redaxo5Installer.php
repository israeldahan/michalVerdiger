<?php

namespace NF_FU_VENDOR\Composer\Installers;

class Redaxo5Installer extends BaseInstaller
{
    protected $locations = array('addon' => 'redaxo/src/addons/{$name}/', 'bestyle-plugin' => 'redaxo/src/addons/be_style/plugins/{$name}/');
}
