<?php

namespace App\Manager;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Service\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * Class CartManager
 * @package App\Manager
 */
class CartManager
{
    /**
     * @var CartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var OrderFactory
     */
    private $cartFactory;

    private $entityManager;
    const CART_KEY_NAME = 'cart_id';
    private $requestStack;


    /**
     * CartManager constructor.
     *
     * @param CartSessionStorage $cartStorage
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        CartSessionStorage $cartStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    /**
     * Gets the current cart.
     * 
     * @return Order
     */
    public function getCurrentCart(): Order
    {
        $id = $this->requestStack->getSession()->get(self::CART_KEY_NAME);
        $cart = null;
        if ($id) {
            $cart = $this->entityManager->getRepository(Order::class)->find($id);
        }
        if (!$cart) {
            $cart = $this->cartFactory->create();
            $this->cartSessionStorage->setCart($cart);
            // dd($this->requestStack->getSession()->get(self::CART_KEY_NAME));   // This will save the new Cart id in session
        }
        // dd($cart->getItems());
        return $cart;
        // return $cart;
    }

     /**
     * Persists the cart in database and session.
     *
     * @param Order $cart
     */
    public function save(Order $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }


    public function resetCart(): void
{
    // Get the current cart
    $currentCart = $this->getCurrentCart();

    // Create a new cart
    $cart = $this->cartFactory->create();

// Store the ID of the current cart
$currentCartId = $currentCart->getId();

    // dd($this->entityManager->remove($currentCart));
    // Remove the old cart from the database
    $this->entityManager->remove($currentCart);

    // Persist the new cart in the database
    $this->entityManager->persist($cart);
    $this->entityManager->flush();
    // Try to find the cart in the database
    $removedCart = $this->entityManager->getRepository(Order::class)->find($currentCartId);

    if ($removedCart === null) {
        // The cart was successfully removed
        // dd('The cart was successfully removed');
    } else {
        // The cart was not removed
        // dd('The cart was not removed');
    }

    // Set the new cart in the session
    $this->cartSessionStorage->setCart($cart);
}

}