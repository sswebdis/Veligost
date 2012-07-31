<?php
/**
 * Документ CommerceML
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

namespace Veligost\CommerceML;

use Veligost\CommerceML\Exceptions\SchemaException,
    DOMDocument;

/**
 * Документ CommerceML
 */
class Document extends Component
{
    /**
     * @var string
     */
    protected $nodeName = 'КоммерческаяИнформация';

    /**
     * Возвращает версию схемы
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->element->getAttribute('ВерсияСхемы');
    }

    /**
     * Возвращает классификатор или null
     *
     * @throws Exceptions\SchemaException
     *
     * @return Classifier|null
     */
    public function getClassifier()
    {
        $value = $this->getChild('Классификатор', 'Classifier');
        if (null === $value)
        {
            throw new SchemaException('Отсутствует Классификатор');
        }
        return $value;
    }
}
