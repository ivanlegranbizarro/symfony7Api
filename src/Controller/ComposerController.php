<?php

namespace App\Controller;

use App\Entity\Composer;
use App\Repository\ComposerRepository;
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
class ComposerController extends AbstractController
{
    #[Route('/composers', name: 'app_composers_index')]
    public function index(ComposerRepository $composerRepository): JsonResponse
    {
        $composers = $composerRepository->findAll();

        return $this->json($composers);
    }

    #[Route('/composers/{id}', name: 'app_composers_show')]
    public function show(Composer $composer): JsonResponse
    {

        return $this->json($composer);
    }

    #[Route('/composers/create', name: 'app_composers_create')]
    public function create(SerializerInterface $serializer, ComposerRepository $composerRepository, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json');

        $errors = $validator->validate($composer);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $composerRepository->save($composer, true);

        return $this->json($composer, 201);
    }

    #[Route('/composers/{id}/update', name: 'app_composers_update')]
    public function update(Composer $composer, SerializerInterface $serializer, ComposerRepository $composerRepository, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $composer
        ]);

        $errors = $validator->validate($composer);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $composerRepository->save($composer, true);

        return $this->json($composer, 200);
    }

    #[Route('/composers/{id}/delete', name: 'app_composers_delete')]
    public function delete(Composer $composer, ComposerRepository $composerRepository): JsonResponse
    {
        $composerRepository->remove($composer, true);

        return $this->json(null, 204);
    }
}
