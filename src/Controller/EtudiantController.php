<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    private $manager;
    private $repository;
    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->manager = $this->doctrine->getManager();
        $this->repository = $this->doctrine->getRepository(Etudiant::class);
    }

    #[Route('/etudiant/list', name: 'affiche')]
    public function affiche(): Response
    {

        $data = $this->repository->findAll();
        return $this->render('etudiant/index.html.twig', [
            'data' => $data
        ]);
    }

    #[Route('/etudiant/addform', name: 'add_item')]
    public function addform(Request $req): Response
    {
        $new_item = new Etudiant();

        $form = $this->createForm(EtudiantType::class, $new_item);
        $form->handleRequest($req);

        if($form->isSubmitted()){
            $this->manager->persist($new_item);
            $this->manager->flush();

            return $this->redirectToRoute('affiche');
        }

        return $this->render('etudiant/form.html.twig', [
            'add_form' => $form->createView(),
        ]);
    }

    #[Route('/etudient/edit/{id}', name: 'edit')]
    public function edit(Request $request, Etudiant $etudiant = null): Response
    {
        if (!$etudiant) {
            $etudiant = new Etudiant();
        }

        $form = $this->createForm(EtudiantType::class, $etudiant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//
            $this->manager->persist($etudiant);
            $this->manager->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render('etudiant/form.html.twig', [

            'add_form' => $form->createView(),
        ]);
    }

    #[Route('/etudiant/delete/{id}', name: 'delete')]
    public function delete(Etudiant $etudiant = null): Response
    {

        if(!$etudiant) {
            throw new NotFoundHttpException("Not Found");
        } else {
            $this->manager->remove($etudiant);
            $this->manager->flush();
            $this->addFlash('success', "Student Deleted With Success");
            return $this->redirectToRoute('affiche');
        }
    }
}
