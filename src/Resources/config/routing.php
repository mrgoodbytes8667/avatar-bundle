<?php


namespace Bytes\AvatarBundle\Resources\config;


use Bytes\AvatarBundle\Controller\AvatarApiController;
use Bytes\AvatarBundle\Controller\AvatarSelect2ApiController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @param RoutingConfigurator $routes
 */
return function (RoutingConfigurator $routes) {
    $routes->add('bytes_avatarbundle_gravatar', '/gravatar/{id}/{!size}/avatar.png')
        ->controller([AvatarApiController::class, 'gravatar']);


    $routes->add('bytes_avatarbundle_multiavatar', '/multi/{id}/avatar.png')
        ->controller([AvatarApiController::class, 'multiAvatar']);


    $routes->add('bytes_avatarbundle_select2', '/select2')
        ->controller([AvatarSelect2ApiController::class, 'select2'])
        ->format('json');
};
