<?php
/**
 * Интерфейс запроса HTTP
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

namespace Veligost\HTTP\Request;

/**
 * Предоставляет доступ к данным запроса HTTP используя встроенные средства PHP: суперглобальные
 * переменные $_GET, $_COOKIE и $_FILES.
 */
class Native implements RequestInterface
{
    /**
     * Возвращает значение аргумента GET
     *
     * @param string $key  имя аргумента
     *
     * @return null|string  значение аргумента GET или null, если такой аргумент отсутствует
     */
    public function getArg($key)
    {
        if (array_key_exists($key, $_GET))
        {
            return $_GET[$key];
        }
        return null;
    }

    /**
     * Возвращает значение куки
     *
     * @param string $name  имя куки
     *
     * @return null|string  значение куки или null, если такой куки отсутствует
     */
    public function getCookie($name)
    {
        if (array_key_exists($name, $_COOKIE))
        {
            return $_COOKIE[$name];
        }
        return null;
    }

    /**
     * Возвращает тело запроса
     *
     * @return string
     */
    public function getBody()
    {
        $body = file_get_contents('php://input');
        $BOM = pack('CCC', 0xEF, 0xBB, 0xBF);
        if (0 == strncmp($body, $BOM, 3))
        {
            $body = substr($body, 3);
        }
        return $body;
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd