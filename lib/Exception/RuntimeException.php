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

namespace Eldnp\Export\Writer\Csv\Exception;

use Eldnp\Export\Exception\ExceptionInterface;

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
