<?php
// src/Controller/Index.php
namespace App\Controller;

use App\Tool\Conf;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Index extends AbstractController
{
  /**
    * @Route("/accueil", name="homepage")
    */
    
    public function display()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('accueil/content-main.html.twig',
                ['app_config'=>Conf::appConfig,
        ]) ;
    }
}