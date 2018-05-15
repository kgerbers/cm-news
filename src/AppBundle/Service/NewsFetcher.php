<?php
/**
 * File: NewsFetcher.php.php
 */

namespace AppBundle\Service;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Item;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NewsFetcher
{
    private $em;
    private $session;
    private $logger;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, LoggerInterface $logger)
    {
        // Make sure libxml_disable_entity_loader is set to false (default) because of bug in PHP (see https://bugs.php.net/bug.php?id=62577)
        libxml_disable_entity_loader(false);

        $this->em = $em;
        $this->session = $session;
        $this->logger = $logger;
    }

    public function addFeed($url)
    {
        $rss = @simplexml_load_file($url);
        if (false === $rss) {
            $error_msg = 'Feed \''.$feed->getTitle().'\' could not be loaded';
            return false;
        } else {
            $rss = $rss->channel;
            $rss->addChild('feed_url', $url);
            $this->saveFeed($rss);
            return true;
        }
    }


    public function refreshFeed(Feed $feed)
    {
        $rss = @simplexml_load_file($feed->getFeedUrl());
        if (false === $rss) {
            $item = $this->em->getRepository('AppBundle:Item')->findBy([
                'feed' => $feed
            ]);

            $error_msg = 'Feed \''.$feed->getTitle().'\' could not be loaded';
            $this->session->getFlashBag()->add('warning', $error_msg);

            $this->logger->warning($error_msg, [libxml_get_last_error()]);


            return [$feed, $item];
        } else {
            $this->saveFeed($rss->channel);
            return false;
        }
    }


    public function getFeed($slug):array
    {
        $feed = $this->em->getRepository('AppBundle:Feed')->findOneBy(
            [
                'slug' => strtolower($slug)
            ]
        );

        if ($feed) {
            $now = new \DateTime();
            $check_time = $feed->getLastUpdated();
            $check_time->modify('+1 minutes');

            if ($now > $check_time) {
                $this->session->getFlashBag()->add('success', 'updated');
                $this->refreshFeed($feed);
            } else {
                $this->session->getFlashBag()->add('info', 'not updated');
            }
        }

        $items = $this->em->getRepository('AppBundle:Item')->findBy([
            'feed' => $feed,
        ]);

        return [$feed, $items];
    }


    public function saveFeed($rss)
    {
        $items = [];


        $feed = $this->em->getRepository('AppBundle:Feed')->findOneBy([
            'title' => (string) $rss->title[0],

        ]);

        // remove all old news items before setting new ones:
        $items = $this->em->getRepository('AppBundle:Item')->findBy(['feed'=>$feed]);
        foreach ($items as $item) {
            $this->em->remove($item);
        }

        $this->em->flush();


        if (!$feed) {
            $feed = new Feed();
        }

        $slug = strtolower((string) $rss->title[0]);
        $slug = preg_replace('/[^a-zA-Z0-9]/', '', $slug);

        $feed
            ->setTitle((string) $rss->title[0])
            ->setSlug($slug)
            ->setLink((string) $rss->link[0])
            ->setDescription((string) $rss->description[0])
            ->setLanguage((string) $rss->language[0])
            ->setCopyright((string) $rss->copyright[0])
            ->setTtl((int) $rss->ttl[0])
            ->setLastUpdated()
        ;

        if ($rss->feed_url[0]) {
            $feed->setFeedUrl((string) $rss->feed_url[0]);
        }

        $this->em->persist($feed);




        foreach ($rss->item as $item_data) {
            $item = $this->em->getRepository('AppBundle:Item')->findOneBy([
                'title' => (string) $item_data->title[0],
                'link' => (string) $item_data->link[0],
            ]);

            if (!$item) {
                $item = new Item();
            }
            $item
                ->setTitle((string) $item_data->title[0])
                ->setLink((string) $item_data->link[0])
                ->setDescription((string) $item_data->description[0])
                ->setPubDate((string) $item_data->pubDate[0])
                ->setGuid((string) $item_data->guid[0])
                ->setFeed($feed)
                ->setEnclosures((array) $item_data->enclosure[0])
            ;

            $this->em->persist($item);
            $items[] = $item;
        }


        $this->em->flush();

        return [$feed, $items];
    }
}
