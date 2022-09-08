<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Receipt;
use App\Form\MarkType;
use App\Repository\MarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarkController extends AbstractController
{
    /**
     *
     * @Route("/mark", name="mark.index",)
     */
    public function index(MarkRepository $repository, PaginatorInterface $paginator,Request $request): Response
    {
        $marks= $paginator->paginate(
            $repository->findAll(),
            $request->query->get('page', 1)
        );
        
        return $this->render('pages/mark/index.html.twig', [
            'marks'=> $marks
        ]);
    }

    /**
     * Get marks form a receipt
     * @Route("/marks/receipt/{id}", name="mark.receipt", methods={"GET"})
     * @param MarkRepository $repository
     * @param Receipt $receipt
     * @param integer|null $max
     */
    public function receiptMarks(MarkRepository $repository,Receipt $receipt, ?int $max){
        $wMarks=$repository->receiptMarks($receipt, $max??null);
        return $wMarks;
    }

    // public function new(EntityManagerInterface $manager,$receiptId,  ){

    //     $mark=new Mark();
    //     $form=$this->createForm(MarkType::class, );
    // }
}
