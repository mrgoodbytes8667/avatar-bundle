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
                            ->setDeprecated('mrgoodbytes8667/avatar-bundle', '0.7.0', 'The child node "%node%" at path "%path%" is deprecated. Please replace with the new "local-cache" node.')
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
                            ->setDeprecated('mrgoodbytes8667/avatar-bundle', '0.7.0', 'The child node "%node%" at path "%path%" is deprecated. Please replace with the new "local-cache" node.')
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

                        ->arrayNode('local')
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
                        ->end() // end local
                        ->arrayNode('response')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('success')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('cached')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->integerNode('duration')
                                                    ->min(1)
                                                    ->defaultValue(15)
                                                    ->info('Length of time (in minutes) to tell the browser cache to cache for')
                                                ->end()
                                            ->end()
                                        ->end()

                                        ->arrayNode('initial')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->integerNode('duration')
                                                    ->min(1)
                                                    ->defaultValue(15)
                                                    ->info('Length of time (in minutes) to tell the browser cache to cache for')
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('fallback')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->integerNode('duration')
                                            ->min(1)
                                            ->defaultValue(5)
                                            ->info('Length of time (in minutes) to tell the browser cache to cache for')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end() // end response

                    ->end()
                ->end() // end cache
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