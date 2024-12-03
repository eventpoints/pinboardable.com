<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Enum\ReactionTypeEnum;

final readonly class ReactionDataDto
{
    public function __construct(
        public ReactionTypeEnum $type,
        public int $count
    )
    {
    }
}
