<?php

namespace App\Controller;

use App\Entity\BannedIp;
use App\Entity\Domain;
use App\Entity\Ip;
use App\Entity\Perimeter;
use App\Repository\PerimeterRepository;
use App\Service\PerimeterService;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\PersistentCollection;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
class PerimeterController extends AbstractController
{



    private PerimeterService $perimeterService;


    public function __construct(PerimeterService $perimeterService)
    {
        $this->perimeterService = $perimeterService;
    }

    #[Route('/perimeter', name: 'perimeter_index', methods: ['GET'])]
    public function index(Request $request, PerimeterRepository $perimeterRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        if ($page <= 0) {
            return new JsonResponse(['error' => 'page number must be positive'], Response::HTTP_BAD_REQUEST);
        }
        if ($limit <= 0) {
            return new JsonResponse(['error' => 'limit must be positive'], Response::HTTP_BAD_REQUEST);
        }

        $paginator = $perimeterRepository->getPerimetersPaginator($page, $limit);
        $perimeters = $paginator->getIterator();

        $data = [];
        foreach ($perimeters as $perimeter) {
            $ips = [];
            foreach ($perimeter->getIps() as $ip) {
                $ips[] = $ip->getIpAddress();
            }

            $domains = [];
            foreach ($perimeter->getDomains() as $domain) {
                $domains[] = $domain->getDomainName();
                }

            $bannedIps = [];
            foreach ($perimeter->getBannedIps() as $ip) {
                $bannedIps[] = $ip->getIpAddress();
            }

            $data[] = [
                'id' => $perimeter->getId()->toString(),
                'domains' => $domains,
                'ips' => $ips,
                'bannedIps' => $bannedIps,
                'contact_mail' => $perimeter->getContactMail(),
                'created_at' => $perimeter->getCreatedAt()?->format(DateTimeInterface::ATOM),
            ];
        }

        return new JsonResponse([
            'items' => $data,
            'total_count' => $paginator->count(),
            'page_count' => ceil($paginator->count() / $limit),
            'current_page' => $page,
        ]);
    }




    #[Route('/perimeter/{id}', name: 'perimeter_show', methods: ['GET'])]
    public function show(Request $request,Perimeter $perimeter = null): JsonResponse
    {
        if ($perimeter === null) {
            return new JsonResponse(['error' => 'Perimeter not found'], Response::HTTP_NOT_FOUND);
        }
        $ips = [];
        foreach ($perimeter->getIps() as $ip) {
            $ips[] = $ip->getIpAddress();
        }

        $domains = [];
        foreach ($perimeter->getDomains() as $domain) {
            $domains[] = $domain->getDomainName();
        }

        $bannedIps = [];
        foreach ($perimeter->getBannedIps() as $ip) {
            $bannedIps[] = $ip->getIpAddress();
        }

        $data = [
            'id' => $perimeter->getId()->toString(),
            'domains' => $domains,
            'ips' => $ips,
            'bannedIps' => $bannedIps,
            'contact_mail' => $perimeter->getContactMail(),
            'created_at' => $perimeter->getCreatedAt()?->format(DateTimeInterface::ATOM),
        ];

        return new JsonResponse($data);
    }

    #[Route('/perimeter/{id}', name: 'perimeter_update', methods: ['PATCH'])]
    public function update(Request $request, Perimeter $perimeter = null,EntityManagerInterface $entityManager): JsonResponse
    {
        if ($perimeter === null) {
            return new JsonResponse(['error' => 'Perimeter not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Update the fields with the new values from the request data
        if (isset($data['domains']) && is_array($data['domains'])) {
            $perimeter->getDomains()->clear();
            
            foreach ($data['domains'] as $domain) {
                $domainsEntity=new Domain();
                $domainsEntity->setDomainName($domain);
                $perimeter->addDomain($domainsEntity);
            }
        }
        if (isset($data['ips']) && is_array($data['ips'])) {
            $perimeter->getIps()->clear();
            
            foreach ($data['ips'] as $ip) {
                $ipEntity = new Ip();
                $ipEntity->setIpAddress($ip);
                $perimeter->addIp($ipEntity);
            }
        }
        if (isset($data['bannedIps']) && is_array($data['bannedIps'])) {
            $perimeter->getBannedIps()->clear();
            
            foreach ($data['bannedIps'] as $ip) {
                $ipEntity = new BannedIp();
                $ipEntity->setIpAddress($ip);
                $perimeter->addBannedIp($ipEntity);
            }
        }
        if (isset($data['contact_mail'])) {
            $perimeter->setContactMail($data['contact_mail']);
        }
        $entityManager->persist($perimeter);
        $entityManager->flush();
      
        $domains=[];
        foreach ($perimeter->getDomains() as $domain){
            $domains[]=$domain->getDomainName();
        }

        $bannedIps = [];
        foreach ($perimeter->getBannedIps() as $ip) {
            $bannedIps[] = $ip->getIpAddress();
        }
        $ips = [];
        foreach ($perimeter->getIps() as $ip) {
            $ips[] = $ip->getIpAddress();
        }
        // Return the updated data in the response
        $response = [
            'id' => $perimeter->getId()->toString(),
            'domains' => $domains,
            'ips' => $ips,
            'bannedIps' => $bannedIps,
            'contact_mail' => $perimeter->getContactMail(),
            'created_at' => $perimeter->getCreatedAt()?->format(DateTimeInterface::ATOM),
        ];

        return new JsonResponse($response);
    }

    #[Route('/perimeter/{id}', name: 'perimeter_delete', methods: ['DELETE'])]
    public function delete(Perimeter $perimeter = null, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($perimeter === null) {
            return new JsonResponse(['error' => 'Perimeter not found'], Response::HTTP_NOT_FOUND);
        }       
        $entityManager->remove($perimeter);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Perimeter deleted successfully']);
    }

    #[Route('/perimeter', name: 'perimeter_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            if(isset($data['contactEmail']) && isset($data['domainNames']) && isset($data['ips']) && isset($data['bannedIps'])) {
                if (!is_array($data['ips']))
                    throw new InvalidArgumentException('ips field must be an array');
                if (!is_array($data['domainNames']))
                    throw new InvalidArgumentException('domainNames field must be an array');
                if (!is_array($data['bannedIps']))
                    throw new InvalidArgumentException('bannedIps field must be an array');
                $perimeter = $this->perimeterService->create($data['domainNames'], $data['contactEmail'], $data['ips'],
                    $data['bannedIps']);
            } else {
                return new JsonResponse(['error' =>'contactEmail, domainNames, ips or bannedIps have to be defined'], Response::HTTP_BAD_REQUEST);
            }
        }
        catch(Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(['message' =>'Perimeter successfully created'], Response::HTTP_CREATED);
    }
}