<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/comments')]
final class AdminCommentController extends AbstractController
{
    #[Route('', name: 'app_admin_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('admin_comment/index.html.twig', [
            'pendingComments' => $commentRepository->findPending(),
            'allComments' => $commentRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/{id}/approve', name: 'app_admin_comment_approve', methods: ['POST'])]
    public function approve(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setStatus('approved');
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été approuvé.');

        return $this->redirectToRoute('app_admin_comment_index');
    }

    #[Route('/{id}/reject', name: 'app_admin_comment_reject', methods: ['POST'])]
    public function reject(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setStatus('rejected');
        $entityManager->flush();

        $this->addFlash('warning', 'Le commentaire a été rejeté.');

        return $this->redirectToRoute('app_admin_comment_index');
    }

    #[Route('/{id}/delete', name: 'app_admin_comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été supprimé.');

        return $this->redirectToRoute('app_admin_comment_index');
    }
}
