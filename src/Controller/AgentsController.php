<?php

namespace App\Controller;

use App\Entity\Agents;
use App\Form\AgentsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentsController extends AbstractController
{
    /**
     * @Route("/agents", name="agents")
     */
    public function index(): Response
    {
        $agentList = $this->getDoctrine()->getRepository(Agents::class)->findBy(['is_deleted' => 0]);
        return $this->render('agents/index.html.twig', [
            'agentList' => $agentList,
        ]);
    }

    /**
     * @Route("/agents/create", name="agent_create")
     * @param  $request
     * @return mixed
     */

    public function create(Request $request)
    {
        $agent = new Agents();
        $agent->setIpAddress('127.0.0.1');
        $form = $this->createForm(AgentsType::class, $agent);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($agent);
            $em->flush();
            $this->addFlash('success', 'Покупатель создан успешно.');
            return $this->redirectToRoute('agents');
        }

        return $this->render('agents/form.html.twig', [
            'title' => 'Новый покупатель',
            'agent_edit' => $form->createView()
        ]);
    }

    /**
     * @Route("/agents/edit/{agentId}", name="agent_edit")
     * @param Request $request
     * @param int agentId
     * @return RedirectResponse|Response
     */

    public function editAction(Request $request, int $agentId)
    {
        $agent = $this->getDoctrine()->getRepository(Agents::class)->find($agentId);
        $form = $this->createForm(AgentsType::class, $agent);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Покупатель изменен');
            return $this->redirectToRoute('agents');
        }

        return $this->render('agents/form.html.twig', [
            'title' => 'Редактирование контрагента',
            'agent_edit' => $form->createView()
        ]);
    }

    /**
     * @Route("/agents/remove/{agentId}", name="agent_remove")
     * @param Request $request
     * @param int $agentId
     * @return RedirectResponse|Response
     */

    public function removeAction(Request $request, int $agentId)
    {
        $agent = $this->getDoctrine()->getRepository(Agents::class)->find($agentId);
        $agent->setIsDeleted(1);
        $em = $this->getDoctrine()->getManager();

        //$em->remove($nomen); Может быть захотим совсем удалять
        $em->flush();
        $this->addFlash('success', 'Покупатель ' . $agent->getFio() . ' помечен на удаление.');
        return $this->redirectToRoute('agents');
    }
}
