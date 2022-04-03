<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\User;
use App\Form\OrdersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @var integer Page size.
     */
    const PAGE_SIZE = 10;

    /**
     * @Route("/orders", name="orders")
     */
    public function index(Request $request, int $page): Response
    {
        $date_order = $request->request->get('filter_date');
        $status = $request->get('filter_status');

        $filters = array(
            'filter_date' => ($date_order != null) ? $date_order : null,
            'filter_status' => ($status != null) ? $status : 99,
        );

        $orderList = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->getOrderList($filters, $page, self::PAGE_SIZE);

        return $this->render('orders/index.html.twig', [
            'orderList' => $orderList->getIterator(),
            'filters' => $filters,
            'current_page' => $page,
            'total_pages' => ceil($orderList->count() / self::PAGE_SIZE)
        ]);
    }

    /**
     * @Route("/orders/edit/{orderId}", name="orders_edit")
     * @param Request $request
     * @param int $orderId
     * @return RedirectResponse|Response
     */

    public function edit(Request $request, int $orderId): ?Response
    {
        $order = $this->getDoctrine()->getRepository(Orders::class)->find($orderId);
        $user_repo = $this->getDoctrine()->getRepository(User::class);
        $user_creator = $user_repo->find($order->getUserId());
        $user_editor = $user_repo->find($order->getEditorId());

        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setEditorId($this->getUser()->getId());
            $em->flush();
            $this->addFlash('success', 'Заказ изменен');
            return $this->redirectToRoute('orders');
        }

        return $this->render('orders/form.html.twig', [
            'title' => 'Редактирование заказа',
            'user_creator' => $user_creator->getFullName(),
            'user_editor' => ($user_editor) ? $user_editor->getFullName() : null,
            'order_edit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders/create", name="orders_create")
     * @param Request $request
     * @return Response
     */

    public function create(Request $request): Response
    {
        $order = new Orders();
        $em = $this->getDoctrine()->getManager();
        $order->setDateOrder(new \DateTime());
        $user_id = $this->getUser()->getId();
        $user_creator = $this->getDoctrine()->getRepository(User::class)->find($user_id)->getFullName();
        $order->setUserId($user_id);
        $order->setEditorId($user_id);

        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO номер лучше задавать при создании, чтобы не задублить одновременно
            $order->setNumber(Orders::getNextNumber($em));
            //$em->merge()
            $em->persist($order);
            $em->flush();
            $this->addFlash('success', 'Заказ создан');
            return $this->redirectToRoute('orders');
        }

        return $this->render('orders/form.html.twig', [
            'title' => 'Новый заказ',
            'order_edit' => $form->createView(),
            'user_creator' => $user_creator,
            'user_editor' => null
        ]);
    }
}
