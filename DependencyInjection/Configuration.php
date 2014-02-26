<?php

namespace Falgun\Bundle\PhantomBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('falgun_phantom');

        $rootNode
            ->children()
            ->arrayNode('config')
            ->children()
            ->scalarNode('phantomjs')->end()
            ->scalarNode('format')->end()
            ->scalarNode('margin')->end()
            ->scalarNode('zoom')->end()
            ->scalarNode('orientation')->end()
            ->scalarNode('tmpdir')->end()
            ->scalarNode('rendering_time')->end()
            ->scalarNode('viewport_width')->end()
            ->scalarNode('viewport_height')->end()
            ->scalarNode('rendering_timeout')->end()

            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
