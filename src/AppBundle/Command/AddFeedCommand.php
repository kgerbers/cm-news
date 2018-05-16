<?php

namespace AppBundle\Command;

use GuzzleHttp\Client;
use On2it\FilerBundle\Entity\Auditlog;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AddFeedCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('news:feed:add')
            ->setDescription('Add a new feed to the database')
            ->setHelp('Initialises a new feed in the database')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'The URL of the news feed, eg. <comment>https://www.nu.nl/rss</comment>.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("Adding '".$input->getArgument('url')."': ");
        $news_service = $this->get('AppBundle\Service\NewsService');

        if ($news_service->addFeed($input->getArgument('url'))) {
            $output->write('OK');
        } else {
            $output->write('ERROR, Feed could not be added, see log for details');
        }
    }
}
