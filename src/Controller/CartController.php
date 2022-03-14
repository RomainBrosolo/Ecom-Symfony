<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\Security\Core\Security;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $panier = $session->get("panier", []);

        $data = [];
        $total = 0;

        foreach($panier as $id => $quantity){
            $product = $productRepository->find($id);
            $data[] = [
                "product" => $product,
                "quantity" => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('cart/index.html.twig', [
            'total' => $total,
            'data' => $data
        ]);
    }

    #[Route('/add/{id<\d+>}', name: 'app_cart_add')]
    public function add($id, SessionInterface $session, Product $product): Response
    {
        $panier = $session->get("panier", []);
        $id = $product-> getId();

        if (!empty($panier[$id])){
            $panier[$id]++;
        }
        else {
            $panier[$id]= 1;
        }

        $session->set("panier", $panier);
        
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/remove/{id<\d+>}', name: 'app_cart_remove')]
    public function remove($id, SessionInterface $session, Product $product): Response
    {
        $panier = $session->get("panier", []);
        $id = $product-> getId();

        if (!empty($panier[$id])){
            if ($panier[$id]>1) {
                $panier[$id]-- ;
            } 
            else {
                unset($panier[$id]);
            }  
        }

        $session->set("panier", $panier);
        
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/delete/{id<\d+>}', name: 'app_cart_delete')]
    public function delete($id, SessionInterface $session, Product $product): Response
    {
        $panier = $session->get("panier", []);
        $id = $product-> getId();

        if (!empty($panier[$id])){
            unset($panier[$id]);  
        }

        $session->set("panier", $panier);
        
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/checkout', name: 'app_cart_checkout')]
    public function checkout(SessionInterface $session, Security $security, ProductRepository $productRepository): Response
    {
        $panier = $session->get("panier", []);
        $data = [];
        $total = 0;

        foreach($panier as $id => $quantity){
            $product = $productRepository->find($id);
            $data[] = [
                "product" => $product,
                "quantity" => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        $user = $security->getUser();

        if (!empty($panier) && $user){
            return $this->render('cart/checkout.html.twig', [
                'total' => $total,
                'data' => $data
            ]); 
        }
        else {
            return $this->redirectToRoute("app_login");
        }
    }

    #[Route('/order', name: 'app_cart_order')]
    public function order(SessionInterface $session): Response
    {
        $session->set("panier", []);

        return $this->redirectToRoute('app_index');
    }

    
}
