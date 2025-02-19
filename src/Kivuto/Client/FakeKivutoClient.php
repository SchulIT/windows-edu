<?php

namespace App\Kivuto\Client;

use App\Kivuto\User\DataResolverInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

class FakeKivutoClient extends AbstractKivutoClient {

    public function __construct(#[Autowire(env: 'KIVUTO_ACCOUNT')] string $account,
                                #[Autowire(env: 'KIVUTO_ENDPOINT')] string $endpoint,
                                #[Autowire(env: 'KIVUTO_SECRET_KEY')] string $secretKey,
                                DataResolverInterface $dataResolver,
                                RequestStack $requestStack) {
        parent::__construct($account, $endpoint, $secretKey, $dataResolver, $requestStack);
    }

    public function getRedirectUrl(): string {
        $requestData = $this->getRequestData();
        $url = sprintf('%s?%s', $this->endpoint, http_build_query($requestData));

        return $url;
    }
}