<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;  
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\CartManager;
use Stripe;


class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(SessionInterface $session): Response
    {
        $cartTotal = $session->get('cart_total');
        // dd();

        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'cartTotal' => $cartTotal
        ]);
    }
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, SessionInterface $session,CartManager $cartManager)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        
        $cartTotal = $session->get('cart_total');

        Stripe\Charge::create ([
                "amount" => $cartTotal,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
        ]);
        
        $cartManager->resetCart();

        $this->addFlash(
            'success',
            'Payment Successful!'
        );


        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
