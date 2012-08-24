<?php
/**
 * Стереотип «Компонент»
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

namespace Veligost\CommerceML\Stereotypes;

use Veligost\CommerceML\Base\Element;

/**
 * Стереотип «Компонент»
 */
abstract class Component extends Element
{
    /**
     * Кэш объектов
     *
     * @var Element[]
     */
    private $nodeCache = array();

    /**
     * Возвращает объект класса $className для дочернего узла с именем $nodeName
     *
     * При повторном обращении к тому же узлу, будет возвращён объект, созданный для этого узла
     * ранее.
     *
     * @param string $nodeName
     * @param string $className
     *
     * @return null|Element
     */
    protected function getChild($nodeName, $className)
    {
        if (!array_key_exists($nodeName, $this->nodeCache))
        {
            $node = $this->getChildElement($nodeName);
            if (null !== $node)
            {
                $className = '\Veligost\CommerceML\\' . $className;
                $this->nodeCache[$nodeName] = new $className($node);
            }
            else
            {
                $this->nodeCache[$nodeName] = null;
            }
        }
        return $this->nodeCache[$nodeName];
    }
}
