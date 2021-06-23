<?php


namespace Bytes\AvatarBundle\Resources\config;


use Bytes\AvatarBundle\Controller\MultiAvatarApiController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @param RoutingConfigurator $routes
 */
return function (RoutingConfigurator $routes) {
    $routes->add('bytes_avatarbundle_multiavatar', '/multi/{id}/avatar.png')
        ->controller([MultiAvatarApiController::class, 'multiAvatarPngAction']);
};