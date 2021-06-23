<?php


namespace Bytes\AvatarBundle\Resources\config;


use Bytes\AvatarBundle\Controller\GravatarApiController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @param RoutingConfigurator $routes
 */
return function (RoutingConfigurator $routes) {
    $routes->add('bytes_avatarbundle_gravatar', '/gravatar/{id}/{!size}/avatar.png')
        ->controller([GravatarApiController::class, 'gravatarPngAction']);
};