<?php

namespace App\Kivuto\Client;

interface KivutoClientInterface {
    public function getRedirectUrl(): string;
}