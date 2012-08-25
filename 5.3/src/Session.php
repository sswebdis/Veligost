<?php
/**
 * Сессия
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

use Veligost\SessionStorage\SessionStorageInterface,
    Veligost\FileStorage\FileStorageInterface;

/**
 * Сессия
 *
 * Хранит информацию о сеансе обмена данными с 1С
 */
class Session
{
    /**
     * Идентификатор сессии
     * @var string
     */
    private $id;

    /**
     * @var SessionStorage\SessionStorageInterface
     */
    private $sessions;

    /**
     * @var FileStorage\FileStorageInterface
     */
    private $files;

    /**
     * @param string|null                            $id        идентификатор сессии
     * @param SessionStorage\SessionStorageInterface $sessions  хранилище сессий
     * @param FileStorage\FileStorageInterface       $files     хранилище файлов
     */
    public function __construct($id, SessionStorageInterface $sessions, FileStorageInterface $files)
    {
        assert('is_null($id) || is_scalar($id)');

        $this->id = $id;
        $this->sessions = $sessions;
        $this->files = $files;
    }

    /**
     * Возвращает идентификатор сессии
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Возвращает true если сессия существует
     */
    public function exists()
    {
        return $this->id && $this->sessions->sessionExists($this->id);
    }

    /**
     * Создаёт сессию
     */
    public function create()
    {
        $this->id = $this->sessions->createSession();
    }

    /**
     * Уничтожает сессию
     */
    public function destroy()
    {
        $this->sessions->closeSession($this->id);
        $this->files->cleanup($this->id);
    }

    /**
     * Сохраняет файл в сессии
     *
     * @param string $filename  имя файла
     * @param string $contents  содержимое файла
     */
    public function storeFile($filename, $contents)
    {
        $this->files->store($this->id, $filename, $contents);
    }

    /**
     * Извлекает (удаляет) файл из сессии и возвращает его содержимое
     *
     * @param string $filename  имя файла
     *
     * @return string|bool содержимое сохранённого ранее файла или false, если такого файла нет
     */
    public function retrieveFile($filename)
    {
        if (($contents = $this->files->read($this->id, $filename)) !== false)
        {
            $this->files->unlink($this->id, $filename);
        }
        return $contents;
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd