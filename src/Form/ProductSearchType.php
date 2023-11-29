<?php
namespace App\Form;

use App\Model\ProductSearchData;
use symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{

     public function builderForm(FormBuilderInterface $builder , array $options)
     {
        $builder ->add('q',TextType::class, [
            'attr' => [
                'placeholder' => 'Recherche via un mot cle'
            ]
            ]);
     
        }
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                // 'data_class' => Product::class,
                'data_class' => ProductSearchData::class,
                'method' => 'GET',
                'csrf_production' => false
            ]);
        }

}

