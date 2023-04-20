<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\ManyToOne(targetEntity :"App\Entity\Vulnerability", inversedBy: "tags")]
    #[ORM\JoinColumn(nullable: false)]
    private Vulnerability $vulnerability;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Vulnerability
     */
    public function getVulnerability(): Vulnerability
    {
        return $this->vulnerability;
    }

    /**
     * @param Vulnerability $vulnerability
     */
    public function setVulnerability(Vulnerability $vulnerability): void
    {
        $this->vulnerability = $vulnerability;
    }


}
