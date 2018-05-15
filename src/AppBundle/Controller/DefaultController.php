<?php

namespace AppBundle\Controller;

use AppBundle\Service\NewsFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $feed_list = $this->getDoctrine()->getRepository('AppBundle:Feed')->findAll();

        return $this->render('default/index.html.twig', [
            'feed_list' => $feed_list,
        ]);
    }

    /**
     * @Route("/news/{slug}", name="news")
     */
    public function newsAction(Request $request, NewsFetcher $newsFetcher, $slug='nu')
    {
        [$feed, $items] = $newsFetcher->getFeed($slug);

        $feed_list = $this->getDoctrine()->getRepository('AppBundle:Feed')->findAll();

        return $this->render('default/news.html.twig', [
            'feed_list' => $feed_list,
            'feed' => $feed,
            'items' => $items,
        ]);
    }

    /**
     * @Route("/settings", name="settings")
     */
    public function settingsAction(Request $request)
    {
        $feed_list = $this->getDoctrine()->getRepository('AppBundle:Feed')->findAll();

        return $this->render('default/index.html.twig', [
            'feed_list' => $feed_list,
        ]);
    }
}
