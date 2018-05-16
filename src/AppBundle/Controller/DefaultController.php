<?php

namespace AppBundle\Controller;

use AppBundle\Service\NewsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Get all Feeds to show them in the menu and index page
        $feed_list = $this->getDoctrine()->getRepository('AppBundle:Feed')->findBy(
            [],
            ['title' => 'ASC']
        );

        return $this->render('default/index.html.twig', [
            'feed_list' => $feed_list,
        ]);
    }

    /**
     * @Route("/news/{slug}/{category}", name="news")
     * @param Request $request
     * @param NewsService $news_service
     * @param string $slug
     * @param string $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAction(Request $request, NewsService $news_service, $slug, $category=null)
    {
        $category = urldecode($category);
        $feed = $news_service->getFeed($slug, $category);

        if (!$feed) {
            $this->addFlash('warning', 'This feed does not exists');
            return $this->redirectToRoute('homepage');
        }

        // Get all Feeds to show them in the menu and index page
        $feed_list = $this->getDoctrine()->getRepository('AppBundle:Feed')->findBy(
            [],
            ['title' => 'ASC']
        );

        return $this->render('default/news.html.twig', [
            'feed_list' => $feed_list,
            'feed' => $feed,
            'category' => $category,
        ]);
    }
}
