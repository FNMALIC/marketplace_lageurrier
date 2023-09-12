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



    public function __construct(private PostRepository $postRepository,private EntityManagerInterface $entityManager)
    {

        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }
        
    }


    #[Route('/', name: 'home')]
    public function index( Request $request): Response
    {

        
       

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        // $user = ; // Get the currently logged in user

        if (!$this->getUser()) {
            // If there is no logged in user, redirect to the login page
            return $this->redirectToRoute('app_login');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // $this->entityManager->persist($post);
            // $this->entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }
        // $user =$this->getUser();
        // dd($user);
        return $this->render('post/index.html.twig', [
            'posts' => $this->postRepository->findAll(),
            // 'user' => $user
            'form' => $form,
        ]);

    }

    #[Route('/profile_setting', name: 'profile_setting')]
    public function profile_setting(): Response
    {
        return $this->render('navigation/profile_setting.html.twig', [
            'controller_name' => 'NavigationController',
        ]);
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        return $this->render('navigation/profile.html.twig', [
            'controller_name' => 'NavigationController',
        ]);
    }
}

