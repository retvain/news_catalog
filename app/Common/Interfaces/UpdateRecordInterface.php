<?php

declare(strict_types=1);

namespace App\Common\Interfaces;

interface UpdateRecordInterface
{
    public function one(array $data, int $id): array;
}
