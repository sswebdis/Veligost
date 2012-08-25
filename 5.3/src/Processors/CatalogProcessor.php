<?php
/**
 * Процессор выгрузки каталогов продукции
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

use Veligost\Response,
    Veligost\CommerceML\Document,
    DOMDocument;

/**
 * Процессор выгрузки каталогов продукции
 */
class CatalogProcessor extends AbstractProcessor
{
    /**
     * Выгрузка на сайт файлов обмена
     */
    protected function actionFile()
    {
        $filename = $this->request->getArg('filename');
        if ($filename)
        {
            $this->session->storeFile($filename, $this->request->getBody());
            $this->response->add(Response::SUCCESS);
        }
        else
        {
            $this->response->add(Response::FAILURE);
            $this->response->add('Неправильный запрос: отсутствует параметр «filename»');
        }
    }

    /**
     * Пошаговая загрузка каталога
     */
    protected function actionImport()
    {
        $filename = $this->request->getArg('filename');
        if ($filename)
        {
            $xml = new DOMDocument;
            $xml->load($this->session->retrieveFile($filename));

            if ($this->handler)
            {
                $doc = new Document($xml->firstChild);
                $this->handler->import($doc);
            }
            $this->response->add(Response::SUCCESS);
        }
        else
        {
            $this->response->add(Response::FAILURE);
            $this->response->add('Неправильный запрос: отсутствует параметр «filename»');
        }
    }
}
