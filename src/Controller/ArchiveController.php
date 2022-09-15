<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Entity\Nomenclature;
use App\Form\ArchiveType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArchiveController extends AbstractController
{

    /**
     * @Route("/archive", name="archive")
     */
    public function index(): Response
    {
        $archive_list = $this->getDoctrine()->getRepository(Archive::class)
        ->getArchiveList();
        $nomen_list = $this->getDoctrine()->getRepository(Nomenclature::class)->findAll();
        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
            'archive_list' => $archive_list,
            'nomen_list' => $nomen_list
        ]);
    }

    /**
     * @Route("/archive/create", name="archive_create")
     * @param  $request
     * @return mixed
     */
    public function create(Request $request) {
        $archive = new Archive();
        $form = $this->createForm(ArchiveType::class, $archive);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            //$archive->setIsDeleted(false);
            $em->persist($archive);
            $em->flush();
            $this->addFlash('success', 'Запись добавлена в Архив');
            return $this->redirectToRoute('archive');
        }
        return $this->render('archive/form.html.twig', [
            'title' => 'Запись архива',
            'archive_edit' => $form->createView()
        ]);
    }
}
