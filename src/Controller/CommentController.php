<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Comment;

use App\Form\CommentType;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/oeuvre")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="oeuvre_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository , NormalizerInterface $Normalizer): Response
    {
        $oeuvres=$commentRepository->findAll();
        $json=$Normalizer->normalize($oeuvres, 'json', ['groups' => 'cmd']) ;

        return new Response(json_encode($json)) ;
    }


    /**
     * @Route("/new", name="oeuvre_new")
     */
    public function new(Request $request, NormalizerInterface $Normalizer, CommandeRepository $commandeRepository, ClientRepository $clientRepo, $idClient = 1, $idOeuvre = 1): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $idClient = 1;
        $idOeuvre = 1;
        $oeuvre = new Comment();

        //$commande->setDateCommande();
        $oeuvre->setMsg($request->get('msg'));

        $oeuvre->setCreateAt(new \DateTime());
        $oeu = $commandeRepository->find($request->get('idSubject'));

        $oeuvre->setCommande($oeu);
        $entityManager->persist($oeuvre);
        $entityManager->flush();
        $json = $Normalizer->normalize($oeuvre, 'json', ['groups' => 'cmd']);



        return new Response(json_encode($json));
    }

    /**
     * @Route("/{id}", name="oeuvre_show", methods={"GET"})
     */
    public function show(Comment $comment,NormalizerInterface $Normalizer): Response
    {
        /* return $this->render('oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]); */
        return new Response(json_encode($Normalizer->normalize($comment, 'json')  )) ;
    }

    /**
     * @Route("/{id}/edit", name="oeuvre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('oeuvre_index');
        }

        return $this->render('oeuvre/edit.html.twig', [
            'oeuvre' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="oeuvre_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment , CommentRepository  $commentRepository): Response
    {


            $entityManager = $this->getDoctrine()->getManager();
        $oeu = $commentRepository->find($comment->getId());
            $entityManager->remove($oeu);
            $entityManager->flush();


        return $this->redirectToRoute('oeuvre_index');
    }
}
