<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\OrderItem;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{

    const STATUS_CART = 'cart';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ordernumber = null;

    #[ORM\Column]
    private ?float $totalPrice = null;

    #[ORM\Column(length: 100)]
    private ?string $status = self::STATUS_CART;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $buyer = null;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'orders')]
    private Collection $product;

    #[ORM\OneToMany(mappedBy: 'paymentOrder', targetEntity: Payment::class)]
    private Collection $payments;


    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: "orderRef", cascade: ["persist", "remove"], orphanRemoval: true)]
    private  $items;
    

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;


    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->ordernumber = $this->generateOrderNumber();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdernumber(): ?int
    {
        return $this->ordernumber;
    }

    public function setOrdernumber(int $ordernumber): static
    {
        $this->ordernumber = $ordernumber;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): static
    {
        $this->buyer = $buyer;

        return $this;
    }



    /**
     * Get the value of items
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @return  self
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->product->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setPaymentOrder($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getPaymentOrder() === $this) {
                $payment->setPaymentOrder(null);
            }
        }

        return $this;
    }


    public function removeItem(OrderItem $item): self
    {
       // Check if the item is already in the collection
            if (!$this->items->contains($item)) {
                return $this;
            }
 

            // Remove the item from the collection
            $this->items->removeElement($item);
// dd(!$this->items->contains($item));
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderRef() === $this) {
                $item->setOrderRef(null);
            }
        }

        return $this;
    }


    // TODO: to debug
    public function addItem(OrderItem $item): self
    {
        // dd($this->getItems());
        foreach ($this->getItems() as $existingItem) {
            // The item already exists, update the quantity
            $existingItem->equals($item);
            if ($existingItem->equals($item)) {
                $existingItem->setQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                );
                return $this;
            }
        }

        $this->items[] = $item;
        $item->setOrderRef($this);

        // dd($this);
        return $this;
    }

    /**
     * Removes all items from the order.
     *
     * @return $this
     */
    public function removeItems(): self
    {
        foreach ($this->getItems() as $item) {
            $this->removeItem($item);
        }

        return $this;
    }

    /**
     * Calculates the order total.
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    private function generateOrderNumber(): int
    {
        // Implement your logic to generate a unique order number
        // For example, you can use a timestamp concatenated with a random number.
        return time() . mt_rand(1000, 9999);
    }

}
