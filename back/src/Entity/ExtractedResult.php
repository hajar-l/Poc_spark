<?php

namespace App\Entity;

use App\Repository\ExtractedResultsRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: ExtractedResultsRepository::class)]
class ExtractedResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $value;

    #[ORM\ManyToOne(targetEntity :"App\Entity\Vulnerability", inversedBy: "extracted_results")]
    #[ORM\JoinColumn(nullable: false)]
    private Vulnerability $vulnerability;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
