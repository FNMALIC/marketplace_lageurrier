<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvents;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('quantity')
            ->add('imageFilename', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
            ])
            ->add('category')
            ->add('store',HiddenType::class)
            // ->add('orders')
        ;
        // $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
        //     $data = $event->getData();
        //     $form = $event->getForm();

        //     dd($data);
        
        //     if (isset($data['field1'])) {
        //         $field2 = $this->container->get('repository')->find($data['field1'])->getValue();
        
        //         $data['field2'] = $field2;              
        //         $event->setData($data); 
        //     }
        // });
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
