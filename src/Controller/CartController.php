<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\CartManager;
use App\Form\CartType;
// use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass;
use Twig\Environment;

class CartController extends AbstractController
{


    /**
     * @var \Twig\Environment
     */
    private $twig;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $manager;

    public function __construct( Environment $twig ) {
        $this->twig    = $twig;
    }

    #[Route('/cart', name: 'app_cart')]
        public function index(CartManager $cartManager, Request $request ,SessionInterface $session): Response
        {
            $cart = $cartManager->getCurrentCart();
            $form = $this->createForm(CartType::class, $cart);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // dd($cart);
                $cart->setUpdatedAt(new \DateTime());
                $cartManager->save($cart);

                return $this->redirectToRoute('app_cart');
            }
            // $cartTotal = $cart->getTotal();
            // Assuming $cartTotal is the total amount in the cart
            // $session->set('cart_total', $cartTotal);

                        // Check if the cart is empty
            if ($cart->getItems()->isEmpty()) {
                // If the cart is empty, set the cart total to zero
                $session->set('cart_total', 0);
            } else {
                // If the cart is not empty, set the cart total to the actual total
                $session->set('cart_total', $cart->getTotal());
            }

            // dd(count($cart->getItems()));
            // $twig = new Environment($loader);
            // $twig->addGlobal('myStuff', $someVariable);
            // $this->twig->addGlobal('cart_items', $cart->getItems());
            // $this->container->get('twig')->addGlobal('cart_items', $cart->getItems());

// dd(0);
// dd($cart);
            return $this->render('cart/index.html.twig', [
                'cart' => $cart,
                'form' => $form->createView()
            ]);
        }
}
