<?php 

namespace App\Controller;

use App\Entity\Doctor;
use App\Helper\DoctorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorsController extends AbstractController
{
    private $statusCode = 200;
    /**
     * @var private $entityManager
     */
    /**
     * @var private $doctorFactory
     */
    public function __construct(EntityManagerInterface $entityManager, DoctorFactory $doctorFactory)
    {
        $this->entityManager = $entityManager;
        $this->doctorFactory = $doctorFactory;
    }

    /**
     * @Route("/doctors", methods={"GET"})
     */
    public function getAllAction(): Response
    {
        $doctorRepository = $this->getDoctrine()->getRepository(Doctor::class);

        $doctors = $doctorRepository->findAll();

        return new JsonResponse($doctors);
    }

    /**
     * @Route("/doctors", methods={"POST"})
     */
    public function storeAction(Request $request): Response
    {
        $input = $request->getContent();

        $doctor = $this->doctorFactory->mutateDoctor($input);

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        return new JsonResponse($doctor);
    }

    /**
     * @Route("/doctor/{id}", methods={"GET"})
     */
    public function getOneAction(int $id): Response
    {
        $doctor = $this->getDoctor($id);

        $this->statusCode;
        if (is_null($doctor)) {
            $this->statusCode = Response::HTTP_NO_CONTENT;
        }

        return new JsonResponse($doctor, $this->statusCode);
    }

    /**
     * @Route("/doctor/{id}", methods={"PUT"})
     */
    public function updateAction(int $id, Request $request): Response
    {
        $data = $request->getContent();

        $doctorData = $this->doctorFactory->mutateDoctor($data);

        $doctor = $this->getDoctor($id);

        if (is_null($doctor)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $doctor->crm    = $doctorData->crm;
        $doctor->name   = $doctorData->name;

        $this->entityManager->flush();

        return new JsonResponse($doctor);

    }

    /**
     * @Route("/doctor/{id}", methods={"DELETE"})
     */
    public function deleteAction($id): Response
    {
        $doctor = $this->getDoctor($id);

        if (is_null($doctor)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($doctor);
        $this->entityManager->flush();

        return new JsonResponse('Doctor successfully removed!', Response::HTTP_OK);
    }

    private function getDoctor(int $id)
    {
        $doctorRepository = $this->getDoctrine()->getRepository(Doctor::class);

        $doctor = $doctorRepository->find($id);

        return $doctor;
    }
}
?>