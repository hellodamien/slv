<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/brand')]
class BrandController extends AbstractController
{
    private BrandRepository        $brandRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        BrandRepository        $brandRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->brandRepository = $brandRepository;
        $this->entityManager   = $entityManager;
    }

    #[Route('/', name: 'admin_brand_index')]
    public function index(): Response
    {
        $brands = $this->brandRepository->findAll();

        return $this->render('admin/brand/index.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/{id}', name: 'admin_brand_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $brand = $this->brandRepository->find($id);

        return $this->render('admin/brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/new', name: 'admin_brand_new')]
    public function new(Request $request): Response
    {
        $brand = new Brand();

        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($brand);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_brand_index');
        }

        return $this->render('admin/brand/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_brand_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $brand = $this->brandRepository->find($id);

        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_brand_index');
        }

        return $this->render('admin/brand/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_brand_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $brand = $this->brandRepository->find($id);

        $this->entityManager->remove($brand);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_brand_index');
    }
}