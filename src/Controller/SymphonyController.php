<?php

namespace App\Controller;

use App\Entity\Symphony;
use App\Repository\SymphonyRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('ROLE_USER')]
#[Route('/api')]
class SymphonyController extends AbstractController
{
    #[Route('/symphonies', name: 'app_symphonies_index')]
    public function index(SymphonyRepository $symphonyRepository): JsonResponse
    {
        $symphonies = $symphonyRepository->findAll();

        return $this->json($symphonies);
    }

    #[Route('/symphonies/{id}', name: 'app_symphonies_show')]
    public function show(Symphony $symphony): JsonResponse
    {
        return $this->json($symphony);
    }

    #[Route('/symphonies/create', name: 'app_symphonies_create')]
    public function create(SerializerInterface $serializer, SymphonyRepository $symphonyRepository, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $symphony = $serializer->deserialize($request->getContent(), Symphony::class, 'json');

        $errors = $validator->validate($symphony);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $symphonyRepository->save($symphony, true);

        return $this->json($symphony, 201);
    }

    #[Route('/symphonies/{id}/update', name: 'app_symphonies_update')]
    public function update(Symphony $symphony, SerializerInterface $serializer, SymphonyRepository $symphonyRepository, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $symphony = $serializer->deserialize($request->getContent(), Symphony::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $symphony
        ]);

        $errors = $validator->validate($symphony);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $symphonyRepository->save($symphony, true);

        return $this->json($symphony, 200);
    }

    #[Route('/symphonies/{id}/delete', name: 'app_symphonies_delete')]
    public function delete(Symphony $symphony, SymphonyRepository $symphonyRepository): JsonResponse
    {
        $symphonyRepository->remove($symphony, true);

        return $this->json(null, 204);
    }
}
