<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EntityTreat
{
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): self {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }
}