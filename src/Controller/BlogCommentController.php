<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\BlogComment;
use App\Form\BlogCommentType;
use App\Repository\BlogCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BlogRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/blog/comment")
 */
class BlogCommentController extends AbstractController
{
    /**
     * @Route("/", name="blog_comment_index", methods="GET")
     */
    public function index(BlogCommentRepository $blogCommentRepository): Response
    {
        return $this->redirectToRoute('blog_index');
        //dump($blogCommentRepository->findAll());
        return $this->render('blog_comment/index.html.twig', ['blog_comments' => $blogCommentRepository->findAll()]);
    }

    /**
     * @Route("/new/{blog_id}", name="blog_comment_new", methods="GET|POST")
     */
    public function new(Request $request, BlogRepository $blogRepository): Response
    {
        $blogComment = new BlogComment();

        //$blogComment->setUser($this->getUser());
        //$blogComment->setCreatedAt(new \DateTime());

        //$blogComment->addBlog(1);

         $blog = $blogRepository->findBy(['id' => $request->get('blog_id')], null, 1);
         //dump($blog);
         $blogComment->addBlog($blog[0]);



        //dump($blogComment);

        $form = $this->createForm(BlogCommentType::class, $blogComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blogComment);
            $em->flush();

            //return $this->redirectToRoute('blog_show', ['id' => $blog[0]->getId()]);
        }

        return $this->render('blog_comment/new.html.twig', [
            'blog_comment' => $blogComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_comment_show", methods="GET")
     */
    public function show(BlogComment $blogComment): Response
    {
        return $this->render('blog_comment/show.html.twig', ['blog_comment' => $blogComment]);
    }

    /**
     * @Route("/{id}/edit", name="blog_comment_edit", methods="GET|POST")
     * @Security("is_granted('EDIT', blogComment) or is_granted('ROLE_ADMIN')")
     */
    public function edit(Request $request, BlogComment $blogComment): Response
    {
        $form = $this->createForm(BlogCommentType::class, $blogComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //dump($blogComment->getBlog()[0]->getId());

            return $this->redirectToRoute('blog_show', ['id' => $blogComment->getBlog()[0]->getId()]);
            return $this->redirectToRoute('blog_comment_edit', ['id' => $blogComment->getId()]);
        }

        return $this->render('blog_comment/edit.html.twig', [
            'blog_comment' => $blogComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_comment_delete", methods="DELETE")
     * @Security("is_granted('EDIT', blogComment) or is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, BlogComment $blogComment): Response
    {
        $returnBlogId = $blogComment->getBlog()[0]->getId();

        if ($this->isCsrfTokenValid('delete'.$blogComment->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blogComment);
            $em->flush();
        }
        //dump ($blogComment);
        return $this->redirectToRoute('blog_show', ['id' => $returnBlogId]);

        return $this->redirectToRoute('blog_comment_index');
    }

    /**********************************************************************************************************************/
    /* for JjQuery
    /**********************************************************************************************************************/


    /**
     * @Route("/jquerynew/{blog_id}", name="jquery_blog_comment_new", methods="GET|POST")
     */
    public function newjQuery(Request $request, BlogRepository $blogRepository): Response
    {
        $blogComment = new BlogComment();
        $blogComment->setUser($this->getUser());
        $blog = $blogRepository->findBy(['id' => $request->get('blog_id')], null, 1);

        $blogComment->addBlog($blog[0]);
        $blogComment->setComment($request->get('comment'));

        //var_dump($blogComment);

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogComment);
        $em->flush();

        return $this->json(array('result' => 0));

    }

    /**
     * @Route("jquerydelete/{id}", name="jquery_blog_comment_delete", methods="DELETE")
     * @Security("is_granted('EDIT', blogComment) or is_granted('ROLE_ADMIN')")
     */
    public function deletejQuery(Request $request, BlogComment $blogComment): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blogComment);
        $em->flush();

        return $this->json(array('result' => 0));
    }

    /**
     * @Route("/{id}/jqueryedit", name="jquery_blog_comment_edit", methods="GET|POST")
     * @Security("is_granted('EDIT', blogComment) or is_granted('ROLE_ADMIN')")
     */
    public function editjQuery(Request $request, BlogComment $blogComment): Response
    {
        $blogComment->setComment($request->get('comment'));

        $this->getDoctrine()->getManager()->flush();

        return $this->json(array('result' => 0));

    }
}
