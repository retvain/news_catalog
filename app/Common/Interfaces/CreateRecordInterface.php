<?php

declare(strict_types=1);

namespace App\Common\Interfaces;

interface CreateRecordInterface
{
    public function one(array $data): array;
}
