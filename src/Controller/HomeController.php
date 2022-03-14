<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'last_sneakers' => $productRepository->findBy(['category' => "Sneakers"],[],4),
            'last_accessories' => $productRepository->findBy(['category' => "Bags"],[],4),
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function template(): Response {
        return $this->render('template.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response {
        return $this->render('/home/contact.html.twig');
    }
}
