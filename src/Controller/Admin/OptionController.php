<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/option')]
class OptionController extends AbstractController
{
    private OptionRepository       $optionRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        OptionRepository       $optionRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->optionRepository = $optionRepository;
        $this->entityManager    = $entityManager;
    }

    #[Route('/', name: 'admin_option_index')]
    public function index(): Response
    {
        $options = $this->optionRepository->findAll();

        return $this->render('admin/option/index.html.twig', [
            'options' => $options,
        ]);
    }

    #[Route('/{id}', name: 'admin_option_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $option = $this->optionRepository->find($id);

        return $this->render('admin/option/show.html.twig', [
            'option' => $option,
        ]);
    }

    #[Route('/new', name: 'admin_option_new')]
    public function new(Request $request): Response
    {
        $option = new Option();

        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($option);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_option_index');
        }

        return $this->render('admin/option/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_option_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $option = $this->optionRepository->find($id);

        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_option_index');
        }

        return $this->render('admin/option/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_option_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $option = $this->optionRepository->find($id);

        $this->entityManager->remove($option);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_option_index');
    }
}