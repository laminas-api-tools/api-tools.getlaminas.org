<?php

declare(strict_types=1);

namespace Application\Command;

use stdClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetReleasesCommand extends Command
{
    private const HELP = <<<'END'
        Fetches a list of laminas-api-tools releases, based on api-tools-skeleton versions
        released. It then fetches release notes, and uses those to populate the list of
        versions with their changelogs, as well as provide a download link.

        END;

    private const RELEASES_TEMPLATE = <<< 'END'
        <?php
        return [
            'api-tools-releases' => %s,
        ];
        END;

    protected static $defaultName = 'get-releases';

    protected function configure(): void
    {
        $this->setDescription('Fetch list of laminas-api-tools releases');
        $this->setHelp(self::HELP);
        $this->addOption(
            'credentials',
            'c',
            InputOption::VALUE_REQUIRED,
            'Github credentials to use when fetching releases; either HTTP Basic, or a PAT',
            ''
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/vnd.github.v3+json',
            'User-Agent: api-tools-getlaminas-org',
        ]);                                             // Send appropriate headers
        curl_setopt($curl, CURLOPT_HEADER, 0);          // do not return headers in output
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  // return data from call

        // Get tag data
        $output->writeln('<info>Fetching releases and changelogs from GitHub...</info>');
        $tags = $this->getTags($curl, $input->getOption('credentials'));

        // Close curl handle
        curl_close($curl);

        $releases = sprintf(self::RELEASES_TEMPLATE, var_export($tags, true));

        file_put_contents('config/autoload/releases.global.php', $releases);

        $output->writeln('<info>[DONE] Fetched releases from GitHub!</info>');

        return 0;
    }

    private function getTags($curl, string $credentials)
    {
        curl_setopt($curl, CURLOPT_URL, sprintf(
            'https://%sapi.github.com/repos/laminas-api-tools/api-tools-skeleton/git/refs/tags',
            $credentials ? $credentials . '@' : ''
        ));
        $result  = curl_exec($curl);
        $tagData = json_decode($result);

        if (! is_array($tagData)) {
            printf(
                "[ERROR] Did not receive expected result when fetching tags\nReceived:\n%s\n",
                var_export($tagData, true)
            );
            exit(1);
        }

        $tags    = array_map(function ($tagInfo) use ($curl, $credentials) {
            $sha = $tagInfo->object->sha;
            return $this->getTag($curl, $credentials, $sha);
        }, $tagData);
        return array_filter($tags);
    }

    private function getTag($curl, string $credentials, string $sha)
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
            'changelog' => $this->filterTagMessage($tagData),
        ];
    }

    private function filterTagMessage(stdClass $tagData)
    {
        $message = $tagData->message;
        if (! isset($tagData->verification->signature)) {
            return $message;
        }
        return str_replace($tagData->verification->signature, '', $message);
    }
}
