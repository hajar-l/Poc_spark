<?php

namespace App\Service;

use App\Entity\BannedIp;
use App\Entity\Domain;
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
        // Separate IP and port
        $parts = explode(':', $ip);
        $ipPart = $parts[0];

        // Check IP
        if (filter_var($ipPart, FILTER_VALIDATE_IP) === false) {
            return false;
        }
        // Check port range
        if (count($parts) >= 2) {
            $portPart = $parts[1];
            if (str_contains($portPart, '-')) {
                // Port range specified
                list($minPort, $maxPort) = explode('-', $portPart);
                if ($minPort < 1 || $maxPort > 65535 || $minPort > $maxPort) {
                    return false;
                }
            } else {
                // Single port specified
                $port = (int)$portPart;
                if ($port < 1 || $port > 65535) {
                    return false;
                }
            }
        }

        return true;
    }

    public function create(array $domains, string $email, array $ips, array $bannedIps): Perimeter
    {
        $entityManager = $this->doctrine->getManager();

        if (!isset($domains) || !isset($email) || !isset($ips) || !isset($bannedIps)) {
            throw new InvalidArgumentException('domain names, email, ips or bannedIps cannot be empty');
        }
        if (!$this->isValidEmail($email)) {
            throw new InvalidArgumentException("Invalid email.");
        }

        $perimeter = new Perimeter();
        $perimeter->setContactMail($email);
        $perimeter->setCreatedAt(new DateTime());

        foreach ($ips as $ipAddress) {
            if (!is_string($ipAddress)) {
                throw new InvalidArgumentException("ip must be a string.");
            }
            // Check if the IP address contains a port range
            if (str_contains($ipAddress, ":")) {
                $parts = explode(':', $ipAddress);
                $ip = $parts[0];
                $portRange = null;
                if (str_contains($parts[1], '-')) {
                    $portRange = $parts[1];
                }
                if ($portRange) {
                    [$start, $end] = explode("-", $portRange);
                    // Add each IP address with the corresponding port to the database
                    for ($i = $start; $i <= $end; $i++) {
                        $ipWithPort = $ip . ":" . $i;
                        if (!$this->isValidIp($ipWithPort)) {
                            throw new InvalidArgumentException("Invalid ip " . $ipWithPort);
                        }
                        $ipObj = new Ip();
                        $ipObj->setIpAddress($ipWithPort);
                        $perimeter->addIp($ipObj);
                    }
                } else {
                    // Single port specified
                    if (!$this->isValidIp($ip.':' . $parts[1])) {
                        throw new InvalidArgumentException("Invalid ip" . $ip . ':' . $parts[1]);
                    }
                    $ipObj = new Ip();
                    $ipObj->setIpAddress($ip .':' . $parts[1]);
                    $perimeter->addIp($ipObj);
                }
            } else {
                // Add single IP address to the database
                if (!$this->isValidIp($ipAddress)) {
                    throw new InvalidArgumentException("Invalid ip " . $ipAddress);
                }
                $ipObj = new Ip();
                $ipObj->setIpAddress($ipAddress);
                $perimeter->addIp($ipObj);
            }
        }

        foreach ($bannedIps as $ipAddress) {
            if (!is_string($ipAddress)) {
                throw new InvalidArgumentException("ip must be a string.");
            }
            // Check if the IP address contains a port range
            if (str_contains($ipAddress, ":")) {
                $parts = explode(':', $ipAddress);
                $ip = $parts[0];
                $portRange = null;
                if (str_contains($parts[1], '-')) {
                    $portRange = $parts[1];
                }
                if ($portRange) {
                    [$start, $end] = explode("-", $portRange);
                    // Add each IP address with the corresponding port to the database
                    for ($i = $start; $i <= $end; $i++) {
                        $ipWithPort = $ip . ":" . $i;
                        if (!$this->isValidIp($ipWithPort)) {
                            throw new InvalidArgumentException("Invalid ip " . $ipWithPort);
                        }
                        $ipObj = new BannedIp();
                        $ipObj->setIpAddress($ipWithPort);
                        $perimeter->addBannedIp($ipObj);
                    }
                } else {
                    // Single port specified
                    if (!$this->isValidIp($ip.':' . $parts[1])) {
                        throw new InvalidArgumentException("Invalid ip" . $ip . ':' . $parts[1]);
                    }
                    $ipObj = new BannedIp();
                    $ipObj->setIpAddress($ip .':' . $parts[1]);
                    $perimeter->addBannedIp($ipObj);
                }
            } else {
                // Add single IP address to the database
                if (!$this->isValidIp($ipAddress)) {
                    throw new InvalidArgumentException("Invalid ip " . $ipAddress);
                }
                $ipObj = new BannedIp();
                $ipObj->setIpAddress($ipAddress);
                $perimeter->addBannedIp($ipObj);
            }
        }

        foreach ($domains as $domain) {
            if (!$this->isValidDomainName($domain) || !is_string($domain)) {
                throw new InvalidArgumentException("Invalid domain name " . $domain);
            }
            $d = new Domain();
            $d->setDomainName($domain);

            $perimeter->addDomain($d);
        }

        $entityManager->persist($perimeter);
        $entityManager->flush();

        return $perimeter;
    }


}