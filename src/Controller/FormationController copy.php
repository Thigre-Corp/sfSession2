<?php
/*
namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormationController extends AbstractController
{
    #[Route('/formation/new', name: 'home')]

   // #[Route('/formation', name: 'app_formation')]
    public function index(EntityManagerInterface $entityManager, Request $request, Formation $formation = null): Response
    {
        $formations = $entityManager->getRepository(Formation::class)->findAll();
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'formations' => $formations
        ]);
    }

    
    #[Route('/formation/edit{id}', name: 'edit_formation')]
    public function edit(EntityManagerInterface $entityManager, Request $request, Formation $formation = null): Response
    {
        $formations = $entityManager->getRepository(Formation::class)->findAll();

        if( !$formation){
            $formation = new Formation();
        }

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formation = $form->getData();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'formations' => $formations,
            'formAddFormation' => $form,
            'edit' => $formation->getId(),
            'auth' => true
        ]);
    }












    #[Route('/formation&id={id}', name: 'show_formation')]
    public function show(Formation $formation) : Response
    {
        return $this->render('formation/show.html.twig', [
            'controller_name' => 'Show - FormationController',
            'formation' => $formation
        ]);
    }

    #[Route('/formation/delete&id={id}', name: 'delete_formation')]
    public function delete(EntityManagerInterface $entityManager, Formation $formation) : Response
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}

*/