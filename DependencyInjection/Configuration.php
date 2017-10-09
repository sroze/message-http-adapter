<?php

namespace Sam\Symfony\Message\HttpAdapter\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('message_http_adapter');

        $rootNode
            ->children()
                ->arrayNode('consumers')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->isRequired()->end()
                            ->scalarNode('message')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('producers')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('endpoint')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
