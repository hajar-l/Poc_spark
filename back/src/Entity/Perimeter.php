<?php

namespace App\Entity;

use App\Repository\PerimeterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
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
    private ?string $contact_mail;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\OneToMany(mappedBy: "perimeter", targetEntity: "App\Entity\Ip", cascade:["persist"])]
    private $ips;

    #[ORM\OneToMany(mappedBy: "perimeter", targetEntity: "App\Entity\Domain", cascade:["persist"])]
    private $domains;

    #[ORM\OneToMany(mappedBy: "perimeter", targetEntity: "App\Entity\Vulnerability", cascade:["persist"])]
    private $vulnerabilites;

    #[ORM\OneToMany(mappedBy: "perimeter", targetEntity: "App\Entity\BannedIp", cascade:["persist"])]
    private $bannedIps;

    public function __construct()
    {
        $this->ips = new ArrayCollection();
        $this->domains = new ArrayCollection();
        $this->vulnerabilites = new ArrayCollection();
        $this->bannedIps = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getDomains(): PersistentCollection
    {
        return $this->domains;
    }

    /**
     * @param ArrayCollection $domains
     */


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

    public function getIps(): PersistentCollection
    {
        return $this->ips;
    }

    public function setIps(PersistentCollection $ips): void
    {
        $this->ips = $ips;
    }


    public function getVulnerabilites(): PersistentCollection
    {
        return $this->vulnerabilites;
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

    public function addDomain(Domain $domain): self
    {
        if (!$this->domains->contains($domain)) {
            $this->domains[] = $domain;
            $domain->setPerimeter($this);
        }

        return $this;
    }

    public function removeDomain(Domain $domain): self
    {
        if ($this->domains->removeElement($domain)) {
            if ($domain->getPerimeter() === $this) {
                $domain->setPerimeter(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBannedIps(): PersistentCollection
    {
        return $this->bannedIps;
    }

    /**
     * @param ArrayCollection $bannedIps
     */
    public function setBannedIps(PersistentCollection $bannedIps): void
    {
        $this->bannedIps = $bannedIps;
    }

    public function addBannedIp(BannedIp $ip): self
    {
        if (!$this->bannedIps->contains($ip)) {
            $this->bannedIps[] = $ip;
            $ip->setPerimeter($this);
        }

        return $this;
    }

    public function removeBannedIp(BannedIp $ip): self
    {
        if ($this->bannedIps->removeElement($ip)) {
            if ($ip->getPerimeter() === $this) {
                $ip->setPerimeter(null);
            }
        }

        return $this;
    }

}
