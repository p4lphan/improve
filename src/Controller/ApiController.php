<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    
    /**
     * @Route("/configuration/publication/import", name="publication_import")
     */
    
    public function importPublication(Request $request)
    {
        /*
        $url = $this->generateUrl('publication_export',[],UrlGeneratorInterface::ABSOLUTE_URL);
        $json = file_get_contents($url);
        $obj = json_decode($json,true);
        */

        return $this->render('publication/publication-deserialize.html.twig');
    } 
}
