<?php


namespace Bytes\AvatarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use function Symfony\Component\String\u;

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
                ->scalarNode('select2_filter')
                    ->defaultValue('avatar_thumb_30x30')
                    ->info('Default Liip filter to use in the select2 route')
                ->end()
                ->arrayNode('cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('success')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('key')
                                    ->defaultValue('bytes_avatar')
                                    ->validate()
                                        ->always()->then(function ($value) {
                                            $key = u($value);
                                            if($key->endsWith('.'))
                                            {
                                                $key = $key->beforeLast('.');
                                            }
                                            return $key->toString();
                                        })
                                    ->end()
                                ->end()
                                ->integerNode('duration')
                                    ->min(1)
                                    ->defaultValue(15)
                                    ->info('Length of time (in minutes) to cache a remote image temporarily when instantiating the cache')
                                ->end()
                                ->booleanNode('enable')->defaultTrue()->info('Cache remote URL responses for a short time to prevent repeated calls to remote sites')->end()
                            ->end()
                        ->end()
                        ->arrayNode('fallback')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('key')
                                    ->defaultValue('bytes_avatar')
                                    ->validate()
                                        ->always()->then(function ($value) {
                                            $key = u($value);
                                            if($key->endsWith('.'))
                                            {
                                                $key = $key->beforeLast('.');
                                            }
                                            return $key->toString();
                                        })
                                    ->end()
                                ->end()
                                ->integerNode('duration')
                                    ->min(1)
                                    ->defaultValue(5)
                                    ->info('Length of time (in minutes) to cache a remote image temporarily when instantiating the cache')
                                ->end()
                                ->booleanNode('enable')->defaultTrue()->info('Cache remote URL responses for a short time to prevent repeated calls to remote sites')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
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
                        ->booleanNode('enable')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}