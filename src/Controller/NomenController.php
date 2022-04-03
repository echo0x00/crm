<?php

namespace App\Controller;

use App\Entity\Nomenclature;
use App\Form\NomenclatureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NomenController extends AbstractController
{
    /**
     * @Route("/nomenclature", name="nomen")
     */
    public function index(): Response
    {
        $nomenList = $this->getDoctrine()->getRepository(Nomenclature::class)->findBy(['is_deleted' => 0]);
        return $this->render('nomen/index.html.twig', [
            'nomenList' => $nomenList,
        ]);
    }

    /**
     * @Route("/nomenclature/create", name="nomen_create")
     * @param  $request
     * @return mixed
     */

    public function create(Request $request)
    {
        $nomen = new Nomenclature();
        $form = $this->createForm(NomenclatureType::class, $nomen);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $nomen->setIsDeleted(false);
            $em->persist($nomen);
            $em->flush();
            $this->addFlash('success', 'Номенклатура создана');
            return $this->redirectToRoute('nomen');
        }

        return $this->render('nomen/form.html.twig', [
            'title' => 'Новая номенклатура',
            'nomenclature_edit' => $form->createView()
        ]);
    }

    /**
     * @Route("/nomenclature/edit/{nomenId}", name="nomen_edit")
     * @param Request $request
     * @param int $nomenId
     * @return RedirectResponse|Response
     */

    public function editAction(Request $request, int $nomenId): ?Response
    {
        $nomen = $this->getDoctrine()->getRepository(Nomenclature::class)->find($nomenId);
        $form = $this->createForm(NomenclatureType::class, $nomen);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Номенклатура изменена');
            return $this->redirectToRoute('nomen');
        }

        return $this->render('nomen/form.html.twig', [
            'title' => 'Редактирование номенклатуры',
            'nomenclature_edit' => $form->createView()
        ]);
    }

    /**
     * @Route("/nomenclature/remove/{nomenId}", name="nomen_remove")
     * @param Request $request
     * @param int $nomenId
     * @return RedirectResponse|Response
     */

    public function removeAction(Request $request, int $nomenId)
    {
        $nomen = $this->getDoctrine()->getRepository(Nomenclature::class)->find($nomenId);
        $nomen->setIsDeleted(1);
        $em = $this->getDoctrine()->getManager();

        //$em->remove($nomen); Может быть захотим совсем удалять
        $em->flush();
        $this->addFlash('success', 'Номенклатура ' . $nomen->getFullName() . ' помечена на удаление.');
        return $this->redirectToRoute('nomen');
    }
}
