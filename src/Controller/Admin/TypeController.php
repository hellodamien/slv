<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/type')]
class TypeController extends AbstractController
{
    private TypeRepository        $typeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        TypeRepository        $typeRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->typeRepository = $typeRepository;
        $this->entityManager   = $entityManager;
    }

    #[Route('/', name: 'admin_type_index')]
    public function index(): Response
    {
        $types = $this->typeRepository->findAll();

        return $this->render('admin/type/index.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/{id}', name: 'admin_type_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $type = $this->typeRepository->find($id);

        return $this->render('admin/type/show.html.twig', [
            'type' => $type,
        ]);
    }

    #[Route('/new', name: 'admin_type_new')]
    public function new(Request $request): Response
    {
        $type = new Type();

        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($type);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_type_index');
        }

        return $this->render('admin/type/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_type_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $type = $this->typeRepository->find($id);

        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_type_index');
        }

        return $this->render('admin/type/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_type_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $type = $this->typeRepository->find($id);

        $this->entityManager->remove($type);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_type_index');
    }
}