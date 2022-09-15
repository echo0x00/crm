<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Entity\Nomenclature;
use App\Repository\ArchiveRepository;
use App\Repository\NomenclatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="app_api")
 */
class ApiController extends AbstractController
{
    static $TOKEN = 'f72R2yQLZEc@rqF2vSB9pHa_iq';

//    public function index(): Response
//    {
//        return $this->render('api/index.html.twig', [
//            'controller_name' => 'ApiController',
//        ]);
//    }

    /**
     * @Route("/archives", name="archives_api_get", methods={"GET"})
     */
    public function getArchives(Request $request, ArchiveRepository $arRepository): JsonResponse
    {
        $data = $arRepository->findAll();
        $token = $request->query->get('token');
        if ($token === self::$TOKEN) {
            return $this->response($data);
        } else {
            return $this->response([]);
        }
    }

    /**
     * @param ArchiveRepository $arRepository
     * @param $title
     * @return JsonResponse
     * @Route("/archives/get-by-paper/{title}", name="archives_api_get_by_paper", methods={"GET"})
     */
    public function getByPaper(Request $request, ArchiveRepository $arRepository, $title): JsonResponse
    {
        $token = $request->query->get('token');
        if ($token !== self::$TOKEN) {
            return $this->response([]);
        }
        $post = $arRepository->findByNomenclatureTitle($title);

        if (!$post){
//            $data = [
//                'status' => 404,
//                'errors' => "Post not found",
//            ];
            return $this->response([]);
        }
        return $this->response($post);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ArchiveRepository $arRepository
     * @return JsonResponse
     * @throws Exception
     * @Route("/archives", name="archive_api_add", methods={"POST"})
     */
    public function addArchive(Request $request, EntityManagerInterface $entityManager, ArchiveRepository $arRepository): JsonResponse
    {
        try{
            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('nomenclature_id') || !$request->request->get('count')){
                throw new Exception();
            }
            $nomen = $this->getDoctrine()->getRepository(Nomenclature::class)->find($request->get('nomenclature_id'));
            $ar = new Archive();
            $ar->setCount($request->get('count'));
            $date = new \DateTime($request->get('date_paper'));
            $ar->setDatePaper($date);
            $ar->setNomenclature($nomen);
            $entityManager->persist($ar);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Archive added successfully",
            ];
            return $this->response($data);

        }catch (Exception $e){
            $data = [
                'status' => 422,
                'errors' => $e->getMessage(),
            ];
            return $this->response($data, 422);
        }
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function response(array $data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
