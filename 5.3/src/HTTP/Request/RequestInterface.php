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

//@codeCoverageIgnoreStart
/**
 * Интерфейс запроса HTTP
 *
 * Для работы с запросами HTTP Велигосту требуется получать информацию из этих запросов. Т. к.
 * в разных фреймворках работа с HTTP может быть организована по-разному, Велигост определяет
 * интерфейс, который должен иметь класс, предоставляющий доступ к данным запроса.
 *
 * Разработчикам необязательно создавать свой класс с таким интерфейсом, можно воспользоваться
 * готовыми реализациями, такими как {@link Native}.
 */
interface RequestInterface
{
    /**
     * Возвращает значение аргумента GET
     *
     * @param string $key  имя аргумента
     *
     * @return null|string  значение аргумента GET или null, если такой аргумент отсутствует
     */
    public function getArg($key);

    /**
     * Возвращает значение куки
     *
     * @param string $name  имя куки
     *
     * @return null|string  значение куки или null, если такой куки отсутствует
     */
    public function getCookie($name);

    /**
     * Возвращает тело запроса
     *
     * @return string
     */
    public function getBody();
}
//@codeCoverageIgnoreEnd