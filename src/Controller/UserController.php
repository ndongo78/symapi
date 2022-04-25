<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/register', name: 'app_user',methods: ["POST"])]
    public function register(ManagerRegistry $managerRegistry , Request $request ,UserPasswordHasherInterface $passwordHasher): Response
    {
       $user= new User();
       $data=json_decode($request->getContent(),true);
       $form= $this->createForm(UserType::class,$user,['csrf_protection' => false]);
        $er= $form->submit($data);
        if($form->isValid()){
           $password=$passwordHasher->hashPassword(
               $user,
               $data['password']
           );
            $user->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setEmail($data['email'])
                ->setPassword($password);
                $managerRegistry->getManager()->persist($user);
                $managerRegistry->getManager()->flush();
            return  $this->json(
                [
                    "message" => "Votre compte est créer avec succes"
                ]
            );
        }else{
            return  $this->json(["errors"=>$er]);
        }

    }

     #[Route('/login', name: 'user_login',methods: ["POST"])]
    public  function login() : Response
     {
         $user= $this->getUser();

         return  $this->json($user);
     }
}
