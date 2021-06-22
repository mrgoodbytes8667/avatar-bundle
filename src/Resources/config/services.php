<?php


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bytes\AvatarBundle\Avatar\AvatarChain;
use Bytes\AvatarBundle\Avatar\Gravatar;
use Bytes\AvatarBundle\Avatar\Multiavatar;
use Bytes\AvatarBundle\Controller\AvatarApiController;
use Bytes\AvatarBundle\Controller\AvatarSelect2ApiController;
use Bytes\AvatarBundle\Maker\MakeLiipAvatarConfig;
use Bytes\AvatarBundle\Request\UserParamConverter;
use Bytes\AvatarBundle\Avatar\Avatars;

/**
 * @param ContainerConfigurator $container
 */
return static function (ContainerConfigurator $container) {

    $services = $container->services();

    $services->set('bytes_avatar.avatar_api_controller', AvatarApiController::class)
        ->args([
            service('security.helper'), // Symfony\Component\Security\Core\Security
            '', // $config['multiavatar']['salt']
            '', // $config['multiavatar']['field']
            '', // $config['null_user_replacement']
        ])
        ->alias(AvatarApiController::class, 'bytes_avatar.avatar_api_controller')
        ->public();

    $services->set('bytes_avatar.avatar_select2_api_controller', AvatarSelect2ApiController::class)
        ->args([
            service('security.helper'), // Symfony\Component\Security\Core\Security
            service('liip_imagine.cache.manager'), // Liip\ImagineBundle\Imagine\Cache\CacheManager
            service('bytes_avatar.avatars'), // Bytes\AvatarBundle\Avatar\Avatars
        ])
        ->alias(AvatarSelect2ApiController::class, 'bytes_avatar.avatar_select2_api_controller')
        ->public();

    $services->set('bytes_avatar.avatars', Avatars::class)
        ->args([
            service('security.helper'), // Symfony\Component\Security\Core\Security
            service('bytes_avatar.locator.avatars'),
        ])
        ->call('setUrlGenerator', [service('router.default')])
        ->alias(Avatars::class, 'bytes_avatar.avatars')
        ->public();

    $services->set('bytes_avatar.avatars.gravatar', Gravatar::class)
        ->tag('bytes_avatar.avatars.service', ['alias' => 'gravatar'])
        ->alias(Gravatar::class, 'bytes_avatar.avatars.gravatar')
        ->public();

    $services->set('bytes_avatar.avatars.multiavatar', Multiavatar::class)
        ->tag('bytes_avatar.avatars.service', ['alias' => 'multiAvatar'])
        ->alias(Multiavatar::class, 'bytes_avatar.avatars.multiavatar')
        ->public();

    $services->set('bytes_avatar.user_param_converter', UserParamConverter::class)
        ->args([
            service('doctrine.orm.default_entity_manager'),
            '' // $config['user_class']
        ])
        ->tag('request.param_converter', [
            'converter' => 'bytes_avatar_user'
        ]);

    $services->set('bytes_avatar.command.make_liip_avatar_config', MakeLiipAvatarConfig::class)
        ->args([
            service('router.default'), // Symfony\Component\Routing\Generator\UrlGeneratorInterface
            service('debug.validator'), // Symfony\Component\Validator\Validator\ValidatorInterface
            param('kernel.project_dir'),
        ])
        ->tag('maker.command');

    $services->set('bytes_avatar.locator.avatars', AvatarChain::class)
        ->args([
            ''
        ])
        ->lazy()
        ->alias(AvatarChain::class, 'bytes_avatar.locator.avatars')
        ->public();

};