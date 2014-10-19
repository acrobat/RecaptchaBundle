<?php

namespace Acrobat\Bundle\RecaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @author Jeroen Thora <jeroenthora@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acrobat_recaptcha');

        $rootNode
            ->children()
                ->scalarNode('public_key')->isRequired()->end()
                ->scalarNode('private_key')->isRequired()->end()
                ->scalarNode('locale')->defaultValue('%kernel.default_locale%')->end()
                ->scalarNode('enabled')->defaultTrue()->end()
                ->scalarNode('use_ajax')->defaultFalse()->end()
                ->scalarNode('https')
                    ->defaultValue('auto')
                    ->validate()
                    ->ifNotInArray(array('on', 'off', 'auto'))
                    ->thenInvalid('Invalid https setting "%s"')
                    ->end()
            ->end();

        return $treeBuilder;
    }
}
