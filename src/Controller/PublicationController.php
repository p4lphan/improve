<?php

namespace App\Controller;

use App\Tool\Conf;
use App\Form\FormPublication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Entity\TypePublication;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
/**
 * @IsGranted("ROLE_USER")
 */

class PublicationController extends AbstractController
{
     
     
    /**
     * @Route("/publication/show/{id<\d+>}", name="publication_show")
     * @ParamConverter("id", class="App\Entity\Publication")
     */
    
    public function show(Publication $publication ){
        
       if($publication){
        return $this->render('publication/publication-single.html.twig', [
            'app_config'        =>  Conf::appConfig,
            'publication'       =>  $publication,
            'edit'              =>  0,
            'typePublication'   =>  $publication->getIdType()->getName(),                   
        ]);
       }
       else{
           return $this->render('error404.html.twig',[
            'app_config'        =>  Conf::appConfig,
        ]);
       }
    }
    
     /**
     * @Route("/configuration/publication/see/{id<\d+>}", name="publication_see")
     * @ParamConverter("id", class="App\Entity\Publication")
     */
    
    public function edit(Publication $publication ){
       
        if($publication){
            return $this->render('publication/publication-single.html.twig', [
                'app_config'        =>  Conf::appConfig,
                'publication'       =>  $publication,
                'edit'              =>  1,
                'typePublication'   =>  $publication->getIdType()->getName(),
            ]);
        }
        else{
            return $this->render('error404.html.twig',[
             'app_config'        =>  Conf::appConfig,
         ]);
        }
    }
    
    
    /**
     * @Route("/configuration/publication/config", name="publication_config_all")
     */
    
    public function configAll(){
       
        $publications = $this->getDoctrine()
        ->getRepository(Publication::class)
        ->findAll();
        
        return $this->render('publication/publication-config-all.html.twig',[
            'app_config'               =>  Conf::appConfig,
            'options'                  =>  [1,1,1],   
            'entities'                 =>  $publications,
            'title'                    =>  'Gestion des publications',
            'entitiesPlural'           =>  'Mes publications',            
            'entitiesPluralDescription'=>  'Ici se trouvent toutes mes publications',  
            'className'                =>  'Publication',
            'pageName'                 =>  'publication',                       
            'friendlyClassName'        =>  'la publication',
            'bannerMessage'            =>  'Pour voir les publications, cliquer ici',
            'addControllerPath'        =>  'publication_add',
            'headers'=>['Publication',
                       'Catégorie',
                       'Status',
                       'Utilisateur',
                       'Date de création',
                       '', ],             
        ]);
    }
    
    /**
     * @Route("/publications", name="publication_show_all")
     */
    
    public function showAll(){
        
        $typesPublications = $this->getDoctrine()
        ->getRepository(TypePublication::class)
        ->findBy(['valid'=>1]);
        
         return $this->render('publication/publication-show-byCategorie.html.twig',[
             'app_config'           => Conf::appConfig,
             'typePublications'     => $typesPublications,
        ]);
    }  
    
    /**
     * @Route("/configuration/publication/success-add", name="publication_success_add")
     */
    
     public function displayAddSuccess()
    {
               
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,
        'successTitle'         =>  'Création de la publication réussie !',
        'successPath'          =>  'publication_config_all',
        'entityPath'           =>  'publication_show_all',
        'seeSuccessEntity'     =>  'Mes publications',
        ]);
    }
    
    /**
     * @Route("/configuration/publication/success-export", name="publication_success_export")
     */
    
     public function displayExportSuccess(Request $request)
    {
         $response->setCallback('handleResponse');
         var_dump($response);
         /*
        return $this->render('generic/generic-export.html.twig', [
        'app_config'           =>  Conf::appConfig,
        'successTitle'         =>  'Export des publications réussie !',
        'seeSuccessEntity'     =>  'Télécharger',
        ]);
          * 
          */
    }
    
    /**
     * @Route("/configuration/publication/success-update", name="publication_success_update")
     */
    
     public function displayUpdateSuccess()
    {
                
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,       
        'successTitle'         =>  'Modification de la publication réussie !',
        'successPath'          =>  'publication_config_all',
        'entityPath'           =>  'publication_show_all',
        'seeSuccessEntity'     =>  'Mes publications',
        ]);
    }
    
    /**
     * @Route("/configuration/publication/add", name="publication_add")
     */
    
     public function formCreate(Request $request)
    {
        
        $typePublications = $this->getDoctrine()
        ->getRepository(TypePublication::class)
        ->findAll();
               
        $publication = new Publication();
        $publication->setName('');
        $publication->setContent('' );
        $publication->setValid(true);
        $publication->setFilepath('');
        $publication->setAuthor('Auteur inconnu');
        $publication->setCreateDate(new \DateTime('now'));
        $publication->setIdType($typePublications[0]);
        $publication->setUser($this->getUser());
        
        $form = $this->createForm(FormPublication::class,$publication)
            ->add('enregistrer' , SubmitType::class, ['label' => 'Créer une publication','attr'=>['style'=>'margin-top:2em;']]);
        
        $form->handleRequest($request);        
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $publication    =   $form->getData();
            $entityManager  =   $this->getDoctrine()->getManager();
            $entityManager  ->  persist($publication);
            $entityManager  ->  flush();
            
            return $this->redirectToRoute('publication_success_add');
        }
       
        return $this->render('publication/publication-form.html.twig', [
            'app_config'           =>  Conf::appConfig,           
            'form'                 =>  $form->createView(),
            'createName'           =>  "Création d'une nouvelle publication",
        ]);
    }
    
    /**
     * @Route("/configuration/publication/edit/{id<\d+>}", name="publication_update")
     * @ParamConverter("id",class="App\Entity\Publication")
     */
    
     public function formUpdate(Request $request,Publication $publication)
    {
         
        if($publication){
        
            $form = $this->createForm(FormPublication::class,$publication)
                ->add('enregistrer' , SubmitType::class, ['label' => 'Enregistrer','attr'=>['style'=>'margin-top:2em;']]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $publication    =   $form->getData();
                $entityManager  =   $this->getDoctrine()->getManager();
                $entityManager  ->  flush();
                
                return $this->redirectToRoute('publication_success_update');
            }

            return $this->render('publication/publication-form.html.twig', [
                'app_config'           =>  Conf::appConfig,               
                'form'                 =>  $form->createView(),
                'createName'           =>  "Modification d'une publication",
            ]);
        }
    } 
    
    /**
     * @Route("/configuration/publication/delete/{id<\d+>}", name="publication_delete")
     * @ParamConverter("id", class="App\Entity\Publication")
     */
    
    public function deletePublication(Publication $publication)
    {
        $hasAccess = $this->isGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if($publication){
        
            $entityManager  =   $this->getDoctrine()->getManager();
            $entityManager  ->  remove($publication);
            $entityManager  ->  flush();

            return $this->redirectToRoute('publication_success_delete');
        }
    } 
    
    /**
     * @Route("/configuration/publication/export", name="publication_export")
     */
    
    public function exportPublicationold(Request $request):Response
    {
        $publications = $this->getUser()->getPublications();        
        
        $encoder = new JsonEncoder();
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);

        $cbTimestampToDatetime = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceOf \Datetime ? $innerObject->format('j-m-Y H:i:s'):"";
        };

        $normalizer->setCallbacks(['createDate' => $cbTimestampToDatetime]);

        $serializer = new Serializer([$normalizer],[$encoder]);

        $arrayPublication=array();

        foreach($publications as $publication){
            $normalizedPublication  =   $serializer->normalize($publication, null, ['groups' => 'group1','group2']);
            $arrayPublication[]     =   $normalizedPublication;
        }            

        $response = new Response();
        $response->setContent(json_encode($arrayPublication));
        $response->headers->set('Content-Type', 'application/json');
        
        var_dump($request);
        return $this->redirectToRoute('publication_import');
    } 
    
    /**
     * @Route("/configuration/publication/export", name="publication_export")
     */
    public function exportPublication(Request $request)
    {
        $publications = $this->getUser()->getPublications();        
        
        $form = $this->createFormBuilder()
                ->add('export'   ,   Submittype::class, ['label'=>'Exporter'])
                ->getForm();
                    
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            
            $encoder = new JsonEncoder();
            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            $normalizer = new ObjectNormalizer($classMetadataFactory);
            
            $cbTimestampToDatetime = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
                return $innerObject instanceOf \Datetime ? $innerObject->format('d-m-Y H:i:s'):"";
            };
            
            $normalizer->setCallbacks(['createDate' => $cbTimestampToDatetime]);

            $serializer = new Serializer([$normalizer],[$encoder]);

            $arrayPublication=array();
          
            foreach($publications as $publication){
                $normalizedPublication  =   $serializer->normalize($publication, null, ['groups' => 'group1','group2']);
                $arrayPublication[]     =   $normalizedPublication;
            }            
            
            $response = new Response();
            $response->setContent(json_encode($arrayPublication));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
            
            // var_dump($response->getContent());
            // //return $this->redirectToRoute('publication_import',[$response]);
            //  $url = $this->generateUrl('publication_import',[],UrlGeneratorInterface::ABSOLUTE_URL);
            // return new RedirectResponse($url);
        }
       return $this->render('publication/publication-form.html.twig', [
                'app_config'           =>  Conf::appConfig,               
                'form'                 =>  $form->createView(),
                'createName'           =>  "Export des publications",
       ]);
    } 
    
}