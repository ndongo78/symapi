<?php

namespace App\Controller;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
class CategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'app_category',methods: ['POST'])]
    public function create(ValidatorInterface $validator , ManagerRegistry $managerRegistry ,Request $request): Response
    {
       $category= new Category;
       $user=$this->getUser();

       $data=json_decode($request->getContent(),true);
       $form=$this->createForm(CategoryType::class,$category,array('csrf_protection' => false));
    $er= $form->submit($data);

     if($form->isValid() ){
         $category->setName($data['name']);
         $managerRegistry->getManager()->persist($category);
         $managerRegistry->getManager()->flush();
         return  $this->json(["meesage"=>"Category saved"]);
     }else{
         return  $this->json($er);
     }
    }
    #[Route("/categories", name:"get_category",methods:['GET'])]
    public function show(CategoryRepository $categoryRepository) : Response
    {
        $categories= $categoryRepository->findAll();

        return $this->json(
                $categories
        );
    }
    #[Route('/api/categories/{id}', name: "update", methods:["PUT"])]
    public function updated(ManagerRegistry $managerRegistry ,Request $request ,int $id,CategoryRepository $categoryRepository) : Response
    {
        $category=new Category();
        $cateEx=$managerRegistry->getRepository(Category::class)->find($id);
        $data=json_decode($request->getContent(),true);
        $form=$this->createForm(CategoryType::class,$category,array('csrf_protection' => false));
        $er= $form->submit($data);

       if ($cateEx) {
            if ($form->isValid()) {
               $cateEx->setName($data['name']);
                $managerRegistry->getManager()->flush();
                return $this->json(["meesage" => "Category updated"]);
            } else {
                return $this->json($er);
            }
        }else
        {
            return $this->json(["message"=>"Error 404"]);
        }
   }
   #[Route("/api/categories/{id}", name:'delete',methods: ["DELETE"])]
   public function delete(ManagerRegistry $managerRegistry , int $id) : Response
   {
       $data=$managerRegistry->getRepository(Category::class)->find($id);
       if ($data){
           $managerRegistry->getManager()->remove($data);
           $managerRegistry->getManager()->flush();
           return  $this->json(["message"=>"Categorie sumprimer avec succes"]);
       }else
       {
           return  $this->json(["message"=>"Error 404"]);
       }
  }
}
