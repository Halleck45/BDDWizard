<?php

namespace Behat\WizardExtension;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Behat\Behat\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/*
 * This file is part of the Behat\WizardExtension
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Gui extension for Behat
 *
 * @author Jean-François Lépine
 */
class Extension implements ExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    Extension configuration hash (from behat.yml)
     * @param ContainerBuilder $container ContainerBuilder instance
     */
    public function load(array $config, ContainerBuilder $container)
    {


        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/services'));
        $loader->load('core.yml');

    }

    /**
     * Setups configuration for current extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
    }

    /**
     * Returns compiler passes used by extension.
     *
     * @return array
     */
    public function getCompilerPasses()
    {
        return array(
        );
    }
}