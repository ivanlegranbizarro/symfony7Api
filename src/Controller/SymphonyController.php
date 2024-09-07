<?php

namespace App\Controller;

use App\Entity\Symphony;
use OpenApi\Attributes as OA;
use App\Repository\SymphonyRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;

#[OA\Tag(name: 'Symphony')]
#[IsGranted('ROLE_USER')]
#[Route('/api')]
class SymphonyController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Get all symphonies',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: Symphony::class)))
    )]
    #[Route('/symphonies', name: 'app_symphonies_index', methods: ['GET'])]
    public function index(SymphonyRepository $symphonyRepository): JsonResponse
    {
        $symphonies = $symphonyRepository->findAll();
        return $this->json($symphonies);
    }

    #[OA\Response(
        response: 200,
        description: 'Get one symphony',
        content: new OA\JsonContent(ref: new Model(type: Symphony::class))
    )]
    #[Route('/symphonies/{id}', name: 'app_symphonies_show', methods: ['GET'])]
    public function show(Symphony $symphony): JsonResponse
    {
        return $this->json($symphony);
    }

    #[OA\RequestBody(
        description: 'Create a new symphony',
        content: new OA\JsonContent(ref: new Model(type: Symphony::class))
    )]
    #[OA\Response(
        response: 201,
        description: 'Create a new symphony',
        content: new OA\JsonContent(ref: new Model(type: Symphony::class))
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[Route('/symphonies/create', name: 'app_symphonies_create', methods: ['POST'])]
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

    #[OA\RequestBody(
        description: 'Update a symphony',
        content: new OA\JsonContent(ref: new Model(type: Symphony::class))
    )]
    #[OA\Response(
        response: 200,
        description: 'Update a symphony',
        content: new OA\JsonContent(ref: new Model(type: Symphony::class))
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[Route('/symphonies/{id}/update', name: 'app_symphonies_update', methods: ['PUT'])]
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

    #[OA\Response(
        response: 204,
        description: 'Delete a symphony'
    )]
    #[Route('/symphonies/{id}/delete', name: 'app_symphonies_delete', methods: ['DELETE'])]
    public function delete(Symphony $symphony, SymphonyRepository $symphonyRepository): JsonResponse
    {
        $symphonyRepository->remove($symphony, true);
        return $this->json(null, 204);
    }
}
