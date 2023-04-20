<?php

namespace App\Controller;

use App\Entity\Perimeter;
use App\Repository\PerimeterRepository;
use App\Service\PerimeterService;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            $data[] = [
                'id' => $perimeter->getId()->toString(),
                'domain_name' => $perimeter->getDomainName(),
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
    public function show(Perimeter $perimeter): JsonResponse
    {
        $data = [
            'id' => $perimeter->getId()->toString(),
            'domain_name' => $perimeter->getDomainName(),
            'contact_mail' => $perimeter->getContactMail(),
            'created_at' => $perimeter->getCreatedAt()?->format(DateTimeInterface::ATOM),
        ];

        return new JsonResponse($data);
    }

    #[Route('/perimeter', name: 'perimeter_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            if (!is_array($data['ips']))
                throw new InvalidArgumentException('ips field must be an array');
            $perimeter = $this->perimeterService->create($data['domainName'], $data['contactEmail'], $data['ips']);
        }
        catch(Exception $e) {
            return new JsonResponse($e->getMessage());
        }

        return new JsonResponse($perimeter);
    }
}