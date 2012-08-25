<?php
/**
 * Абстрактный процессор запросов 1С
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

namespace Veligost\Processors;

use InvalidArgumentException,
    Veligost\HTTP\Request\RequestInterface,
    Veligost\Session,
    Veligost\SessionStorage\SessionStorageInterface,
    Veligost\SessionStorage\Native as NativeSessionStorage,
    Veligost\FileStorage\FileStorageInterface,
    Veligost\FileStorage\Directory as DirectoryFileStorage,
    Veligost\Response,
    Veligost\Interfaces\CatalogHandler;

/**
 * Абстрактный процессор запросов 1С
 */
abstract class AbstractProcessor
{
    /**
     * Имя куки
     * @var string
     */
    protected $cookieName = 'veligost';

    /**
     * Запрос HTTP
     * @var RequestInterface
     */
    protected $request;

    /**
     * Сессия
     *
     * @var Session
     */
    protected $session;

    /**
     * Хранилище сессий
     * @var SessionStorageInterface
     */
    protected $sessionStorage;

    /**
     * Хранилище файлов
     * @var FileStorageInterface
     */
    protected $fileStorage;

    /**
     * Ответ 1С
     * @var Response
     */
    protected $response;

    /**
     * Обработчик запроса
     *
     * @var CatalogHandler
     */
    protected $handler;

    /**
     * Конструктор процессора
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
       $this->request = $request;
       $this->response = new Response();
    }

    /**
     * Задаёт хранилище сессий
     *
     * @param \Veligost\SessionStorage\SessionStorageInterface $storage
     */
    public function setSessionStorage(SessionStorageInterface $storage)
    {
        $this->sessionStorage = $storage;
    }

    /**
     * Задаёт хранилище файлов
     *
     * @param \Veligost\FileStorage\FileStorageInterface $storage
     */
    public function setFileStorage(FileStorageInterface $storage)
    {
        $this->fileStorage = $storage;
    }

    /**
     * Устанавливает обработчик запроса
     *
     * @param CatalogHandler $handler
     *
     * @throws InvalidArgumentException
     */
    public function setHandler($handler)
    {
        if (!is_object($handler))
        {
            throw new InvalidArgumentException(
                '$handler must be an object not a ' . gettype($handler));
        }

        $validInterface = get_class($this);
        $validInterface = str_replace('Processors', 'Interfaces', $validInterface);
        $validInterface = str_replace('Processor', 'Handler', $validInterface);

        if (!($handler instanceof $validInterface))
        {
            throw new InvalidArgumentException(
                '$handler must implement ' . $validInterface);
        }

        $this->handler = $handler;
    }

    /**
     * Обрабатывает текущий запрос
     */
    public function process()
    {
        $this->initSession();
        if ($this->request->getArg('mode') == 'checkauth')
        {
            $this->actionCheckAuth();
        }
        elseif ($this->checkSession())
        {
            $method = 'action' . $this->request->getArg('mode');
            if (method_exists($this, $method))
            {
                call_user_func(array($this, $method));
            }
            else
            {
                $this->response->add(Response::FAILURE);
                $this->response->add('Invalid request');
            }
        }

        $this->response->send();
    }

    /**
     * Подготавливает сессию к работе
     *
     * Если хранилище сессий не было задано вызовом {@link setSessionStorage()}, то будет создан
     * экземпляр класса {@link \Veligost\SessionStorage\Native}.
     *
     * Если хранилище файлов не было задано вызовом {@link setFileStorage()}, то будет создан
     * экземпляр класса {@link \Veligost\FileStorage\Directory} с хранением файлов в папке,
     * возвращаемой {@link sys_get_temp_dir() sys_get_temp_dir()}.
     */
    protected function initSession()
    {
        if (!$this->sessionStorage)
        {
            $this->sessionStorage = new NativeSessionStorage($this->cookieName);
        }

        if (!$this->fileStorage)
        {
            $this->fileStorage = new DirectoryFileStorage(sys_get_temp_dir());
        }

        $sid = $this->request->getCookie($this->cookieName);
        $this->session = new Session($sid, $this->sessionStorage, $this->fileStorage);
    }

    /**
     * Проверяет сессию
     *
     * @return bool  true если сессия активна и false в противном случае
     */
    protected function checkSession()
    {
        if ($this->session->exists())
        {
            return true;
        }

        $this->response->add(Response::FAILURE);
        $this->response->add('Invalid session cookie');
        return false;
    }

    /**
     * Обрабатывает запрос на начало сессии
     */
    protected function actionCheckAuth()
    {
        $this->session->create();

        $this->response->add(Response::SUCCESS);
        $this->response->add($this->cookieName);
        $this->response->add($this->session->getId());
    }

    /**
     * Обрабатывает запрос на параметры
     */
    protected function actionInit()
    {
        $this->response->add('zip=no');
        preg_match('/([\d\.]+)\s*([KMG])?/', ini_get('upload_max_filesize'), $matches);
        $fileLimit = $matches[1];
        if (isset($matches[2]))
        {
            switch ($matches[2])
            {
                case 'K':
                    $fileLimit *= 1024;
                    break;
                case 'M':
                    $fileLimit *= 1024 * 1024;
                    break;
                case 'G':
                    $fileLimit *= 1024 * 1024 * 1024;
                    break;
            }
        }
        $this->response->add('file_limit=' . $fileLimit);
    }
    //@codeCoverageIgnoreStart
}
//@codeCoverageIgnoreEnd