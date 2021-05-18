<?php

namespace App\Controller;


use App\Entity\Post;
use App\Form\FormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(PostRepository $postRepository): Response
    {

        $form = $this->createForm(FormType::class);
        $posts = $postRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'posts'           => $posts,

        ]);
    }


    /**
     * @Route("/admin/create", name="admin_create", methods={"GET"})
     */
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(FormType::class, $post, ['action' => $this->generateUrl('admin_save')]);

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/save", name="admin_save", methods={"POST"})
     */
    public function save(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(FormType::class, $post);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            // save
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/remove/{id}", name="admin_remove")
     */
    public function remove(Post $post): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/edit/{id}", name="admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {

        $form = $this->createForm(FormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin', [
                'id'=>$post->getId(), ]);
        }

        return $this->render('admin/edit.html.twig', [
            'post'=>$post,
            'form' => $form->createView(),
        ]);



    }
}
