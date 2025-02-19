<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface {

    public function findOneById(int $id): ?User;

    public function findOneByUuid(string $uuid): ?User;

    public function findOneByUsername(string $username): ?User;

    public function persist(User $user): void;

    public function remove(User $user): void;
}