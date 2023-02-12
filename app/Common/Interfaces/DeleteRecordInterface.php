<?php

declare(strict_types=1);

namespace App\Common\Interfaces;

interface DeleteRecordInterface
{
    public function one(int $id): void;
}
