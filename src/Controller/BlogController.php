<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
	 * @Route("/", name="blog_index", methods="GET")
     */
    public function index(BlogRepository $blogRepository, CategoriesRepository $CategoriesRepository): Response
    {
        return $this->render(
							'blog/index.html.twig', 
							[
								'blogs' => $blogRepository->findAll(), 
								'categories' => $CategoriesRepository->findAll(),
								'selectedCategory' => null,
							]
		);
    }
	
	/**
     * @Route("/category/{id}", name="blog_index_category", methods="GET")
     */
	public function indexByCategory(Request $request, BlogRepository $blogRepository, CategoriesRepository $CategoriesRepository): Response
    {
        return $this->render(
							'blog/index.html.twig', 
							[
								'blogs' => $blogRepository->findByCategory($request->get('id')), 
								'categories' => $CategoriesRepository->findAll(),
								'selectedCategory' => $request->get('id'),
							]
		);
    }
	
	

    /**
     * @Route("/new", name="blog_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        dump($request->getUser());
        $blog = new Blog();
		//$blog->setUser($user->getId());
		
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_show", methods="GET")
     */
    public function show(Blog $blog): Response
    {
		//var_dump($blog);
        return $this->render('blog/show.html.twig', ['blog' => $blog]);
    }

    /**
     * @Route("/{id}/edit", name="blog_edit", methods="GET|POST")
     * @IsGranted("EDIT", subject="blog")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Blog $blog, AuthorizationCheckerInterface $authCheck): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_edit', ['id' => $blog->getId()]);
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_delete", methods="DELETE")
     * @IsGranted("EDIT", subject="blog")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Blog $blog, AuthorizationCheckerInterface $authCheck): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
        }

        return $this->redirectToRoute('blog_index');
    }
}
