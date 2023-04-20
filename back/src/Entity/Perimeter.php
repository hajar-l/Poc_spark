<?php

namespace App\Entity;

use App\Repository\PerimeterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: PerimeterRepository::class)]
class Perimeter
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: 'uuid')]
    #[ORM\CustomIdGenerator(class:"Ramsey\Uuid\Doctrine\UuidGenerator")]
    private UuidInterface $id;

    #[ORM\Column(length: 255)]
    private ?string $domain_name;

    #[ORM\Column(length: 255)]
    private ?string $contact_mail;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

     #[ORM\OneToMany(mappedBy: "perimeter", targetEntity: "App\Entity\Ip", cascade:["persist"])]
    private $ips;


     #[ORM\OneToMany(mappedBy: "perimeter", targetEntity: "App\Entity\Vulnerability")]
    private $vulnerabilites;

    public function __construct()
    {
        $this->ips = new ArrayCollection();
        $this->vulnerabilites = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     */
    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getDomainName(): ?string
    {
        return $this->domain_name;
    }

    /**
     * @param string|null $domain_name
     */
    public function setDomainName(?string $domain_name): void
    {
        $this->domain_name = $domain_name;
    }

    /**
     * @return string|null
     */
    public function getContactMail(): ?string
    {
        return $this->contact_mail;
    }

    /**
     * @param string|null $contact_mail
     */
    public function setContactMail(?string $contact_mail): void
    {
        $this->contact_mail = $contact_mail;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeInterface|null $created_at
     */
    public function setCreatedAt(?\DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getIps(): ArrayCollection
    {
        return $this->ips;
    }

    public function setIps(ArrayCollection $ips): void
    {
        $this->ips = $ips;
    }


    public function getVulnerabilites(): Array
    {
        return $this->vulnerabilites;
    }

    public function setVulnerabilites(Array $vulnerabilites): void
    {
        $this->vulnerabilites = $vulnerabilites;
    }


    public function addIp(Ip $ip): self
    {
        if (!$this->ips->contains($ip)) {
            $this->ips[] = $ip;
            $ip->setPerimeter($this);
        }

        return $this;
    }

    public function removeIp(Ip $ip): self
    {
        if ($this->ips->removeElement($ip)) {
            if ($ip->getPerimeter() === $this) {
                $ip->setPerimeter(null);
            }
        }

        return $this;
    }



}
