<?php


namespace Bytes\AvatarBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Class BytesAvatarExtension
 * @package Bytes\AvatarBundle\DependencyInjection
 */
class BytesAvatarExtension extends Extension implements ExtensionInterface
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $configuration = $this->getConfiguration($configs, $container);

        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('bytes_avatar.user_param_converter');
        $definition->replaceArgument(1, $config['user_class']);

        $definition = $container->getDefinition('bytes_avatar.avatar_api_controller');
        $definition->replaceArgument(1, $config['multiavatar']['salt']);
        $definition->replaceArgument(2, $config['multiavatar']['field']);
        $definition->replaceArgument(3, $config['null_user_replacement']);
    }
}