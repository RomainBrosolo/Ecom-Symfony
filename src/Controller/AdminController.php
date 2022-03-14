<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/admin/products', name: 'app_admin_products')]
    public function products(ProductRepository $productRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
    
    
}
