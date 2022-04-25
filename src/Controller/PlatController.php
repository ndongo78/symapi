<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Plat;
use App\Form\PlatType;

use App\Repository\PlatRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class PlatController extends AbstractController
{
    #[Route('/api/plat', name: 'app_plat',methods: ["POST"])]
    public function index(Request $request,ManagerRegistry $managerRegistry, FileUploader $fileUploader): Response
      {
          $data=$request->request->all();
        $plat= new Plat();
        $category=$managerRegistry->getRepository(Category::class)->find($data["category"]);
        $form=$this->createForm(PlatType::class,$plat,array('csrf_protection' => false));
        $er= $form->submit($data);
        $image=$request->files->get('image');

        if($form->isValid() && $image ){
            $fileName=$fileUploader->upload($image);
          $plat->setName($data["name"])
              ->setDescription($data["description"])
              ->setPrice($data["price"])
              ->setImageFile($fileName)
             ->setCategory($category)
             ->setUpdatedAt(new \DateTime());
           $managerRegistry->getManager()->persist($plat);
           $managerRegistry->getManager()->flush();
            return  $this->json(["meesage"=>"Category saved"]);
        }else{
            return  $this->json($er);
        }
    }
    #[Route("/plat", name:"get_plat",methods:["GET"])]
    public function show(ManagerRegistry $managerRegistry,PlatRepository $platRepository) : Response
    {
        $plats=$platRepository->findAll();
        return  $this->json($plats);
   }
   #[Route('/api/plat/{id}',name:"update",methods:["POST"])]
   public function update(ManagerRegistry $managerRegistry ,Request $request,int $id,FileUploader $fileUploader)
   {
       $data=$request->request->all();
       $plat= new Plat();
       $plats=$managerRegistry->getRepository(Plat::class)->find($id);
       $form=$this->createForm(PlatType::class,$plat,array('csrf_protection' => false));
       $er= $form->submit($data);
       $image=$request->files->get('image');
       if ($image ==null){
           if($form->isValid()){
               $fileName=$fileUploader->upload($image);
               $plats->setName($data["name"])
                   ->setDescription($data["description"])
                   ->setPrice($data["price"])
                   ->setUpdatedAt(new \DateTime());
               $managerRegistry->getManager()->persist($plats);
               $managerRegistry->getManager()->flush();
               return  $this->json(["meesage"=>"Plat updated"]);
           }else{
               return  $this->json($er);
           }
       }else{
       if($form->isValid()){
           $fileName=$fileUploader->upload($image);
           $plats->setName($data["name"])
               ->setDescription($data["description"])
               ->setPrice($data["price"])
               -> setImageFile($fileName)
               ->setUpdatedAt(new \DateTime());
           $managerRegistry->getManager()->persist($plats);
           $managerRegistry->getManager()->flush();
           return  $this->json(["meesage"=>"Plat updated"]);
       }else{
           return  $this->json($er);
       }
       }
   }
   #[Route("/api/delete/{id}",name:"delete",methods:["DELETE"])]
   public function delete(ManagerRegistry $managerRegistry ,int $id)
   {
       $plat=$managerRegistry->getRepository(Plat::class)->find($id);
       if ($plat){
           $managerRegistry->getManager()->remove($plat);
           $managerRegistry->getManager()->flush();
           return $this->json(["message"=>"Plat sumprimé avec succés"]);
       }else{
           return $this->json(["message"=>"Not Found 404 "]);
       }
  }
}
