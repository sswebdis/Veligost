<?php
/**
 * Хранилище сессий на основе встроенного механизма сессий PHP
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

namespace Veligost\SessionStorage;

use RuntimeException;

/**
 * Хранилище сессий на основе встроенного механизма сессий PHP
 */
class Native implements SessionStorageInterface
{
    /**
     * Конструктор
     *
     * Запускает механизм сессий
     */
    public function __construct()
    {
        $this->startSessions();
    }

    /**
     * Создаёт новую сессию и возвращает её ключ
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function createSession()
    {
        $this->closeSession('');
        $this->startSessions();
        return session_id();
    }

    /**
     * Возвращает true, если сессия с указанным ключём существует
     *
     * @param string $id
     *
     * @return bool
     */
    public function sessionExists($id)
    {
        return session_id() == $id;
    }

    /**
     * Закрывает сессию
     *
     * @param string $id
     */
    public function closeSession($id)
    {
        session_destroy();
    }

    /**
     * Запускает механизм сессий
     */
    private function startSessions()
    {
        if (!session_start())
        {
            // Начиная с PHP 5.3 session_start возвращает false если сессии не инициализированы
            throw new RuntimeException(__CLASS__ .': session failed to start');
        }
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd