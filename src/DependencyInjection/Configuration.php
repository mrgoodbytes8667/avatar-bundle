<?php


namespace Bytes\AvatarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bytes_avatar');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('user_class')->defaultValue('App\Entity\User')->end()
                ->arrayNode('multiavatar')
                    ->children()
                        ->scalarNode('field')->defaultValue('id')->end()
                        ->scalarNode('salt')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}