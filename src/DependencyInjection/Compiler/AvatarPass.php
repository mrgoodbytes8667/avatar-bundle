<?php


namespace Bytes\AvatarBundle\DependencyInjection\Compiler;


use Bytes\AvatarBundle\Avatar\AvatarChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AvatarPass
 * @package Bytes\AvatarBundle\DependencyInjection\Compiler
 */
class AvatarPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(AvatarChain::class)) {
            return;
        }

        $definition = $container->findDefinition(AvatarChain::class);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('bytes_avatar.avatars.service');

        foreach ($taggedServices as $id => $tags) {
            // a service could have the same tag twice
            foreach ($tags as $attributes) {
                if(!isset($attributes['alias'])) {
                    $attributes['alias'] = $id;
                }
                $definition->addMethodCall('addInstance', [
                    new Reference($id),
                    $attributes['alias']
                ]);
            }

            $taggedDefinition = $container->findDefinition($id);
            $taggedDefinition->addMethodCall('setUrlGenerator', [new Reference('router.default')]);
        }
    }
}