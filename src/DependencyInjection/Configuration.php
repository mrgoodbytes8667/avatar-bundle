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
                ->scalarNode('null_user_replacement')->defaultValue('')->end()
                ->arrayNode('gravatar')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('multiavatar')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('field')->defaultValue('id')->end()
                        ->scalarNode('salt')->defaultValue('')->end()
                        ->booleanNode('enable')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}