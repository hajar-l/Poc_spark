<?php

namespace App\Service;

use App\Entity\Ip;
use App\Entity\Perimeter;
use App\Repository\PerimeterRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

class PerimeterService
{

    private PerimeterRepository $perimeterRepository;

    public function __construct(private readonly ManagerRegistry $doctrine, PerimeterRepository $perimeterRepository)
    {
        $this->perimeterRepository = $perimeterRepository;
    }

    public function isValidEmail(string $email): bool
    {
        $regex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
        return preg_match($regex, $email) === 1;
    }

    public function isValidDomainName(string $domain): bool
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain)
            && preg_match("/^.{1,253}$/", $domain)
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain));
    }
    function isValidIp(string $ip): bool {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public function create(?string $domain, ?string $email,  array $ips): Perimeter
    {
        $entityManager = $this->doctrine->getManager();

        if(!isset($domain) || !isset($email) || !isset($ips))
            throw new InvalidArgumentException('domain name, email or ips cannot be empty');
        if (!$this->isValidDomainName($domain) || !is_string($domain))
            throw new InvalidArgumentException("Invalid domain name.");
        if (!$this->isValidEmail($email))
            throw new InvalidArgumentException("Invalid email.");

        $perimeter = new Perimeter();
        $perimeter->setDomainName($domain);
        $perimeter->setContactMail($email);
        $perimeter->setCreatedAt(new DateTime());

        foreach ($ips as $ipAddress) {
            if (!is_string($ipAddress))
                throw new InvalidArgumentException("ip must be a string.");
            if (!$this->isValidIp($ipAddress))
                throw new InvalidArgumentException("Invalid ip " . $ipAddress);
            $ip = new Ip();
            $ip->setIpAddress($ipAddress);

            $perimeter->addIp($ip);

        }

        $entityManager->persist($perimeter);
        $entityManager->flush();


        return $perimeter;
    }

}