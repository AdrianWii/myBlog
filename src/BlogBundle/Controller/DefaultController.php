<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Post;
use BlogBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\CommentType;
use BlogBundle\Form\PostType;

class DefaultController extends Controller {

    /**
     * @Route("/", name="start_blog")
     */
    public function indexAction(Request $request) {
        $qb = $this->getDoctrine()
                ->getManager()
                ->createQueryBuilder()
                ->from('BlogBundle:Post', 'p')
                ->select('p');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $qb, $request->query->get('page', 1), 5
        );

        return $this->render('BlogBundle:Default:index.html.twig', array('posts' => $pagination));
    }

    /**
     * @Route("/article/{id}", name="post_show")
     */
    public function showAction(Post $post, Request $request) {
        $form = null;
        //if user is logged in
                    $comment = new Comment();
            $comment->setPost($post);
        if ($user = $this->getUser()) {

            $comment->setUser($user);


        }
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $dm = $this->getDoctrine()->getManager();
                $dm->persist($comment);
                $dm->flush();

                $this->addFlash('success', 'Your comment was succesfully add!');
                return $this->redirectToRoute('post_show', array('id' => $post->getId()));
            }
        return $this->render('BlogBundle:Default:show.html.twig', array(
                    'post' => $post,
                    'form' => is_null($form) ? $form : $form->createView()
        ));
    }

    /**
     * @Route("/admin/post", name="post_add")
     */
    public function postAction(Request $request) {
    $form = null;
        //if user is logged in
        if ($user = $this->getUser()) {
            $post = new Post();
            $post->setUser($user);
            
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $dm = $this->getDoctrine()->getManager();
                $dm->persist($post);
                $dm->flush();

                $this->addFlash('success', 'Your post was succesfully add!');
                return $this->redirectToRoute('post_add', array('id' => $post->getId()));
            }
        }
        return $this->render('BlogBundle:Default:post.html.twig', array(
                    'form' => is_null($form) ? $form : $form->createView()
        ));
    }
}
