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

use Veligost\HTTP\Request\RequestInterface,
    Veligost\SessionStorage\SessionStorageInterface,
    Veligost\SessionStorage\Native as NativeSessionStorage,
    Veligost\Response;

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
     * Хранилище сессий
     * @var SessionStorageInterface
     */
    protected $sessionStorage;

    /**
     * Ответ 1С
     * @var Response
     */
    protected $response;

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
     * Обрабатывает текущий запрос
     */
    public function process()
    {
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
     * Возвращает хранилище сессий
     *
     * Если объект не был задан вызовом {@link setSessionStorage()}, то будет создан экземпляр
     * класса {@link NativeSessionStorage}.
     *
     * @return SessionStorageInterface
     */
    protected function getSessionStorage()
    {
        if (!$this->sessionStorage)
        {
            $this->sessionStorage = new NativeSessionStorage($this->cookieName);
        }
        return $this->sessionStorage;
    }

    /**
     * Проверяет сессию
     *
     * @return bool  true если сессия активна и false в противном случае
     */
    protected function checkSession()
    {
        $sessionStorage = $this->getSessionStorage();
        $sid = $this->request->getCookie($this->cookieName);
        if ($sid && $sessionStorage->sessionExists($sid))
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
        $sessionStorage = $this->getSessionStorage();
        $this->response->add(Response::SUCCESS);
        $this->response->add($this->cookieName);
        $this->response->add($sessionStorage->createSession());
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
}
