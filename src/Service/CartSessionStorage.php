<?php

namespace App\Service;

// use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * Class CartSessionStorage
 * @package App\Service
 */
class CartSessionStorage
{
    /*
    * The request stack.
    *
    * @var RequestStack
    */
   private $requestStack;

   /**
    * The cart repository.
    *
    * @var OrderRepository
    */
    private $cartRepository;
    
    private $session;
    private $orderRepository;
    const CART_KEY_NAME = 'cart_id';

    // public function __construct(SessionInterface $session, OrderRepository $orderRepository)
    public function __construct(RequestStack $requestStack, OrderRepository $cartRepository)
    {
        // $this->session = $session;
        // $this->orderRepository = $orderRepository;
        $this->requestStack = $requestStack;
        $this->cartRepository = $cartRepository;
    }

     /**
     * Gets the cart in session.
     *
     * @return Order|null
     */
    public function getCart(): ?Order
    {
        return $this->cartRepository->findOneBy([
            'id' => $this->getCartId(),
            'status' => Order::STATUS_CART
        ]);
    }

    /**
     * Sets the cart in session.
     *
     * @param Order $cart
     */
    public function setCart(Order $cart): void
    {
        $this->requestStack->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    /**
     * Returns the cart id.
     *
     * @return int|null
     */
    private function getCartId(): ?int
    {
        return $this->requestStack->getSession()->get(self::CART_KEY_NAME);
    }

    // private function getSession(): SessionInterface
    // {
    //     return $this->requestStack->getSession();
    // }

    // Implement the setCart() and getCart() methods here
}
