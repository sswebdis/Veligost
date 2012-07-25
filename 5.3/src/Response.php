<?php
/**
 * Ответ 1C
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

use InvalidArgumentException;

/**
 * Ответ 1С
 */
class Response
{
    /**
     * Ответ: успешно
     */
    const SUCCESS = 'success';

    /**
     * Ответ: ошибка
     */
    const FAILURE = 'failure';

    /**
     * Тип ответа: успешно или ошибка
     *
     * @var string
     */
    protected $status;

    /**
     * Сообщения
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Конструктор
     *
     * @param string $status  сообщение об успехе или ошибке
     */
    public function __construct($status = self::SUCCESS)
    {
        $this->setStatus($status);
    }

    /**
     *
     * @param string $status
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($status)
    {
        $statuses = array(self::SUCCESS, self::FAILURE);
        if (!in_array($status, $statuses))
        {
            throw new InvalidArgumentException('Invalid status: ' . $status);
        }
        $this->status = $status;
    }

    /**
     * Добавляет сообщение в ответ
     *
     * @param string $message
     */
    public function add($message)
    {
        $this->messages []= $message;
    }

    /**
     * Отправляет ответ 1С
     */
    public function send()
    {
        array_unshift($this->messages, $this->status);
        $body = implode("\n", $this->messages);
        $this->messages = array();
        header('Content-type: text/plain;charset=UTF-8');
        echo $body;
    }
}
