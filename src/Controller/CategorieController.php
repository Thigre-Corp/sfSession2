<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\Form\FormView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(EntityManagerInterface $entityManager,  Request $request): Response
    {

        //récupération des Catégories
        $categories = $entityManager->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories,
            'auth' => $this->isGranted('ROLE_USER') ,
        ]);
    }

    #[Route('/categorie/new', name: 'categorie_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
    
        $createForm = $this->createForm(CategorieType::class, $categorie);

        $createForm->handleRequest($request);
    
        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_categorie');
        }
    
        return $this->render('categorie/new.html.twig', [
                'title' => 'Ajouter une categorie',
                'createForm' => $createForm->createView(),
                'auth' => $this->isGranted('ROLE_USER') ,
            ]);
    }



    #[Route('/categorie/{id}/edit', name: 'edit_categorie')]
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
    
        $editForm = $this->createForm(CategorieType::class, $categorie);

        $editForm->handleRequest($request);
    
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_categorie');
        }
    
        return $this->render('categorie/edit.html.twig', [
                'title' => 'Editer une categorie',
                'editForm' => $editForm->createView(),
                'auth' => $this->isGranted('ROLE_USER') ,
            ]);
    }

    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
    public function delete(EntityManagerInterface $entityManager, Categorie $categorie) : Response
    {
        $nom = $categorie->getNom();
        $entityManager->remove($categorie);
        $entityManager->flush();

        $this->addFlash(
                    'warning',
                    $nom.' supprimé'
                );

        return $this->redirectToRoute('app_categorie');
    }
}
