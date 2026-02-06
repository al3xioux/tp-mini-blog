<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\UserCommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

final class PublicPostController extends AbstractController
{
    #[Route('/posts', name: 'app_public_post_index')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('public_post/index.html.twig', [
            'posts' => $postRepository->findBy([], ['publishedAt' => 'DESC']),
        ]);
    }

    #[Route('/article/{id}', name: 'app_public_post_show', methods: ['GET', 'POST'])]
    public function show(
        Post $post,
        Request $request,
        EntityManagerInterface $entityManager,
        CommentRepository $commentRepository
    ): Response {
        // Récupérer les commentaires approuvés
        $comments = $commentRepository->findApprovedByPost($post->getId());

        // Formulaire de commentaire pour les utilisateurs connectés
        $comment = new Comment();
        $form = null;

        if ($this->getUser()) {
            $form = $this->createForm(UserCommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setUser($this->getUser());
                $comment->setPost($post);
                $comment->setCreatedAt(new \DateTime());
                $comment->setStatus('pending');

                $entityManager->persist($comment);
                $entityManager->flush();

                $this->addFlash('success', 'Votre commentaire a été soumis et sera affiché après validation par un administrateur.');

                return $this->redirectToRoute('app_public_post_show', ['id' => $post->getId()]);
            }
        }

        return $this->render('public_post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $form?->createView(),
        ]);
    }
}
