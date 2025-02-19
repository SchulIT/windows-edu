<?php

namespace App\Kivuto\Client;

use App\Kivuto\User\DataResolverInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

class KivutoClient extends AbstractKivutoClient {

    public function __construct(#[Autowire(env: 'KIVUTO_ACCOUNT')] string $account,
                                #[Autowire(env: 'KIVUTO_ENDPOINT')] string $endpoint,
                                #[Autowire(env: 'KIVUTO_SECRET_KEY')] string $secretKey,
                                #[Autowire(service: 'eight_points_guzzle.client.kivuto')] private readonly Client $httpClient,
                                DataResolverInterface $dataResolver,
                                RequestStack $requestStack,
                                private readonly LoggerInterface $logger) {
        parent::__construct($account, $endpoint, $secretKey, $dataResolver, $requestStack);
    }

    /**
     * @throws KivutoException
     * @throws GuzzleException
     */
    public function getRedirectUrl(): string {
        $requestData = $this->getRequestData();
        $response = $this->httpClient->post($this->endpoint, [
            'body' => json_encode($requestData),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        if($statusCode === 200) {
            return $content;
        }

        $this->logger
            ->critical(sprintf('Expected status code 200, got %d', $statusCode), [
                'response' => $response->getBody()
            ]);

        throw new KivutoException(
            sprintf('Expected status code 200 (got %d)', $statusCode)
        );
    }
}