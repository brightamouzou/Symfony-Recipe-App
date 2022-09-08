<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Mark;
use App\Entity\Receipt;
use App\Form\MarkType;
use App\Form\ReceiptType;
use App\Repository\IngredientRepository;
use App\Repository\MarkRepository;
use App\Repository\ReceiptRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ReceiptController extends AbstractController


{
    /**
     * Route pour la liste des recettes
     * @Route("/receipt", name="receipt.index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * 
     */
    public function index(PaginatorInterface $paginator, Request $request, ReceiptRepository $repository): Response
    {
        $receipts= $paginator->paginate(
            $repository->findBy(["user"=>$this->getUser()]),
            $request->query->get('page', 1),
            10
        );

        // dd($receipts);
        
        return $this->render('pages/receipt/index.html.twig', [
            'controller_name' => 'ReceiptController',
            'receipts'=>$receipts
        ]);
    }

    /**
     * @Route("/receipt/show/{id}", name="receipt.show", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER') and (user.getId() == receipt.getUser().getId() || receipt.getIsPublic() == true)")
     * @param Receipt $receipt
     * @return Response
     */
    public function show(
        Receipt $receipt,
        Request $request,
        MarkRepository $repository,
        EntityManagerInterface $manager
    ):Response{
        $mark=new Mark();
        $userMark = $repository->findOneBy([
            'user' => $this->getUser(),
            'receipt' => $receipt
        ]);

        if($userMark){
            $mark->setValue($userMark->getValue());
        }

        $form=$this->createForm(MarkType::class, $mark);

        $form->handleRequest($request);
        $formData=$form->getData();


        if($form->isSubmitted() && $form->isValid() && $receipt->getUser()!== $this->getUser()){
            $userMark=$repository->findOneBy([
                'user'=>$this->getUser(),
                'receipt' =>$receipt
            ]);

            if($userMark){
                $userMark->setValue($formData->getValue());
            }else{
                $mark=$formData;
                $mark->setReceipt($receipt);
                $mark->setUser($this->getUser());;
                $manager->persist($mark);
            }

            $manager->flush();

            $this->addFlash("success", "Votre note a été bien enregistrée");

            $this->redirectToRoute("receipt.show", ['id'=>$receipt->getId()]);
        }
        
        
        return $this->render("pages/receipt/show.html.twig", [
            'receipt' => $receipt,
            'form'=>$form->createView(),
            'marksInfos'=>$repository->getAverageMarks($receipt->getId())
        ]);
    }

    /**
     * Undocumented function
     * @Route("/receipt/public", name="receipt.public", methods={"GET"})
     * @param integer $max
     * @param ReceiptRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function showPublicReceipts(?int $max, ReceiptRepository $repository,PaginatorInterface $paginator, Request $request ):Response{
        $wReceipts=$paginator->paginate(
            $repository->findPulic($max),
            $request->get("page", 1), 
            10
        );

        
        // dd($wReceipts);
        
        return $this->render("pages/receipt/show_public.html.twig",[
            'receipts'=>$wReceipts
        ]);

    }



    

    /**
     * Undocumented function
     * @Route("/receipt/new", name="receipt.new",methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function new(Request $request,  EntityManagerInterface $manager):Response{
        $receipt=new Receipt();
        $form=$this->createForm(ReceiptType::class, $receipt);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $receipt= $form->getData();
            $receipt->setUser($this->getUser());
            // dd($receipt);
            $manager->persist($receipt);
            $manager->flush();

            $this->addFlash(
                "success",
                "Recette créee avec succès"
            );

            return $this->redirectToRoute("receipt.index");
            
        }

        return $this->render('pages/receipt/new.html.twig', [
            'form'=>$form->createView()
        ]);
        
        
    }

    /**
     * Editer une recette
     * @Route("/receipt/edit/{id}", name="receipt.edit",methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and user.getId() === receipt.getUser().getId()")
     * 
     * @param Request $request
     * @param integer $receipt
     * @return Response
     */
    public function edit(Receipt $receipt,EntityManagerInterface $manager, Request $request,ReceiptRepository $repository):Response{
        // $receipt = new Receipt();
        // $receipt = $repository->findOneBy(['id'=>$id]);
        $form = $this->createForm(ReceiptType::class, $receipt);

        // dd($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receipt = $form->getData();
            // dd($receipt);
            $manager->persist($receipt);
            $manager->flush();

            $this->addFlash(
                "success",
                "Recette créee avec succès"
            );

            return $this->redirectToRoute("receipt.index");
        }

        return $this->render('pages/receipt/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Undocumented function
     * @Route("/receipt/delete/{id}", name="receipt.delete", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and user.getId() == id", statusCode=404)
     * 
     * @param integer $id
     * @param IngredientRepository $repository
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(int $id, ReceiptRepository $repository, EntityManagerInterface $manager)
    {
        $receipt = $repository->findOneBy(['id' => $id]);

        if (!$receipt) {
            $this->addFlash(
                "danger",
                "La recette n' a pas été trouvé"
            );
        } else {
            $name = $receipt ? $receipt->getName() : '';
            $manager->remove($receipt);
            $manager->flush();
            $this->addFlash("success", "La recette $name supprimé.");
        }

        return $this->redirectToRoute("receipt.index");
    }
}
