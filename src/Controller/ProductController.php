<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Annotation\IsGranted;
use App\Manager\CartManager;
use App\Form\AddToCartType;
use App\Model\ProductSearchData;
use App\Model\ProductSearchDate;
use App\Repository\StoreRepository;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $user = $this->getUser(); // Get the currently logged in user
        // $productSearchData = new ProductSearchData();

        // form  =  $this->createForm(ProductSearchData)
        
        if (!$user) {
            // If there is no logged in user, redirect to the login page
            return $this->redirectToRoute('login');
        }
        // dd(0);
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[IsGranted("ROLE_SELLER")]
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StoreRepository $storeRepository,UserInterface $user, EntityManagerInterface $entityManager): Response
    {
       $product = new Product();
       
       $form = $this->createForm(ProductType::class, $product);
    
       $form->handleRequest($request);
    
       $route = $request->headers->get('referer');
       if ($form->isSubmitted() && $form->isValid()) {
           $storeId = $storeRepository->findBy(array('id' => $_GET['id']));
           $imageFile = $form['imageFilename']->getData();
           if ($imageFile) {
               $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
               $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
               $imageFile->move(
                   $this->getParameter('kernel.project_dir') . '/public' . $this->getParameter('images_directory'),
                   $newFilename
               );
               $product->setImageFilename($newFilename);
           }
           $data = $form->getData();
    
           $product->setStore($storeId[0]);
           $product->setUser($user);
           $entityManager->persist($product);
           $entityManager->flush();
    
           return $this->redirect($route);
       }
    
       return $this->render('product/new.html.twig', [
           'product' => $product,
           'form' => $form,
       ]);
    }


    #[Route('/{id}', name: 'app_product_show', methods: ['GET', 'POST'])]
    public function show(Product $product, Request $request, CartManager $cartManager): Response
    {
        // dd(0);
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        // dd($form->isValid());

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $item = $form->getData();

                $item->setProduct($product);

                $cart = $cartManager->getCurrentCart();
                $cart
                    ->addItem($item)
                    ->setUpdatedAt(new \DateTime());

                // dd(0);

                $cartManager->save($cart);

                return $this->redirectToRoute('app_product_index', ['id' => $product->getId()]);
            }
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    #[IsGranted("ROLE_SELLER")]
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
