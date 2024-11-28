<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'file_name_pattern' => '*.twig',
        'form_themes' => [
            'bootstrap_5_layout.html.twig',
        ],
        'globals' => [
            'turnstile_site_key' => '%env(CLOUDFLARE_TURNSTILE_PUBLIC_KEY)%',
        ],
    ]);
    if ($containerConfigurator->env() === 'test') {
        $containerConfigurator->extension('twig', [
            'strict_variables' => true,
        ]);
    }
};
