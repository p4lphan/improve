<?php

namespace App\Controller;

use App\Tool\Conf;
use App\Entity\User;
use App\Form\FormUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormBuilderInterface;



class UserController extends AbstractController
{
    
    /**
     * @Route("/configuration/user/config", name="user_config_all")
     * @IsGranted("ROLE_ADMIN")
     */

    public function configAll(){
        
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        
        return $this->render('user/user-config-all.html.twig',[
            'app_config'               =>  Conf::appConfig,
            'options'                  =>  [1,1,1],   
            'entities'                 =>  $users,
            'title'                    =>  'Gestion des utilisateurs',
            'entitiesPlural'           =>  'Mes utilisateurs',            
            'entitiesPluralDescription'=>  'Ici se trouvent toutes les utilisateurs',  
            'className'                =>  'Utilisateur',
            'pageName'                 =>  'user',                       
            'friendlyClassName'        =>  "l'utilisateur",
            'bannerMessage'            =>  'Pour voir les utilisateurs, cliquer ici',
            'addControllerPath'        =>  'user_add',
            'headers'=>['Login',   
                        'Role',
                        'Status',
                        'Date de création',
                       '', ],             
        ]);
    }
    
     /**
     * @Route("/configuration/user/see/{id<\d+>}", name="user_see")
     * @ParamConverter("id", class="App\Entity\User")
     * @IsGranted("ROLE_ADMIN")
     */
    
    public function edit(user $user ){
        
       if($user){
           
         $form = $this->createForm(FormUser::class,$user,array('disabled'=>true))
            ->add('roles'           , ChoiceType::class,    ['multiple'=>true,'disabled'=>true,'choices'=>['Utilisateur'=>'ROLE_USER','Administrateur'=>'ROLE_ADMIN']]);
         
         return $this->render('user/user-form.html.twig', [
            'app_config'           =>  Conf::appConfig,           
            'form'                 =>  $form->createView(),
            'createName'           =>  "Information utilisateur",
        ]);
       }
       else{
           return $this->render('error404.html.twig',[
            'app_config'        =>  Conf::appConfig,
        ]);
       }
    }
    
    /**
     * @Route("/configuration/user/edit/{id<\d+>}", name="user_update")
     * @ParamConverter("id",class="App\Entity\User")
     */
    
     public function formUpdate(Request $request,User $user,AuthorizationCheckerInterface $authChecker)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
        
        if($user){
        
            $form = $this->createForm(FormUser::class,$user,['disabled'=>false]);
            if($authChecker->isGranted('ROLE_ADMIN')){
               $form->add('roles', ChoiceType::class ,['multiple'=>true,'choices'=>['Utilisateur'=>'ROLE_USER','Administrateur'=>'ROLE_ADMIN']]);
            }
            
            $form->add('enregistrer' , SubmitType::class, ['label' => 'Enregistrer','attr'=>['style'=>'margin-top:2em;']]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $user           =   $form->getData();
                $entityManager  =   $this->getDoctrine()->getManager();
                $entityManager  ->  flush();
                
                return $this->redirectToRoute('user_success_update');
            }

            return $this->render('user/user-form.html.twig', [
                'app_config'           =>  Conf::appConfig,               
                'form'                 =>  $form->createView(),
                'createName'           =>  "Modification de l'utilisateur : ".$user->getUsername(),
            ]);
        }
    } 
    
    /**
     * @Route("/configuration/user/edit/mdp/{id<\d+>}", name="user_update_mdp")
     * @ParamConverter("id",class="App\Entity\User")
     */
    
     public function changeMdp(Request $request, User $user,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
        
        if($user){
        
            $form = $this->createFormBuilder($user)
                ->add('plainPassword1'   ,   PasswordType::class, ['label'=>'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
              
            ])
                    ->add('plainPassword2'   ,   PasswordType::class, ['label'=>'Confirmation du Mot de passe',
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
              
            ])
                 ->add('enregister' , SubmitType::class, ['label' => 'Enregistrer','attr'=>['style'=>'margin-top:2em;']])
                 ->getForm();
           
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                $oldPassword1=$form->get('plainPassword1')->getData();
                $oldPassword2=$form->get('plainPassword2')->getData();
                
                if($oldPassword1==$oldPassword2){
                    $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $oldPassword1
                    ));

                    $entityManager  =   $this->getDoctrine()->getManager();
                    $entityManager  ->  flush();
                    return $this->redirectToRoute('user_success_update');
                }

            }
            else{
                return $this->render('user/user-form.html.twig', [
                'app_config'           =>  Conf::appConfig,               
                'form'                 =>  $form->createView(),
                'createName'           =>  "Les mots de passe ne correspondent pas !",
            ]);
            }
            
            
            return $this->render('user/user-form.html.twig', [
                'app_config'           =>  Conf::appConfig,               
                'form'                 =>  $form->createView(),
                'createName'           =>  "Modification du mot passe de l'utilisateur : ".$user->getUsername(),
            ]);
        }
    } 
    
     /**
     * @Route("/configuration/user/delete/{id<\d+>}", name="user_delete")
     * @ParamConverter("id",class="App\Entity\User")
     * @IsGranted("ROLE_ADMIN")
     */
    
     public function formDelete(User $user)
    {
         
        if($user){

            $entityManager  =   $this->getDoctrine()->getManager();            
            $entityManager  ->  remove($user);
            $entityManager  ->  flush();
            
            return $this->redirectToRoute('user_success_delete');
           
        }
        else{  
            
               return $this->redirectToRoute('user_echec_delete');
        }
    } 
    
    /**
     * @Route("/configuration/user/success-update", name="user_success_update")
     */
    
     public function displayUpdateSuccess()
    {
                
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,       
        'successTitle'         =>  'Modification de l'."'".'utilisateur réussie !',
        'successPath'          =>  'user_config_all',
        'entityPath'           =>  '',
        'seeSuccessEntity'     =>  'Mes utilisateurs',
        ]);
    }
    
    /**
     * @Route("/configuration/user/success-delete", name="user_success_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    
     public function displayDeleteSuccess()
    {
                
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,       
        'successTitle'         =>  'Suppression de l'."'".'utilisateur réussie !',
        'successPath'          =>  'user_config_all',
        'entityPath'           =>  '',
        'seeSuccessEntity'     =>  'Mes utilisateurs',
        ]);
    }
    
    /**
     * @Route("/configuration/user/echec-update", name="user_echec_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    
     public function displayDeleteEchec()
    {
                
        return $this->render('generic/generic-success.html.twig', [
        'app_config'           =>  Conf::appConfig,       
        'successTitle'         =>  'Modification de l'."'".'utilisateur impossible !',
        'successPath'          =>  'user_config_all',
        'entityPath'           =>  '',
        'seeSuccessEntity'     =>  'Mes utilisateurs',
        ]);
    }
            
}