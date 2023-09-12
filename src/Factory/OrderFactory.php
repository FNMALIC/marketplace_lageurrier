<?php

namespace App\Factory;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Class OrderFactory
 * @package App\Factory
 */
class OrderFactory
{
    private $entityManager;

    /**
     * CartManager constructor.
     *
     * @param CartSessionStorage $cartStorage
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }
    /**
     * Creates an order.
     *
     * @return Order
     */
    public function create(): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setTotalPrice(0)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        // dd($order);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;

    }

    /**
     * Creates an item for a product.
     *
     * @param Product $product
     *
     * @return OrderItem
     */
    public function createItem(Product $product): OrderItem
    {
        $item = new OrderItem();
        $item->setProduct($product);
        $item->setQuantity(1);

        return $item;
    }
}