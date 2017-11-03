<?php
/*
 * This file is part of Eldnp/export.writer.csv.
 *
 * Eldnp/export.writer.csv is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Eldnp/export.writer.csv is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Eldnp/export.writer.csv. If not, see <http://www.gnu.org/licenses/>.
 *
 * @see       https://github.com/eldnp/export.writer.csv for the canonical source repository
 * @copyright Copyright (c) 2017 Oleg Verevskoy <verevskoy@gmail.com>
 * @license   https://github.com/eldnp/export.writer.csv/blob/master/LICENSE GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Eldnp\Export\Writer\Csv;

use Eldnp\Export\Map\AbstractMapWriter;
use Eldnp\Export\Writer\Csv\Exception\RuntimeException;

class CsvWriter extends AbstractMapWriter
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $delimiter = ';';

    /**
     * @var string
     */
    private $enclosure = '"';

    /**
     * @var bool
     */
    private $hasHeader = true;

    /**
     * @var bool
     */
    private $headerIsWritten = false;

    /**
     * @var resource
     */
    private $stream;

    /**
     * CsvWriter constructor.
     * @param string $uri
     * @param string $delimiter
     * @param string $enclosure
     * @param bool $hasHeader
     */
    public function __construct($uri, $delimiter = ';', $enclosure = '"', $hasHeader = true)
    {
        $this->uri = $uri;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->hasHeader = $hasHeader;
    }

    /**
     * @return resource
     */
    private function getStream()
    {
        if (!$this->stream) {
            if (false === $this->stream = @fopen($this->uri, 'w+')) {
                throw new RuntimeException("unable to open stream '{$this->uri}'");
            }
        }
        return $this->stream;
    }

    /**
     * @inheritdoc
     */
    protected function writeMap(array $data)
    {
        if ($this->hasHeader && !$this->headerIsWritten) {
            $this->writeHeader(array_keys($data));
        }
        $this->writeData($data);
    }

    /**
     * @param array $data
     */
    private function writeData($data)
    {
        if (false === @fputcsv($this->getStream(), $data, $this->delimiter, $this->enclosure)) {
            throw new RuntimeException(
                'Unable to write to csv file. Writing data: ' . var_export($data, true)
            );
        }
    }

    /**
     * @param array $fields
     */
    private function writeHeader($fields)
    {
        $this->writeData($fields);
        $this->headerIsWritten = true;
    }

    /**
     * @inheritdoc
     */
    public function flush()
    {
        //NOP
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        $stream = $this->getStream();
        if (is_resource($stream)) {
            fclose($stream);
            $this->stream = null;
        }
    }
}
