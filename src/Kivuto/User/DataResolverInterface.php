<?php

namespace App\Kivuto\User;

interface DataResolverInterface {
    public function getUsername(): string;

    public function getFirstname(): string;

    public function getLastname(): string;

    public function getAcademicStatus(): string;

    public function getEmail(): ?string;
}