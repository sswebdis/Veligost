<?php
/**
 * Интерфейс хранилища файлов
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

namespace Veligost\FileStorage;

/**
 * Интерфейс хранилища файлов
 *
 * Разработчикам необязательно создавать свой класс с таким интерфейсом, можно воспользоваться
 * готовыми реализациями, такими как {@link Directory}.
 */
interface FileStorageInterface
{
    /**
     * Помещает файл в хранилище
     *
     * @param string $sid       идентификатор сессии
     * @param string $filename  имя файла
     * @param string $contents  содержимое файла
     *
     * @return void
     */
    public function store($sid, $filename, $contents);

    /**
     * Возвращает содержимое файла
     *
     * @param string $sid       идентификатор сессии
     * @param string $filename  имя файла
     *
     * @return string|bool  содержимое файла или false, если файл не существует
     */
    public function read($sid, $filename);

    /**
     * Удаляет файл
     *
     * @param string $sid       идентификатор сессии
     * @param string $filename  имя файла
     */
    public function unlink($sid, $filename);

    /**
     * Удаляет все файлы сессии
     *
     * @param string $sid  идентификатор сессии
     *
     * @return void
     */
    public function cleanup($sid);
}
