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
     * Возвращает true если реализация поддерживает получение временного имени загруженного файла
     *
     * @return bool
     */
    public function providesUploadedTempName()
    {
        return true;
    }

    /**
     * Возвращает временное имя загруженного файла
     *
     * @param string  имя файла
     *
     * @return null|string  имя файла или null, если такой файл отсутствует
     */
    public function getUploadedTempName($name)
    {
        if (array_key_exists($name, $_FILES))
        {
            return $_FILES[$name]['tmp_name'];
        }

        return null;
    }

    /**
     * Возвращает true если реализация поддерживает получение содержимого загруженного файла
     *
     * @return bool
     */
    public function providesUploadedContents()
    {
        $openBaseDir = ini_get('open_basedir');
        if (!$openBaseDir)
        {
            return true;
        }

        $uploadTmpDir = ini_get('upload_tmp_dir');
        if (!$uploadTmpDir)
        {
            /*
             * Если параметр "upload_tmp_dir" не указан, используется какая-то системная папка.
             * Т. к. PHP не даёт однозначного способа узнать путь к этой папке, то надёжнее
             * сообщить, что чтение временных файлов не поддерживается.
             */
            return false;
        }

        // Является ли значение open_basedir префиксом пути или именем папки
        $isFullName =
            version_compare(phpversion(), '5.2.16', '>') &&
            version_compare(phpversion(), '5.3.0', '<') ||
            version_compare(phpversion(), '5.3.4', '>=');

        $uploadTmpDir .= DIRECTORY_SEPARATOR;

        $openBaseDir = explode(PATH_SEPARATOR, $openBaseDir);
        foreach ($openBaseDir as $path)
        {
            if ($isFullName && substr($path, -1) != DIRECTORY_SEPARATOR)
            {
                $path .= DIRECTORY_SEPARATOR;
            }

            if (strpos($uploadTmpDir, $path) === 0)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Возвращает содержимое загруженного файла
     *
     * @param string  имя файла
     *
     * @return null|string  содержимое файла или null, если такой файл отсутствует
     */
    public function getUploadedContents($name)
    {
        if (array_key_exists($name, $_FILES))
        {
            return file_get_contents($_FILES[$name]['tmp_name']);
        }

        return null;
    }
}
