<?php

namespace NF_FU_VENDOR\Composer\Installers;

use NF_FU_VENDOR\Composer\DependencyResolver\Pool;
class MantisBTInstaller extends BaseInstaller
{
    protected $locations = array('plugin' => 'plugins/{$name}/');
    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars($vars)
    {
        $vars['name'] = \strtolower(\preg_replace('/(?<=\\w)([A-Z])/', 'NF_FU_VENDOR\\_\\1', $vars['name']));
        $vars['name'] = \str_replace(array('-', '_'), ' ', $vars['name']);
        $vars['name'] = \str_replace(' ', '', \ucwords($vars['name']));
        return $vars;
    }
}
