<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;

final class PinFilterDto
{
    private null|string $keyword = null;

    private null|string $country = null;

    /**
     * @var ArrayCollection<int, Tag>
     */
    private ArrayCollection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): void
    {
        $this->keyword = $keyword;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return ArrayCollection<int, Tag>
     */
    public function getTags(): ArrayCollection
    {
        return $this->tags;
    }

    /**
     * @param ArrayCollection<int, Tag> $tags
     */
    public function setTags(ArrayCollection $tags): void
    {
        $this->tags = $tags;
    }
}
