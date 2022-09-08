<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function PHPSTORM_META\map;
class IngredientController extends AbstractController
{
    /**
     * @Route("/ingredient", name="ingredient.index")
     * @IsGranted("ROLE_USER", statusCode=404)
     */
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {   
        $ingredients=$paginator->paginate(
            $repository->findBy(["user"=>$this->getUser()]),
            $request->query->getInt('page',1),
            10 
        ) ;
        
        // dd($ingredients);
        return $this->render('pages/ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
            'ingredients'=>$ingredients
        ]);
    }

    
    /**
     * Undocumented function
     * @Route("/ingredient/new", methods={"GET", "POST"},name="ingredient.new")
     * @IsGranted("ROLE_USER", statusCode=404)
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    
    public function new(Request $request,EntityManagerInterface $manager):Response
    {
        # code...
        $ingredient=new Ingredient();

        $form=$this->createForm(IngredientType::class,$ingredient);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           $ingredient= $form->getData();
            $ingredient->setUser($this->getUser());


            $manager->persist( $ingredient);
            $manager->flush();


            $this->addFlash(
                'success',
                'Votre ingredient a été crée avec succès',
            );
            return $this->redirectToRoute('ingredient.index');

        }

        return $this->render("pages/ingredient/new.html.twig",[
                'form'=>$form->createView()
            ]
        );

    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param IngredientRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     * @Route("/ingredient/edit/{id}",name="ingredient.edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and user.getId() == repository.findOneBy({'id': id}).getUser().getId()")
     * 
     */
    
    public function edit(int $id, IngredientRepository $repository
    ,Request $request, EntityManagerInterface $manager){
        dd($repository->findOneBy(['id'=>$id])->getUser()->getId());
        $ingredient=new Ingredient();
        $ingredient= $repository->findOneBy(['id'=>$id]);

        $form=$this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $ingredient=$form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            if(!$ingredient){
                $this->addFlash(
                    "danger",
                    "L'ingredient n' a pas été trouvé"
                );
            }else{
                $this->addFlash(
                    "success",
                    "Ingredient édité avec succès"
                );
            }

            return $this->redirectToRoute('ingredient.index');
        }
   
        
        return $this->render('pages/ingredient/edit.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * Supprimer un ingredient
     * @Route("/ingredient/delete/{id}", name="ingredient.delete", methods={"GET",""})
     * @Security("is_granted('ROLE_USER') and user.getId() == id", statusCode=404)
     * 
     */
    public function delete(int $id, IngredientRepository $repository,EntityManagerInterface $manager)
    {
        $ingredient= $repository->findOneBy(['id'=>$id]);

        if (!$ingredient) {
            $this->addFlash(
                "danger",
                "L'ingredient n' a pas été trouvé"
            );
        }else{
            $name = $ingredient ? $ingredient->getName() : '';
            $manager->remove($ingredient);
            $manager->flush();
            $this->addFlash("success","L' ingredient $name supprimé.");
        }

        return $this->redirectToRoute("ingredient.index");
    }
}
