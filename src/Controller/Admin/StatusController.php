<?php

namespace App\Controller\Admin;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/status')]
class StatusController extends AbstractController
{
    private StatusRepository        $statusRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        StatusRepository        $statusRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->statusRepository = $statusRepository;
        $this->entityManager   = $entityManager;
    }

    #[Route('/', name: 'admin_status_index')]
    public function index(): Response
    {
        $statuses = $this->statusRepository->findAll();

        return $this->render('admin/status/index.html.twig', [
            'statuses' => $statuses,
        ]);
    }

    #[Route('/{id}', name: 'admin_status_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $status = $this->statusRepository->find($id);

        return $this->render('admin/status/show.html.twig', [
            'status' => $status,
        ]);
    }

    #[Route('/new', name: 'admin_status_new')]
    public function new(Request $request): Response
    {
        $status = new Status();

        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($status);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_status_index');
        }

        return $this->render('admin/status/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_status_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $status = $this->statusRepository->find($id);

        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_status_index');
        }

        return $this->render('admin/status/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_status_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $status = $this->statusRepository->find($id);

        $this->entityManager->remove($status);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_status_index');
    }
}