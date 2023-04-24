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

        $definition = $container->getDefinition('bytes_avatar.controller.gravatar_api');
        $definition->replaceArgument(0, $config['null_user_replacement']);

        $definition = $container->getDefinition('bytes_avatar.controller.multiavatar_api');
        $definition->replaceArgument(1, $config['multiavatar']['salt']);
        $definition->replaceArgument(2, $config['multiavatar']['field']);
        $definition->replaceArgument(3, $config['null_user_replacement']);

        foreach(['gravatar', 'multiavatar'] as $item)
        {
            if(!isset($config[$item]))
            {
                $config[$item]['enable'] = false;
            }
            
            if(!isset($config[$item]['enable']))
            {
                $config[$item]['enable'] = false;
            }
        }

        $definition = $container->getDefinition('bytes_avatar.locator.avatars');
        $skips = [];
        if(!$config['gravatar']['enable'])
        {
            $skips['gravatar'] = true;
        }
        
        if(!$config['multiavatar']['enable'])
        {
            $skips['multiAvatar'] = true;
        }
        
        $definition->replaceArgument(0, $skips);

        $definition = $container->getDefinition('bytes_avatar.image');
        $definition->replaceArgument(1, $config['cache']['local']['success']['enable']);
        $definition->replaceArgument(2, $config['cache']['local']['success']['key']);
        $definition->replaceArgument(3, $config['cache']['local']['success']['duration']);
        $definition->replaceArgument(4, $config['cache']['local']['fallback']['enable']);
        $definition->replaceArgument(5, $config['cache']['local']['fallback']['key']);
        $definition->replaceArgument(6, $config['cache']['local']['fallback']['duration']);
        $definition->replaceArgument(7, $config['cache']['response']['success']['cached']['duration']);
        $definition->replaceArgument(8, $config['cache']['response']['success']['initial']['duration']);
        $definition->replaceArgument(9, $config['cache']['response']['fallback']['duration']);

        $definition = $container->getDefinition('bytes_avatar.controller.avatar_select2_api');
        $definition->replaceArgument(4, $config['select2_filter']);
    }
}