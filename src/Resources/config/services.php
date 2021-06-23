<?php


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bytes\AvatarBundle\Avatar\AvatarChain;
use Bytes\AvatarBundle\Avatar\Avatars;
use Bytes\AvatarBundle\Avatar\Gravatar;
use Bytes\AvatarBundle\Avatar\Multiavatar;
use Bytes\AvatarBundle\Controller\AvatarApiController;
use Bytes\AvatarBundle\Controller\AvatarSelect2ApiController;
use Bytes\AvatarBundle\Controller\GravatarApiController;
use Bytes\AvatarBundle\Controller\Image;
use Bytes\AvatarBundle\Controller\MultiAvatarApiController;
use Bytes\AvatarBundle\EventListener\ResolveCacheSubscriber;
use Bytes\AvatarBundle\Imaging\Cache;
use Bytes\AvatarBundle\Maker\MakeLiipAvatarConfig;
use Bytes\AvatarBundle\Request\UserParamConverter;

/**
 * @param ContainerConfigurator $container
 */
return static function (ContainerConfigurator $container) {

    $services = $container->services();

    //region Controllers
    $services->set('bytes_avatar.controller.avatar_api', AvatarApiController::class)
        ->args([
            service('security.helper'), // Symfony\Component\Security\Core\Security
            '', // $config['multiavatar']['salt']
            '', // $config['multiavatar']['field']
            '', // $config['null_user_replacement']
            service('bytes_avatar.image'),
        ])
        ->alias(AvatarApiController::class, 'bytes_avatar.controller.avatar_api')
        ->public();

    $services->set('bytes_avatar.controller.gravatar_api', GravatarApiController::class)
        ->args([
            '', // $config['null_user_replacement']
        ])
        ->call('setImage', [service('bytes_avatar.image')])
        ->call('setSecurity', [service('security.helper')])
        ->alias(GravatarApiController::class, 'bytes_avatar.controller.gravatar_api')
        ->public();

    $services->set('bytes_avatar.controller.multiavatar_api', MultiAvatarApiController::class)
        ->args([
            service('http_client'),
            '', // $config['multiavatar']['salt']
            '', // $config['multiavatar']['field']
            '', // $config['null_user_replacement']
        ])
        ->call('setImage', [service('bytes_avatar.image')])
        ->call('setSecurity', [service('security.helper')])
        ->alias(MultiAvatarApiController::class, 'bytes_avatar.controller.multiavatar_api')
        ->public();

    $services->set('bytes_avatar.controller.avatar_select2_api', AvatarSelect2ApiController::class)
        ->args([
            service('security.helper'), // Symfony\Component\Security\Core\Security
            service('liip_imagine.cache.manager'), // Liip\ImagineBundle\Imagine\Cache\CacheManager
            service('bytes_avatar.cache'), // Bytes\AvatarBundle\Imaging\Cache
            service('bytes_avatar.avatars'), // Bytes\AvatarBundle\Avatar\Avatars
        ])
        ->alias(AvatarSelect2ApiController::class, 'bytes_avatar.controller.avatar_select2_api')
        ->public();
    //endregion

    //region Avatars
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
    //endregion

    //region Requests
    $services->set('bytes_avatar.user_param_converter', UserParamConverter::class)
        ->args([
            service('doctrine.orm.default_entity_manager'),
            '' // $config['user_class']
        ])
        ->tag('request.param_converter', [
            'converter' => 'bytes_avatar_user'
        ]);
    //endregion

    //region Maker
    $services->set('bytes_avatar.command.make_liip_avatar_config', MakeLiipAvatarConfig::class)
        ->args([
            service('router.default'), // Symfony\Component\Routing\Generator\UrlGeneratorInterface
            service('debug.validator'), // Symfony\Component\Validator\Validator\ValidatorInterface
            param('kernel.project_dir'),
        ])
        ->tag('maker.command');
    //endregion

    //region Handlers
    $services->set('bytes_avatar.locator.avatars', AvatarChain::class)
        ->args([
            ''
        ])
        ->lazy()
        ->alias(AvatarChain::class, 'bytes_avatar.locator.avatars')
        ->public();
    //endregion

    //region Imaging
    $services->set('bytes_avatar.image', Image::class)
        ->args([
            service('cache.app'),
            service('http_client'),
            true,
            '',
            0
        ])
        ->lazy()
        ->alias(Image::class, 'bytes_avatar.image')
        ->public();

    $services->set('bytes_avatar.cache', Cache::class)
        ->args([
            service('liip_imagine.cache.manager'),
            service('liip_imagine.filter.manager'),
            service('liip_imagine.data.manager'),
        ])
        ->lazy()
        ->alias(Cache::class, 'bytes_avatar.cache')
        ->public();
    //endregion

    //region Subscribers
    $services->set('bytes_avatar.subscriber.resolve_cache', ResolveCacheSubscriber::class)
        ->tag('kernel.event_subscriber');
    //endregion
};