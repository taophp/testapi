<?php

namespace App\Swagger;

use ApiPlatform\Symfony\Bundle\SwaggerUi\SwaggerUiProvider as BaseSwaggerUiProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class SwaggerUiProvider implements ProviderInterface
{
    public const DISABLE_SWAGGER = 'disable_swagger';
    private $decorated;
    public function __construct(BaseSwaggerUiProvider $decorated)
    {
        $this->decorated = $decorated;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        dump('Here');
        return empty($operation->getDefaults()[self::DISABLE_SWAGGER])
            ? null
            : $this->decorated->provide($operation, $uriVariables, $context);
    }
}
