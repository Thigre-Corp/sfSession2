<?php
// src/Controller/FormationController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 
final class FormationController extends AbstractController
{

    #[Route ('/', name:'home')]
    #[Route('/formation', name: 'formation_index', methods: ['GET'])]
    
    public function index(EntityManagerInterface $em): Response
    {

        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'formations' => $em->getRepository(Formation::class)->findAll(),
            'auth' => $this->isGranted('ROLE_USER'),
        ]);
    }
    
    #[Route('/formation/new', name: 'formation_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $formation = new Formation();
    
        $form = $this->createForm(FormationType::class, $formation, [
            // IMPORTANT: on fige l'action vers la route "new"
            'action' => $this->generateUrl('formation_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('formation_index');
        }
    
        // Si tu veux charger en AJAX dans la modale :
        if ($request->isXmlHttpRequest()) {
            return $this->render('formation/_form_modal_content.html.twig', [
                'title' => 'Ajouter une formation',
                'form' => $form->createView(),
            ]);
        }
    
        // Fallback (peu utilisé ici)
        return $this->render('formation/new.html.twig',
         ['form' => $form ,
          'auth' => $this->isGranted('ROLE_USER'),
          'formations' => null
        ]);
    }
    
    #[Route('/formation/{id}/edit', name: 'formation_edit', methods: ['GET','POST'])]
    public function edit(Formation $formation, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FormationType::class, $formation, [
            // IMPORTANT: on fige l'action vers la route "edit" avec l'id
            'action' => $this->generateUrl('formation_edit', ['id' => $formation->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('formation_index');
        }
    
        if ($request->isXmlHttpRequest()) {
            return $this->render('formation/_form_modal_content.html.twig', [
                'title' => 'Éditer une formation',
                'form' => $form->createView(),
            ]);
        }
    
        return $this->render('formation/edit.html.twig', ['form' => $form]);
    }

    #[Route('/formation/{id}', name: 'show_formation')]
    public function show(Formation $formation) : Response
    {
        return $this->render('formation/show.html.twig', [
            'controller_name' => 'Show - FormationController',
            'formation' => $formation,
            'auth' => $this->isGranted('ROLE_USER'),
        ]);
    }

    #[Route('/formation/delete/{id}', name: 'delete_formation')]
    public function delete(EntityManagerInterface $entityManager, Formation $formation) : Response
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('formation_index');
    }

}