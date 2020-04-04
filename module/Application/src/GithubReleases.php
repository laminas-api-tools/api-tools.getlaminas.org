<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Application;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class GithubReleases implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $releases = [];

    /**
     * @param array $releases
     */
    public function __construct(array $releases = [])
    {
        $this->releases = $this->parseReleases($releases);
    }

    /**
     * Implements Countable
     *
     * @return int
     */
    public function count()
    {
        return count($this->releases);
    }

    public function current(): ?array
    {
        error_log(sprintf('In %s', __METHOD__));
        if (0 === count($this)) {
            error_log('- No items found; returning null');
            return null;
        }

        $iterator = $this->getIterator();
        $iterator->rewind();
        return $iterator->current();
    }

    /**
     * Implements IteratorAggregate
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->releases);
    }

    /**
     * Parses releases for information of interest, and sorts by version in desc order
     *
     * @param array $releases
     * @return array
     */
    protected function parseReleases(array $releases)
    {
        usort($releases, function ($a, $b) {
            $compare = version_compare($a['name'], $b['name']);
            if ($compare === -1) {
                return 1;
            }
            if ($compare === 1) {
                return -1;
            }
            return 0;
        });

        return $releases;
    }
}
