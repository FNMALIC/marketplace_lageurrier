<?php

namespace App\Form;

use App\Entity\Order;
// use App\EventListener\RemoveCartItemListener as EventListenerRemoveCartItemListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\RemoveCartItemListener;
// use APP\Form\EventListener\RemoveCartItemListener;
// use App\EventListener\RemoveCartItemListener;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('ordernumber')
            // ->add('totalPrice')
            // ->add('status')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('buyer')
            // ->add('product')
            ->add('items', CollectionType::class, [
                'entry_type' => CartItemType::class
            ])
            ->add('save', SubmitType::class)
            ->add('clear', SubmitType::class);
            // ->addEventSubscriber(new RemoveCartItemListener());
            // dd(new RemoveCartItemListener());
            $builder->addEventSubscriber(new RemoveCartItemListener());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
