<?php

namespace App\DataTransferObject;

use App\Enum\ReactionTypeEnum;

final readonly class ReactionDataDto
{
    public function __construct(
            public ReactionTypeEnum $type,
            public int              $count
    )
    {
    }
}