<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController
{
    /**
     * @Route("/ola")
     */
    public function helloWorldAction(Request $request) : Response
    {
        $pathInfo = $request->getPathInfo();
        $query = $request->query->all();
        return new JsonResponse(["Message" => "Olá mundo", "PathInfo" => $pathInfo, "parametro" => $query]);
    }
}

?>