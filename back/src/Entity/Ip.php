<?php

namespace App\Entity;

use App\Repository\IpRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: IpRepository::class)]
class Ip
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class:"Ramsey\Uuid\Doctrine\UuidGenerator")]
    private UuidInterface $id;

    #[ORM\Column]
    private ?string $ip_address;

    #[ORM\ManyToOne(targetEntity :"App\Entity\Perimeter", inversedBy: "ips")]
    #[ORM\JoinColumn(nullable: false)]
    private Perimeter $perimeter;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerimeter()
    {
        return $this->perimeter;
    }

    /**
     * @param mixed $perimeter
     */
    public function setPerimeter($perimeter): void
    {
        $this->perimeter = $perimeter;
    }





}
