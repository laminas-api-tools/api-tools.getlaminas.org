#!/usr/bin/env php
<?php

use Laminas\Console\Exception\RuntimeException;
use Laminas\Console\Getopt;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver;

chdir(__DIR__ . '/../');
require_once 'vendor/autoload.php';

$opts = new Getopt([
    'help|h'       => 'This usage message',
    'key|k=s'      => 'Set the Youtube API key',
]);

try {
    $opts->parse();
} catch (RuntimeException $e) {
    echo $e->getUsageMessage();
    exit(1);
}

if (isset($opts->h)) {
    echo $opts->getUsageMessage();
    exit(0);
}

if (isset($opts->k)) {
    $client = new Google_Client();
    $client->setDeveloperKey($opts->k);
    $youtubeService = new Google_Service_YouTube($client);

    $playlistItemsResponse = $youtubeService->playlistItems->listPlaylistItems('snippet,status', [
        'part' => 'snippet,contentDetails',
        'maxResults' => 50,
        'playlistId' => 'PL8XToL5Ut_4yXlovH3oCmLNNxIT5RNH4w',
    ]);
    // var_dump($playlistItemsResponse['items']);

    $videoPath = __DIR__ . '/../module/Application/view/application/video';
    $videos = [];

    foreach ($playlistItemsResponse['items'] as $playlistItem) {
        $thumbnails = [
            'small' => $playlistItem['snippet']['thumbnails']->getMedium(),
            'large' => $playlistItem['snippet']['thumbnails']->getMaxres(),
        ];
        $video = [
            'id' => $playlistItem['snippet']['resourceId']['videoId'],
            'title' => $playlistItem['snippet']['title'],
            'description' => $playlistItem['snippet']['description'],
            'thumbnails' => $thumbnails,
        ];

        $videos[] = $video;
    }

    $renderer = new PhpRenderer();
    $resolver = new Resolver\TemplateMapResolver();
    $resolver->setMap([
        'video/template' => $videoPath . '/template.phtml',
        'video/item' => $videoPath . '/video.phtml',

    ]);
    $renderer->setResolver($resolver);

    $mainVideo = array_shift($videos);

    $model = new ViewModel([
      'mainVideo' => $mainVideo,
      'videos' => $videos,
    ]);
    $model->setTemplate('video/template');

    $html = $renderer->render($model);

    @file_put_contents($videoPath . '/index.phtml', '');
    @file_put_contents($videoPath . '/index.phtml', $html);
    exit(0);
}
