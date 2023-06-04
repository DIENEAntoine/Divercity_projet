<?php

namespace App\Controller\Visitor\Event;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentFormType;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('/event/all-events', name: 'visitor.events.index')]
    public function index(
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        PostRepository $postRepository,
        ): Response
        {
            $categories  = $categoryRepository->findAll();
            $tags        = $tagRepository->findAll();
            $posts       = $postRepository->findBy(['isPublished' => true]);
            
            
            return $this->render('pages/visitor/event/index.html.twig', compact('categories', 'tags', 'posts'));
        }
       
       
        #[Route('/event/posts/{id<\d+>}/{slug}', name: 'visitor.event.post.show')]
        public function show(Post $post, 
        Request $request, 
        CommentRepository $commentRepository,
        ): Response
        {
            $comment = new Comment();
            $form = $this->createForm(CommentFormType::class, $comment);

            $form->handleRequest($request);

            if ( $form->isSubmitted() && $form->isValid() ) 
            {
                $comment->setPost($post);
                $comment->setUser($this->getUser());
                $commentRepository->save($comment, true);

                $this->addFlash("success", "Le commentaire à été envoyé!");
                return $this->redirectToRoute("visitor.event.post.show", [
                    "id"    => $post->getId(),
                    "slug"  => $post->getSlug()
                ]);
            }

            return $this->render("pages/visitor/event/show.html.twig", [
                'post' => $post,
                'form' => $form->createView(),
            ]);
        }


        #[Route('/event/posts/filter_by_category/{id<\d+>}/{slug}', name: 'visitor.event.posts.filter_by_category')]
        public function filterByCategory(
            Category $category,
            CategoryRepository $categoryRepository,
            TagRepository $tagRepository,
            PostRepository $postRepository
            ): Response
        {

            $categories = $categoryRepository->findAll();
            $tags = $tagRepository->findAll();
            $posts = $postRepository->filterPostsByCategory($category->getId());
       

            return $this->render("pages/visitor/event/index.html.twig", compact('categories', 'tags', 'posts'));
        }


        #[Route('/blog/posts/filter_by_tag/{id<\d+>}/{slug}', name: 'visitor.event.posts.filter_by_tag')]    
    public function filterPostsByTag(
        Tag $tag,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        PostRepository $postRepository
        ) : Response
    {

        $categories = $categoryRepository->findAll();
        $tags       = $tagRepository->findAll();
        $posts       = $postRepository->filterPostsByTag($tag);
        

        return $this->render('pages/visitor/event/index.html.twig', compact('categories', 'tags', 'posts'));

    }


        #[Route('/event/all-events', name: 'visitor.cancel_filter')]
        public function cancel(
            CategoryRepository $categoryRepository,
            TagRepository $tagRepository,
            PostRepository $postRepository,
        ): Response
        {
            $categories  = $categoryRepository->findAll();
            $tags        = $tagRepository->findAll();
            $posts       = $postRepository->findBy(['isPublished' => true]);
            
            
            return $this->render('pages/visitor/event/index.html.twig', compact('categories', 'tags', 'posts'));
        }


}
