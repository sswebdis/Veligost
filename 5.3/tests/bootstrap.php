<?php
/**
 * Модульные тесты
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

namespace
{
    define('_TEST_DIR', __DIR__ );
    define('_SRC_DIR', realpath(__DIR__ . '/../src'));

    require_once _SRC_DIR . '/autoload.php';
}

namespace Veligost
{
    function header($header)
    {
    }
}

namespace Veligost\HTTP\Request
{
    function ini_get($key)
    {
        return isset($GLOBALS['ini'][$key]) ? $GLOBALS['ini'][$key] : null;
    }

    function ini_set($key, $value)
    {
        if (!array_key_exists('ini', $GLOBALS))
        {
            $GLOBALS['ini'] = array();
        }
        $GLOBALS['ini'][$key] = $value;
    }

    function phpversion()
    {
        return isset($GLOBALS['phpversion']) ? $GLOBALS['phpversion'] : PHP_VERSION;
    }
}

namespace Veligost\SessionStorage
{
    function session_start()
    {
        if (isset($GLOBALS['session_start_fail']))
        {
            unset($GLOBALS['session_start_fail']);
            return false;
        }
        $GLOBALS['session_id'] = md5(uniqid());
        return true;
    }

    function session_id()
    {
        return isset($GLOBALS['session_id']) ? $GLOBALS['session_id'] : '';
    }

    function session_destroy()
    {
        if (isset($GLOBALS['session_id']))
        {
            unset($GLOBALS['session_id']);
            return true;
        }
        return false;
    }
}

namespace Veligost\HTTP\Request
{
    function file_get_contents($filename)
    {
        if (isset($GLOBALS['file_get_contents'][$filename]))
        {
            return $GLOBALS['file_get_contents'][$filename];
        }
        return false;
    }
}