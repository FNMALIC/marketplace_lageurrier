<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Factory\ControllerFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
class ProductCrudController extends AbstractCrudController
{

    private RequestStack $requestStack;
    private EntityFactory $entityFactory;
    private ControllerFactory $controllerFactory;

    public function __construct(RequestStack $requestStack, EntityFactory $entityFactory, ControllerFactory $controllerFactory)
    {
        $this->requestStack = $requestStack;
        $this->entityFactory = $entityFactory;
        $this->controllerFactory = $controllerFactory;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id')
                // ->setDisabled(),
            TextField::new('title'),
            // TextField::new('price'),
            TextField::new('user', 'User')
                ->formatValue(function ($value, $entity) {
                   
                    // Customize how the user is displayed (e.g., show the username)
                    return $entity->getUser()->getEmail();
                }),
        ];
    }
    
}
