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

namespace EldnpTest\Export\Writer\Csv;

use Eldnp\Export\Writer\Csv\CsvWriter;
use Eldnp\Export\Writer\Csv\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

class CsvWriterTest extends TestCase
{
    private function createTemporaryFile()
    {
        if (false === $temporaryFileName = tempnam(sys_get_temp_dir(), 'csv')) {
            throw new \RuntimeException('unable create temporary file');
        }

        return tempnam(sys_get_temp_dir(), 'csv');
    }

    private function getFixtureFileName($delimiter, $enclosure, $hasHeader)
    {
        $nameParts = array(
            $delimiter == ';' ? 'semicolon' : 'comma',
            $enclosure == '"' ? 'doubleQuotes' : 'singleQuotes',
            $hasHeader ? 'withHeader' : 'withoutHeader',
        );

        return implode('.', $nameParts) . '.csv';
    }

    public function writeDataProvider()
    {
        return array(
            array(';', '"', true),
            array(';', "'", true),
            array(',', '"', true),
            array(',', "'", true),

            array(';', '"', false),
            array(';', "'", false),
            array(',', '"', false),
            array(',', "'", false),
        );
    }

    /**
     * @dataProvider writeDataProvider
     *
     * @param string $delimiter
     * @param string $enclosure
     * @param string $hasHeader
     */
    public function testWrite($delimiter, $enclosure, $hasHeader)
    {
        $temporaryFile = $this->createTemporaryFile();
        $writer = new CsvWriter("file://{$temporaryFile}", $delimiter, $enclosure, $hasHeader);
        $writer->write(array(
            'field1' => 'field value with spaces',
            'field name with spaces' => 'value with single \'quotes\'',
            'value with double "quotes"',
        ));
        $writer->flush();
        $writer->close();
        $content = file_get_contents($temporaryFile);
        unlink($temporaryFile);
        $this->assertStringEqualsFile(
            realpath(__DIR__ . '/_fixture/' . $this->getFixtureFileName($delimiter, $enclosure, $hasHeader)),
            $content
        );
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnableToOpenException()
    {
        $writer = new CsvWriter('undefined-scheme://undefined-resource');
        $writer->write(array('field' => 'value'));
    }
}
