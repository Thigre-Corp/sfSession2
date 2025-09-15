<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use Symfony\Component\Form\FormView;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




final class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(EntityManagerInterface $entityManager,  PaginatorInterface $paginator , Request $request): Response
    {

        //récupération des modules
        $modules = $entityManager->getRepository(Module::class)->findAll();

        $queryBuilder = $entityManager->getRepository(Module::class)->qbAllModules();

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('module/index.html.twig', [
            'controller_name' => 'moduleController',
            'modules' => $pagination,
            'auth' => $this->isGranted('ROLE_USER') ,
        ]);
    }

    #[Route('/module/new', name: 'module_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $module = new Module();
    
        $createForm = $this->createForm(ModuleType::class, $module);

        $createForm->handleRequest($request);
    
        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $em->persist($module);
            $em->flush();
            return $this->redirectToRoute('app_module');
        }
    
        return $this->render('module/new.html.twig', [
                'title' => 'Ajouter un module',
                'createForm' => $createForm->createView(),
                'auth' => $this->isGranted('ROLE_USER') ,
            ]);
    }



    #[Route('/module/{id}/edit', name: 'edit_module')]
    public function edit(Module $module, Request $request, EntityManagerInterface $em): Response
    {
    
        $editForm = $this->createForm(ModuleType::class, $module);

        $editForm->handleRequest($request);
    
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($module);
            $em->flush();
            return $this->redirectToRoute('app_module');
        }
    
        return $this->render('module/edit.html.twig', [
                'title' => 'Editer un module',
                'editForm' => $editForm->createView(),
                'auth' => $this->isGranted('ROLE_USER') ,
            ]);
    }

    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function delete(EntityManagerInterface $entityManager, Module $module) : Response
    {
        $nom = $module->getNom();
        $entityManager->remove($module);
        $entityManager->flush();

        $this->addFlash(
                    'warning',
                    $nom.' supprimé'
                );

        return $this->redirectToRoute('app_module');
    }
}
