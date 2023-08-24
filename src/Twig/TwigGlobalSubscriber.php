<?php
namespace App\Twig;

use Symfony\Component\HttpKernel\KernelEvents;
use App\Manager\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class TwigGlobalSubscriber implements EventSubscriberInterface {
    private $twig;
    private $manager;
    private $cartManager;

    public function __construct(CartManager $cartManager,Environment $twig,EntityManagerInterface $manager) {
        $this->twig = $twig;
        $this->manager = $manager;
        $this->cartManager = $cartManager;
    }

    public function injectGlobalVariables(ControllerEvent $event,String $cartManager) {
        // $whatYouWantAsGlobal = ;
        // $cart = $cartManager->getCurrentCart();
        
        $numberOfItemInCart = count($this->cartManager->getCurrentCart()->getItems());
        $this->twig->addGlobal('cart_items', $numberOfItemInCart);
    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::CONTROLLER => 'injectGlobalVariables',
        ];
    }
}