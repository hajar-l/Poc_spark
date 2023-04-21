<?php

namespace App\Entity;

use App\Repository\DomainRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: DomainRepository::class)]
class Domain
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class:"Ramsey\Uuid\Doctrine\UuidGenerator")]
    private UuidInterface $id;

    #[ORM\Column]
    private ?string $domainName;

    #[ORM\ManyToOne(targetEntity :"App\Entity\Perimeter", inversedBy: "domains")]
    #[ORM\JoinColumn(nullable: false)]
    private Perimeter $perimeter;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getDomainName(): ?string
    {
        return $this->domainName;
    }

    /**
     * @param string|null $domainName
     */
    public function setDomainName(?string $domainName): void
    {
        $this->domainName = $domainName;
    }

    /**
     * @return Perimeter
     */
    public function getPerimeter(): Perimeter
    {
        return $this->perimeter;
    }

    /**
     * @param Perimeter $perimeter
     */
    public function setPerimeter(Perimeter $perimeter): void
    {
        $this->perimeter = $perimeter;
    }

}
