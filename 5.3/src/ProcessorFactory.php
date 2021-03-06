<?php
/**
 * Процессор запросов 1С
 *
 * @copyright 2012 ООО «Два слона» http://dvaslona.ru/
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @author Михаил Красильников <mk@dvaslona.ru>
 *
 * Copyright 2012 DvaSlona Ltd.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Veligost;

use Veligost\HTTP\Request\RequestInterface,
    Veligost\HTTP\Request\Native as NativeRequest,
    Veligost\Processors\BadRequestProcessor,
    Veligost\Processors\CatalogProcessor,
    Veligost\Processors\SaleProcessor;

/**
 * Процессор запросов 1С
 */
class ProcessorFactory
{
    /**
     * Создаёт новый процессор запроса 1С
     *
     * @param null|RequestInterface $request
     *
     * @return Processors\CatalogProcessor
     */
    public static function create(RequestInterface $request = null)
    {
        if (null === $request)
        {
            $request = new NativeRequest;
        }

        switch ($request->getArg('type'))
        {
            case 'catalog':
                $processor = new CatalogProcessor($request);
                break;

            case 'sale':
                $processor = new SaleProcessor($request);
                break;

            default:
                $processor = new BadRequestProcessor($request);
                break;
        }

        return $processor;
    }
    // @codeCoverageIgnoreStart
}
// @codeCoverageIgnoreEnd