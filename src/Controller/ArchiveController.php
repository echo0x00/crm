<?php

namespace App\Controller;

use App\Entity\Nomenclature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveController extends AbstractController
{
    /**
     * @Route("/archive", name="archive")
     */
    public function index(): Response
    {
        $nomen_list = $this->getDoctrine()->getRepository(Nomenclature::class)->findBy(['is_deleted' => 0]);
        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
            'nomen_list' => $nomen_list
        ]);
    }
}
