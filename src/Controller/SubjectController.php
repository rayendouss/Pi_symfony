<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\CommentRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\Mapping\Id;
use Normalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/commande")
 */
class SubjectController extends AbstractController

{
    /**
     * @Route("/alljson", name="commandeAöö")
     */
    public function alljson(SubjectRepository $commandeRepository, NormalizerInterface $Normalizer): Response
    {
        /* $commandes=$commandeRepository->findAll(); */
        $commandes = $commandeRepository->findAll() ;


        $jsonContent = $Normalizer->normalize($commandes, 'json', ['groups' => 'cmd']);


        /* return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]); */
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/subject/{id}", name="subjetidd", requirements={"id":"\d+"})

     */
    public function getsubject(Request $request, SubjectRepository $commandeRepository, NormalizerInterface $Normalizer): Response
    {
        /* $commandes=$commandeRepository->findAll(); */


        $commandes = $commandeRepository->find($request->get('id')) ;


        $jsonContent = $Normalizer->normalize($commandes, 'json', ['groups' => 'cmd']);


        /* return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]); */
        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/allusers", name="user")
     */
    public function alluser(ClientRepository $clientRepository, NormalizerInterface $Normalizer): Response
    {
        /* $commandes=$commandeRepository->findAll(); */
        $client = $clientRepository->findAll() ;


        $jsonContent = $Normalizer->normalize($client, 'json', ['groups' => 'cmd']);


        /* return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]); */
        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/", name="commande_index")
     */
    public function commandesNotDone(SubjectRepository $commandeRepository, NormalizerInterface $Normalizer): Response
    {
        /* $commandes=$commandeRepository->findAll(); */
        $commandes = $commandeRepository->findBy(["done" => false , "client" => 1]);
        


        $jsonContent = $Normalizer->normalize($commandes, 'json', ['groups' => 'cmd']);


        /* return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]); */
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/addCommandejson/new", name="addCommandejson")
     */
    public function addCommandejson(Request $request, NormalizerInterface $Normalizer, CommentRepository $oeuvreRepo, ClientRepository $clientRepo, $idClient = 1, $idOeuvre = 1): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $idClient = 1;
        $idOeuvre = 1;
        $commande = new Subject();

        //$commande->setDateCommande();
         $commande->setMsg($request->get('msg'));
        $commande->setTitle($request->get('title'));
        $client = $clientRepo->find($request->get('idClient'));
        $commande->setClient($client);
        $commande->setCreateAt(new \DateTime());


        $entityManager->persist($commande);
        $entityManager->flush();
        $json = $Normalizer->normalize($commande, 'json', ['groups' => 'cmd']);

     

        return new Response(json_encode($json));
    }

    /**
     * @Route("/new", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commande = new Subject();
        $form = $this->createForm(SubjectType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Subject $commande, SubjectRepository   $commandeRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $oeu = $commandeRepository->find($commande->getId());
    }


    /**
     * @Route("/clientid/", name="commande_show")
     */
    public function findbyclientid(Subject $commande, SubjectRepository $repo): Response
    {
        $commandesclient = $repo->findOneBySomeField(1);

        dump($commandesclient);
        die;

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
    /**
     * @Route("/confirmCommandeJSON", name="confirmCommandeJSON", methods={"GET","POST"})
     */
    public function edit(MailerInterface $mailer , Request $request, SubjectRepository $commandeRepository, CommentRepository $oeuvreRepository, NormalizerInterface $Normalizer): Response
    {

        $commandes = $commandeRepository->findBy(["done" => false]);


        foreach ($commandes as $commande) {

            $commande->setDone(true);
            /* $oeuvre=$commande->getOeuvre()[0]; */
            $commande->getOeuvre()[0]->setQuantity($commande->getOeuvre()[0]->getQuantity()-1);
            
            

            $this->getDoctrine()->getManager()->flush();
        }
        $json = $Normalizer->normalize($commandes, 'json', ['groups' => 'cmd']);
   //mailer
       
        
    $email = (new Email())
   ->from('fares.elouissi@esprit.tn')
   ->to('fares.elouissi@esprit.tn')
   //->cc('cc@example.com')
   //->bcc('bcc@example.com')
   //->replyTo('fabien@example.com')
   //->priority(Email::PRIORITY_HIGH)
   ->subject('ArtisticShowroom : Votre commande est passé avec Succes')
   ->text('Sending emails is fun again!')
   ->html('<p>Merci votre commande est passé avec succes</p>');

$mailer->send($email);

// ... */




        /* $form = $this->createForm(SubjectType::class, $commande);
        $form->handleRequest($request); */

        /* if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_index');
         */

        /*    return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(), 
        ]);*/
        return new Response("commande confirmed" . json_encode(($json)));
    }

    /**
     * @Route("/deleteCommandeJSON/{id}", name="deleteCommandeJson",methods={"DELETE"})
     */
    public function delete(Request $request, Subject $commandeToDelete, NormalizerInterface $Normalizer, SubjectRepository $CommandeRepo, $id): Response
    {
        /* if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) { */
        $entityManager = $this->getDoctrine()->getManager();
        $commandeToDelete = $CommandeRepo->find($id);

        

        $entityManager->remove($commandeToDelete);
        $entityManager->flush();
        $json = $Normalizer->normalize($commandeToDelete,  'json', ['groups' => 'cmd']);
        return new Response("Subject deleted" . json_encode($json));
        /*  } */

        /* return $this->redirectToRoute('commande_index'); */
    }
}
