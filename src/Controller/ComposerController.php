<?php

namespace App\Controller;

use App\Entity\Composer;
use App\Repository\ComposerRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Composer')]
#[IsGranted('ROLE_USER')]
#[Route('/api')]
class ComposerController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Get all composers',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Composer::class))
        )
    )]
    #[Route('/composers', name: 'app_composers_index', methods: ['GET'])]
    public function index(ComposerRepository $composerRepository, SerializerInterface $serializer): JsonResponse
    {
        $composers = $composerRepository->findAll();

        return $this->json($serializer->normalize($composers, null, ['groups' => 'read']));
    }

    #[OA\RequestBody(
        required: true,
        description: 'Composer data for creation',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'firstName', type: 'string', description: 'First name of the composer'),
                new OA\Property(property: 'lastName', type: 'string', description: 'Last name of the composer'),
                new OA\Property(property: 'dateOfBirth', type: 'string', format: 'date', description: 'Birth date of the composer in YYYY-MM-DD format'),
                new OA\Property(property: 'countryCode', type: 'string', maxLength: 2, description: 'ISO 3166-1 alpha-2 country code')
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Create a new composer',
        content: new OA\JsonContent(ref: new Model(type: Composer::class))
    )]
    #[Route('/composers/create', name: 'app_composers_create', methods: ['POST'])]
    public function create(SerializerInterface $serializer, ComposerRepository $composerRepository, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', ['groups' => 'create']);

        $errors = $validator->validate($composer);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $composerRepository->save($composer, true);

        return $this->json($composer, 201, [], ['groups' => 'read']);
    }

    #[OA\RequestBody(
        required: true,
        description: 'Composer data for updating',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'firstName', type: 'string', description: 'First name of the composer'),
                new OA\Property(property: 'lastName', type: 'string', description: 'Last name of the composer'),
                new OA\Property(property: 'dateOfBirth', type: 'string', format: 'date', description: 'Birth date of the composer in YYYY-MM-DD format'),
                new OA\Property(property: 'countryCode', type: 'string', maxLength: 2, description: 'ISO 3166-1 alpha-2 country code')
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Update an existing composer',
        content: new OA\JsonContent(ref: new Model(type: Composer::class))
    )]
    #[Route('/composers/{id}/update', name: 'app_composers_update', methods: ['PUT'])]
    public function update(Composer $composer, SerializerInterface $serializer, ComposerRepository $composerRepository, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $composer,
            'groups' => 'update'
        ]);

        $errors = $validator->validate($composer);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $composerRepository->save($composer, true);

        return $this->json($composer, 200, [], ['groups' => 'read']);
    }

    #[OA\Response(
        response: 204,
        description: 'Delete a composer'
    )]
    #[Route('/composers/{id}/delete', name: 'app_composers_delete', methods: ['DELETE'])]
    public function delete(Composer $composer, ComposerRepository $composerRepository): JsonResponse
    {
        $composerRepository->remove($composer, true);

        return $this->json(null, 204);
    }
}
