<?php

namespace App\Controller;

use App\Entity\User;
use App\Tool\Conf;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/configuration/user/add", name="user_add")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        if($authChecker->isGranted('ROLE_ADMIN')){
             $form->add('roles', ChoiceType::class ,['multiple'=>true,'choices'=>['Utilisateur'=>'ROLE_USER','Administrateur'=>'ROLE_ADMIN']]);
        }
        
        $form->add('enregistrer' , SubmitType::class, ['label' => 'Créer un utilisateur','attr'=>['style'=>'margin-top:2em;']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreateDate(new \DateTime('now'));
            $user->setName('');
            $user->setFirstname('');
            $user->setEmail('');
            $user->setValid(true);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if( $authChecker->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('user_config_all');
            }
            
            return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
        }

        return $this->render('registration/register.html.twig', [
            'app_config'           =>  Conf::appConfig,           
            'form'                 =>  $form->createView(),
            'createName'           =>  "Création d'un nouvel utilisateur",
        ]);
    }
}
