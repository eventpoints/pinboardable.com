<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', [
            'secret' => '%env(APP_SECRET)%',
            'session' => true,
            'http_client' => [
                    'scoped_clients' => [
                            'cloudflare.turnstile.client' => [
                                    'base_uri' => 'https://challenges.cloudflare.com',
                                    'headers' => [
                                            'Accept' => 'application/json',
                                    ],
                            ],
                    ],
            ],
    ]);
    if ($containerConfigurator->env() === 'test') {
        $containerConfigurator->extension('framework', [
                'test' => true,
                'session' => [
                        'storage_factory_id' => 'session.storage.factory.mock_file',
                ],
        ]);
    }
};
