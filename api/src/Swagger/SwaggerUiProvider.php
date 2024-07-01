<?php
namespace App\Swagger;

use ApiPlatform\Symfony\Bundle\SwaggerUi\SwaggerUiProvider as BaseSwaggerUiProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Psr\Log\LoggerInterface;

class SwaggerUiProvider implements ProviderInterface
{
    private $decorated;
    private $logger;

    public function __construct(BaseSwaggerUiProvider $decorated, LoggerInterface $logger)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $result = $this->decorated->provide($operation, $uriVariables, $context);

        $this->logger->info('Swagger UI operation provided.', ['operation' => $operation]);

        return $result;
    }
}
