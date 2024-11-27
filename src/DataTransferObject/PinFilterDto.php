<?php

namespace App\DataTransferObject;

use App\Enum\PinTypeEnum;

final class PinFilterDto
{
    private null|string $keyword = null;
    private null|PinTypeEnum $pinTypeEnum = null;

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): void
    {
        $this->keyword = $keyword;
    }

    public function getPinTypeEnum(): ?PinTypeEnum
    {
        return $this->pinTypeEnum;
    }

    public function setPinTypeEnum(?PinTypeEnum $pinTypeEnum): void
    {
        $this->pinTypeEnum = $pinTypeEnum;
    }

}