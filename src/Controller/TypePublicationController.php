<?php

namespace App\Controller;

use App\Form\FormTypePublication;
use App\Entity\TypePublication;
use App\Tool\Conf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */

class TypePublicationController extends AbstractController
{
    /**
     * @Route("/configuration/type-publication/config", name="type_publication_config_all")
     */
    
    public function configAll(){
        
        $typesPublications = $this->getDoctrine()->getRepository(TypePublication::class)
        ->findAll();
        
        return $this->render('type_publication/type-publication-config-all.html.twig',[
            'app_config'               =>  Conf::appConfig,
            'options'                  =>  [0,1,1],   
            'entities'                 =>  $typesPublications,
            'title'                    =>  'Gestion des catégories de publication',
            'entitiesPlural'           =>  'Mes catégories de publication',            
            'entitiesPluralDescription'=>  'Ici se trouvent toutes les catégorie de publication',  
            'className'                =>  'Typepublication',
            'pageName'                 =>  'type_publication',                       
            'friendlyClassName'        =>  'la catégorie',
            'bannerMessage'            =>  'Pour voir les catégories, cliquer ici',
            'addControllerPath'        =>  'type_publication_add',
            'headers'=>['Catégorie',   
                        'Status',
                       '', ],             
        ]);
    }
    
    /**
     * @Route("/configuration/type-publication/add", name="type_publication_add")
     */
    
     public function formCreate(Request $request)
    {
        
        $typePublication = new TypePublication();
        $typePublication->setName('Nouvelle catégorie');
        $typePublication->setValid(true);
        
        $form = $this->createForm(FormTypePublication::class,$typePublication)
            ->add('enregistrer' , SubmitType::class, ['label' => 'Créer une catégorie de publication','attr'=>['style'=>'margin-top:2em;']]);

        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $typePublication    =   $form->getData();
            $entityManager      =   $this->getDoctrine()->getManager();
            $entityManager  ->  persist($typePublication);
            $entityManager  ->  flush();
            
            return $this->redirectToRoute('type_publication_success_add');
        }
       
        return $this->render('type_publication/type-publication-form.html.twig', [
            'app_config'           =>  Conf::appConfig,           
            'form'                 =>  $form->createView(),
            'createName'           =>  "Création d'une nouvelle catégorie de publication",
        ]);
    }
    
    /**
     * @Route("/configuration/type-publication/edit/{id<\d+>}", name="type_publication_update")
     * @ParamConverter("id",class="App\Entity\TypePublication")
     */
    
     public function formUpdate(Request $request, TypePublication $typePublication)
    {
        
        $form = $this->createForm(FormTypePublication::class,$typePublication)
            ->add('enregistrer' , SubmitType::class, ['label' => 'Enregistrer une catégorie de publication','attr'=>['style'=>'margin-top:2em;']]);

        $form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            
            $typePublication    =   $form->getData();
            $entityManager      =   $this->getDoctrine()->getManager();
            $entityManager  ->  flush();
            
            return $this->redirectToRoute('type_publication_success_update');
        }
       
        return $this->render('type_publication/type-publication-form.html.twig', [
            'app_config'           =>  Conf::appConfig,           
            'form'                 =>  $form->createView(),
            'createName'           =>  "modification d'une nouvelle catégorie de publication",
        ]);
    }
    
    /**
     * @Route("/configuration/type-publication/success-add", name="type_publication_success_add")
     */
    
     public function displayAddSuccess()
    {
               
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,
        'successTitle'         =>  'Création de la catégorie de publication réussie !',
        'successPath'          =>  'type_publication_config_all',
        'entityPath'           =>  'publication_show_all',
        'seeSuccessEntity'     =>  'Mes publications',
        ]);
    }
    
    /**
     * @Route("/configuration/type-publication/success-delete", name="type_publication_success_delete")
     */
    
     public function displayDeleteSuccess()
    {
               
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,
        'successTitle'         =>  'Suppression de la catégorie de publication réussie !',
        'successPath'          =>  'type_publication_config_all',
        'entityPath'           =>  'publication_show_all',
        'seeSuccessEntity'     =>  'Mes publications',
        ]);
    }
    
    /**
     * @Route("/configuration/type-publication/success-update", name="type_publication_success_update")
     */
    
     public function displayUpdateSuccess()
    {
               
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,
        'successTitle'         =>  'Modification de la catégorie de publication réussie !',
        'successPath'          =>  'type_publication_config_all',
        'entityPath'           =>  'publication_show_all',
        'seeSuccessEntity'     =>  'Mes publications',
        ]);
    }
    
     /**
     * @Route("/configuration/type-publication/echec-delete", name="type_publication_echec_delete")
     */
    
     public function displayDeleteEchec()
    {
               
        return $this->render('generic/generic-echec.html.twig', [
        'app_config'            =>  Conf::appConfig,
        'echecTitle'            =>  'Impossible de supprimer cette catégorie car elle est encore utilisée !',
        'echecPath'             =>  'type_publication_config_all',
        'seeEchecEntity'        =>  'Mes publications',
        ]);
    }
    
    /**
     * @Route("/configuration/type-publication/delete/{id<\d+>}", name="type_publication_delete")
     * @ParamConverter("id",class="App\Entity\TypePublication")
     */
    
    public function formDelete(Request $request,TypePublication $typePublication)
    {
             
        if($typePublication){
        
            $entityManager  =   $this->getDoctrine()->getManager();
            if(sizeof($typePublication->getPublications())==0){
                $entityManager  ->  remove($typePublication);
                $entityManager  ->  flush();
                return $this->redirectToRoute('type_publication_success_delete');
            }
            else{
                return $this->redirectToRoute('type_publication_echec_delete');
            }

        }
    } 
}
