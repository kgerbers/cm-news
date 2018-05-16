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

class NewsService
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



    public function getFeed($slug, $category=null)
    {
        // Find the feed by slug
        $feed = $this->em->getRepository('AppBundle:Feed')->findOneBy(
            [
                'slug' => strtolower($slug)
            ]
        );

        if ($feed) {
            // Checking if the feed's LastUpdated datetime is older than 1 minute, if true then call refreshFeed()
            $now = new \DateTime();
            $check_time = $feed->getLastUpdated();
            $check_time->modify('+1 minutes');

            if ($now > $check_time) {
                $this->refreshFeed($feed);
            }

            if ($category) {
                // Remove all items from Feed object to replace them temporarily with the searched items only
                $feed->removeAllItems();
                //Because the categories are inserted as json, we can search them as text; this is more a free search function in categories:
                $items = $this->em->getRepository('AppBundle:Item')->createQueryBuilder('t')
                    ->where('t.feed = :feed')
                    ->setParameter('feed', $feed)
                    ->andWhere('t.categories LIKE :category')
                    ->setParameter('category', '%'.$category.'%')
                    ->getQuery()->getResult();

                //Set the new items in our Feed object for this request, but do not save them (eq not calling doctrine's flush method)
                $feed->setItems($items);
            }

            return $feed;
        }

        return false;
    }

    public function addFeed($url)
    {
        $rss = @simplexml_load_file($url);
        if (false === $rss) {
            $this->logger->warning("Feed '".$url."' could not be loaded", [libxml_get_last_error()]);
            return false;
        }

        $rss = $rss->channel;
        $rss->addChild('feed_url', $url);
        $this->saveFeed($rss);
        return true;
    }


    private function refreshFeed(Feed $feed)
    {
        // Get the external rss feed; suppres warnings because we want to return DB items if an error has occured:
        $rss = @simplexml_load_file($feed->getFeedUrl());

        if (false === $rss) {
            $error_msg = 'Feed \''.$feed->getTitle().'\' could not be loaded';
            $this->session->getFlashBag()->add('warning', $error_msg);

            $this->logger->warning($error_msg, [libxml_get_last_error()]);

            return false;
        }

        // Save our new feed:
        $this->saveFeed($feed, $rss->channel);
        return true;
    }


    /*
     * Remove all items and insert new ones to the database
     * This could be expanded to delete only old items and add only new items
     * This method also type cast the input to the right types
     */
    private function saveFeed(Feed $feed, $rss)
    {
        // remove all old news items before setting new ones:
        $feed->removeAllItems();
        $this->em->persist($feed);

        if (!$feed) {
            $feed = new Feed();
        }

        // prepare a slug for every feed so we can reference this 'nice' in the app's url
        $slug = strtolower((string) $rss->title[0]);
        $slug = preg_replace('/[^a-zA-Z0-9]/', '', $slug);

        $feed
            ->setTitle((string) $rss->title[0])
            ->setSlug($slug)
            ->setLink((string) $rss->link[0])
            ->setDescription(strip_tags((string) $rss->description[0]))
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
            $item = (new Item())
                ->setTitle((string) $item_data->title[0])
                ->setLink((string) $item_data->link[0])
                ->setDescription((string) $item_data->description[0])
                ->setPubDate((string) $item_data->pubDate[0])
                ->setGuid((string) $item_data->guid[0])
                ->setFeed($feed)
            ;

            // some feeds give extra metadata such as images, converting this to an array
            if ($item_data->enclosure[0]) {
                $attr = [];
                foreach ($item_data->enclosure[0]->attributes() as $key => $value) {
                    $attr[$key] = (string) $value[0];
                }
                $item->setEnclosures($attr);
            }

            if ($item_data->category) {
                $item->setCategories((array) $item_data->category);
            }

            $feed->addItem($item);
        }

        $this->em->flush();

        return true;
    }
}
