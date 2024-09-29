<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/model')]
class ModelController extends AbstractController
{
    private ModelRepository        $modelRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        ModelRepository        $modelRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->modelRepository = $modelRepository;
        $this->entityManager   = $entityManager;
    }

    #[Route('/', name: 'admin_model_index')]
    public function index(): Response
    {
        $models = $this->modelRepository->findAll();

        return $this->render('admin/model/index.html.twig', [
            'models' => $models,
        ]);
    }

    #[Route('/{id}', name: 'admin_model_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $model = $this->modelRepository->find($id);

        return $this->render('admin/model/show.html.twig', [
            'model' => $model,
        ]);
    }

    #[Route('/new', name: 'admin_model_new')]
    public function new(Request $request): Response
    {
        $model = new Model();

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($model);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_model_index');
        }

        return $this->render('admin/model/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_model_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $model = $this->modelRepository->find($id);

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_model_index');
        }

        return $this->render('admin/model/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_model_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $model = $this->modelRepository->find($id);

        $this->entityManager->remove($model);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_model_index');
    }
}