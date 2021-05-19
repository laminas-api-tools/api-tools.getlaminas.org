<?php

declare(strict_types=1);

namespace Application\Command;

use Google_Client;
use Google_Service_YouTube;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplateMapResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class YoutubeCommand extends Command
{
    private const HELP = <<<'END'
        Fetch the Youtube video playlist associated with Laminas API Tools. On success,
        it will then create a template with the list for the "Videos" page of the
        website.

        END;

    protected static $defaultName = 'youtube';

    protected function configure(): void
    {
        $this->setDescription('Fetch the Youtube video playlist');
        $this->setHelp(self::HELP);
        $this->addArgument('key', InputArgument::REQUIRED, 'Set the Youtube API key');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Google_Client();
        $client->setDeveloperKey($input->getArgument('key'));
        $youtubeService = new Google_Service_YouTube($client);

        $playlistItemsResponse = $youtubeService->playlistItems->listPlaylistItems('snippet,status', [
            'part'       => 'snippet,contentDetails',
            'maxResults' => 50,
            'playlistId' => 'PL8XToL5Ut_4yXlovH3oCmLNNxIT5RNH4w',
        ]);
        // var_dump($playlistItemsResponse['items']);

        $videoPath = __DIR__ . '/../../view/application/video';
        $videos    = [];

        foreach ($playlistItemsResponse['items'] as $playlistItem) {
            $thumbnails = [
                'small' => $playlistItem['snippet']['thumbnails']->getMedium(),
                'large' => $playlistItem['snippet']['thumbnails']->getMaxres(),
            ];
            $video = [
                'id'          => $playlistItem['snippet']['resourceId']['videoId'],
                'title'       => $playlistItem['snippet']['title'],
                'description' => $playlistItem['snippet']['description'],
                'thumbnails'  => $thumbnails,
            ];

            $videos[] = $video;
        }

        $renderer = new PhpRenderer();
        $resolver = new TemplateMapResolver();
        $resolver->setMap([
            'video/template' => $videoPath . '/template.phtml',
            'video/item'     => $videoPath . '/video.phtml',

        ]);
        $renderer->setResolver($resolver);

        $mainVideo = array_shift($videos);

        $model = new ViewModel([
            'mainVideo' => $mainVideo,
            'videos'    => $videos,
        ]);
        $model->setTemplate('video/template');

        $html = $renderer->render($model);

        file_put_contents($videoPath . '/index.phtml', $html);
    }
}
