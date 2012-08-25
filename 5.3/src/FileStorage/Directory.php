<?php
/**
 * Хранилище файлов в папке
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

use FilesystemIterator,
    RegexIterator,
    InvalidArgumentException;

/**
 * Хранилище файлов в папке
 */
class Directory implements FileStorageInterface
{
    /**
     * Папка, хранящая файлы
     * @var string
     */
    private $directory;

    /**
     * Создаёт хранилище, размещающее файлы в указанной папке
     *
     * @param string $directory  полный путь к папке, где следует хранить файлы
     *
     * @throws InvalidArgumentException  если путь не существует или указана пустая строка
     */
    public function __construct($directory)
    {
        assert('is_string($directory)');

        $this->directory = realpath($directory);
        if (!$this->directory)
        {
            throw new InvalidArgumentException('Invalid or empty directory path');
        }
    }

    /**
     * Помещает файл в хранилище
     *
     * @param string $sid       идентификатор сессии
     * @param string $filename  имя файла
     * @param string $contents  содержимое файла
     *
     * @return void
     */
    public function store($sid, $filename, $contents)
    {
        file_put_contents($this->createFilename($sid, $filename), $contents);
    }

    /**
     * Возвращает содержимое файла
     *
     * @param string $sid       идентификатор сессии
     * @param string $filename  имя файла
     *
     * @return string|bool  содержимое файла или false, если файл отсутствует
     */
    public function read($sid, $filename)
    {
        $pathname = $this->createFilename($sid, $filename);
        if (file_exists($pathname))
        {
            return file_get_contents($pathname);
        }
        else
        {
            return false;
        }
    }

    /**
     * Удаляет файл
     *
     * @param string $sid       идентификатор сессии
     * @param string $filename  имя файла
     */
    public function unlink($sid, $filename)
    {
        $pathname = $this->createFilename($sid, $filename);
        if (file_exists($pathname))
        {
            unlink($pathname);
        }
    }

    /**
     * Удаляет все файлы сессии
     *
     * @param string $sid  идентификатор сессии
     *
     * @return void
     */
    public function cleanup($sid)
    {
        $it = new RegexIterator(
            new FilesystemIterator($this->directory, FilesystemIterator::KEY_AS_FILENAME),
            "/^$sid\./",
            RegexIterator::MATCH,
            RegexIterator::USE_KEY
        );
        foreach ($it as $file)
        {
            /** @var \SplFileInfo $file */
            unlink($file->getPathname());
        }
    }

    /**
     * Возвращает полное имя файла
     *
     * @param string $sid       идентификатор сессии
     * @param string $filename  имя файла
     *
     * @return string
     */
    private function createFilename($sid, $filename)
    {
        return "{$this->directory}/$sid.$filename";
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd