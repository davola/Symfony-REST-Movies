<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class JwtDecoratorFactory implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $openApi
            ->getPaths()
            ->addPath('/api/login_check', new PathItem(null, null, null, null, null, new Operation(
                    'GET',
                    ['Login'],
                    [
                        Response::HTTP_OK => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'token' => [
                                                'type' => 'string',
                                                'example' => 'some-JWT-token-value',
                                            ]
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'Retrieves a working JWT token.',
                    'Retrieves a JWT token',
                    null,
                    [],
                    new RequestBody(
                        'Generate new JWT Token',
                        new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'username' => [
                                        'type' => 'string',
                                        'example' => 'user1@example.com',
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                        'example' => 'user1',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                    ),
                ),
            ));

        return $openApi;
    }
}
