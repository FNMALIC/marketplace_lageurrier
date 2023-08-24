<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
class NavigationController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository,Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser(); // Get the currently logged in user

        if (!$user) {
            // If there is no logged in user, redirect to the login page
            return $this->redirectToRoute('app_login');
        }

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }
        // $user =$this->getUser();
        // dd($user);
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            // 'user' => $user
            'form' => $form,
        ]);

    }

    #[Route('/profile_setting', name: 'profile_setting')]
    public function profile(): Response
    {
        return $this->render('navigation/profile_setting.html.twig', [
            'controller_name' => 'NavigationController',
        ]);
    }
}
