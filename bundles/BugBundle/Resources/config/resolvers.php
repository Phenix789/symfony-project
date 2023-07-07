<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bundle\Bug\Resolver\DecoratorResolver;
use Bundle\Bug\Resolver\HighResolver;
use Bundle\Bug\Resolver\LowResolver;
use Bundle\Bug\Resolver\NormalResolver;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $services
        ->defaults()
        ->autoconfigure()
        ->autowire()
        ->private();

    $services
        ->set(DecoratorResolver::class)
        ->decorate('argument_resolver', priority: 100) // Before Traceable
    ;

    $tag = 'controller.argument_value_resolver';
    $services
        ->set(LowResolver::class)->tag($tag, ['priority' => -70])/*->autoconfigure(false)*/ // @fix With ->autoconfigure(false) priority is correct
        ->set(NormalResolver::class)->tag($tag)
        ->set(HighResolver::class)->tag($tag, ['priority' => 70]) // With positive priority, no trouble
    ;
};
