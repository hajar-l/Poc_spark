<?php

namespace App\Controller;

use App\Service\RequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{

    private RequestService $requestService;


    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    #[Route('/test', name: 'app_test', methods: ['GET'])]
    public function test(): Response
    {
        echo "salut";
        return new Response("p");
    }

    #[Route('/request/{domain}', name: 'app_request', methods: ['GET'])]
    public function request(string $domain): Response
    {
        try{
            $output = $this->requestService->run($domain);
        }
        catch(Exception $e){
            return new Response($e->getMessage());
        }

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
