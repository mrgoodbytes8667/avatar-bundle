<?php


namespace Bytes\AvatarBundle;


use Bytes\AvatarBundle\DependencyInjection\Compiler\AvatarPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class BytesAvatarBundle
 * @package Bytes\AvatarBundle
 */
class BytesAvatarBundle extends Bundle
{

    /**
     * Use this method to register compiler passes and manipulate the container during the building process.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AvatarPass());
    }
}