#!/usr/bin/env php
<?php
chdir(__DIR__ . '/../');

// Functions

function getTags($curl, string $credentials)
{
    curl_setopt($curl, CURLOPT_URL, sprintf(
        'https://%sapi.github.com/repos/laminas-api-tools/api-tools-skeleton/git/refs/tags',
        $credentials ? $credentials . '@' : ''
    ));
    $result  = curl_exec($curl);
    $tagData = json_decode($result);

    if (! is_array($tagData)) {
        printf("[ERROR] Did not receive expected result when fetching tags\nReceived:\n%s\n", var_export($tagData, true));
        exit(1);
    }

    $tags    = array_map(function ($tagInfo) use ($curl, $credentials) {
        $sha = $tagInfo->object->sha;
        return getTag($curl, $credentials, $sha);
    }, $tagData);
    return array_filter($tags);
}

function getTag($curl, string $credentials, string $sha)
{
    curl_setopt($curl, CURLOPT_URL, sprintf(
        'https://%sapi.github.com/repos/laminas-api-tools/api-tools-skeleton/git/tags/%s',
        $credentials ? $credentials . '@' : '',
        $sha
    ));
    $result = curl_exec($curl);
    $tagData = json_decode($result);

    if (
        ! isset($tagData->tag)
        || ! isset($tagData->message)
    ) {
        return null;
    }

    return [
        'name'      => $tagData->tag,
        'changelog' => filterTagMessage($tagData),
    ];
}

function filterTagMessage(stdClass $tagData)
{
    $message = $tagData->message;
    if (! isset($tagData->verification->signature)) {
        return $message;
    }
    return str_replace($tagData->verification->signature, '', $message);
}

// Get arguments, if any
$credentials = '';
switch ($argc) {
    case 1:
        $credentials = '';
        break;
    case 2:
        $credentials = $argv[1];
        break;
    case 3:
        $credentials = sprintf('%s:%s', $argv[1], $argv[2]);
        break;
    default:
        echo "Too many arguments present!\n\n";
        echo "Usage:\n";
        printf("  %s [token or username] [token associated with username]\n");
        exit(1);
}

// Curl initialization
$curl = curl_init();
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Accept: application/vnd.github.v3+json',
    'User-Agent: api-tools-getlaminas-org',
]);                                             // Send appropriate headers
curl_setopt($curl, CURLOPT_HEADER, 0);          // do not return headers in output
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  // return data from call

// Get tag data
$tags = getTags($curl, $credentials);

// Close curl handle
curl_close($curl);

file_put_contents('data/releases.json', json_encode($tags, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES));
file_put_contents('php://stdout', "[DONE] Fetched releases from GitHub!\n");
