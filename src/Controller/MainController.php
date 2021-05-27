<?php

namespace App\Controller;

use App\Form\FilterPostType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/{page}", name="home")
     * @param Request $request
     * @param PostRepository $postRepository
     * @param PaginatorInterface $paginator
     * @param int $page
     * @return Response
     */
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator, int $page = 0): Response
    {
        $filterForm = $this->createForm(FilterPostType::class);
        $filterForm->handleRequest($request);
        $sortBy = 'date';
        $orderBy = 'ASC';

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $sortBy = $filterForm->getData()['sortby'];
            $orderBy = $filterForm->getData()['orderby'];
        }



        $posts = $postRepository->getAll($page, 5, $sortBy, $orderBy);

        $posts = $paginator->paginate($posts,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5));


//        $total = count($posts);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'posts' => $posts,
            'filterForm' => $filterForm->createView()
        ]);

    }
}
