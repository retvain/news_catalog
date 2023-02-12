<?php

declare(strict_types=1);

namespace App\Common\Interfaces;

interface ReadRecordInterface
{
    public function one(string $id): array;
    public function all(array $params): array;
}
