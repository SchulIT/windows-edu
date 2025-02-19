<?php

namespace App\Kivuto\Client;

use App\Kivuto\User\DataResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractKivutoClient implements KivutoClientInterface {

    public function __construct(protected readonly string $account,
                                protected readonly string $endpoint,
                                protected readonly string $secretKey,
                                protected readonly DataResolverInterface $dataResolver,
                                protected readonly RequestStack $requestStack) { }

    protected function getRequestData(): array {
        return [
            'account' => $this->account,
            'username' => $this->dataResolver->getUsername(),
            'key' => $this->secretKey,
            'last_name' => mb_substr($this->dataResolver->getLastname(), 0, 50),
            'first_name' => mb_substr($this->dataResolver->getFirstname(), 0, 50),
            'shopper_ip' => $this->requestStack->getMainRequest()->getClientIp(),
            'academic_statuses' => $this->dataResolver->getAcademicStatus(),
            'email' => mb_substr($this->dataResolver->getEmail(), 0, 100)
        ];
    }

    public abstract function getRedirectUrl(): string;
}