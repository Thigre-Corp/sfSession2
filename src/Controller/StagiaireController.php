<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator , Request $request): Response
    {

        $queryBuilder = $entityManager->getRepository(Stagiaire::class)->qbAllStagiaires();

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

       
        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'StagiaireController',
            'pagination' => $pagination,
            'auth' => $this->isGranted('ROLE_USER'),
        ]);
    }


    #[Route('/stagiaire/new', name: 'stagiaire_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $stagiaire = new Stagiaire();
    
        $createForm = $this->createForm(StagiaireType::class, $stagiaire);

        $createForm->handleRequest($request);
    
        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $em->persist($stagiaire);
            $em->flush();
            return $this->redirectToRoute('app_stagiaire');
        }
    
        return $this->render('stagiaire/new.html.twig', [
                'title' => 'Ajouter un stagiaire',
                'createForm' => $createForm->createView(),
                'auth' => $this->isGranted('ROLE_USER') ,
            ]);
    }



    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function edit(Stagiaire $stagiaire, Request $request, EntityManagerInterface $em): Response
    {
    
        $editForm = $this->createForm(StagiaireType::class, $stagiaire);

        $editForm->handleRequest($request);
    
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($stagiaire);
            $em->flush();
            return $this->redirectToRoute('app_stagiaire');
        }
    
        return $this->render('stagiaire/edit.html.twig', [
                'title' => 'Editer un stagiaire',
                'editForm' => $editForm->createView(),
                'auth' => $this->isGranted('ROLE_USER') ,
            ]);
    }

    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(EntityManagerInterface $entityManager, Stagiaire $stagiaire) : Response
    {
        $nom = $stagiaire->getNom();
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        $this->addFlash(
                    'warning',
                    $nom.' supprimé'
                );

        return $this->redirectToRoute('app_stagiaire');
    }


    #[Route('/stagiaire/{id}/show', name: 'show_stagiaire')]
    public function show(Stagiaire $stagiaire) : Response
    {

        return $this->render('stagiaire/show.html.twig', [
            'controller_name' => 'Show - StagiaireController',
            'stagiaire' => $stagiaire,
            'auth' => $this->isGranted('ROLE_USER'),
        ]);
    }

}
