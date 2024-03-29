<?php


namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bytes\AvatarBundle\Avatar\AvatarChain;
use Bytes\AvatarBundle\Avatar\Avatars;
use Bytes\AvatarBundle\Avatar\Gravatar;
use Bytes\AvatarBundle\Avatar\Multiavatar;
use Bytes\AvatarBundle\Controller\AvatarSelect2ApiController;
use Bytes\AvatarBundle\Controller\GravatarApiController;
use Bytes\AvatarBundle\Controller\Image;
use Bytes\AvatarBundle\Controller\MultiAvatarApiController;
use Bytes\AvatarBundle\EventListener\ResolveCacheSubscriber;
use Bytes\AvatarBundle\Imaging\Cache;
use Bytes\AvatarBundle\Maker\MakeLiipAvatarConfig;
use Bytes\AvatarBundle\Maker\MakeLiipFilterEnum;

/**
 * @param ContainerConfigurator $container
 */
return static function (ContainerConfigurator $container) {

    $services = $container->services();

    //region Controllers
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
            '' // $config['select2_filter']
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

    //region Maker
    $services->set('bytes_avatar.command.make_liip_avatar_config', MakeLiipAvatarConfig::class)
        ->args([
            service('router.default'), // Symfony\Component\Routing\Generator\UrlGeneratorInterface
            service('debug.validator'), // Symfony\Component\Validator\Validator\ValidatorInterface
            param('kernel.project_dir'),
        ])
        ->tag('maker.command');

    $services->set('bytes_avatar.command.make_liip_filter_enum', MakeLiipFilterEnum::class)
        ->args([
            service('bytes_avatar.cache'),
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
            true,
            '',
            0,
            true,
            '',
            0,
            0,
            0,
            0,
        ])
        ->call('setClient', [service('http_client')])
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
    if (interface_exists(\Symfony\Component\Messenger\Handler\MessageHandlerInterface::class)) {
        $services->set('bytes_avatar.subscriber.resolve_cache', ResolveCacheSubscriber::class)
            ->args([
                service('liip_imagine.filter.manager'),
                service('liip_imagine.service.filter'),
                service('event_dispatcher'),
            ])
            ->tag('kernel.event_subscriber')
            ->tag('messenger.message_handler');
    }
    
    //endregion
};