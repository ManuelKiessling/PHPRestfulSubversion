<?php

/**
 * PHPRestfulSubversion
 *
 * Copyright (c) 2011, Manuel Kiessling <manuel@kiessling.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *   * Neither the name of Manuel Kiessling nor the names of its contributors
 *     may be used to endorse or promote products derived from this software
 *     without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   VersionControl
 * @package    RestfulSubversion
 * @subpackage Core
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPRestfulSubversion
 */

namespace RestfulSubversion\Core;

/**
 * Class representing a revision number in a SVN repository
 *
 * @category   VersionControl
 * @package    RestfulSubversion
 * @subpackage Core
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPRestfulSubversion
 * @uses       RevisionInvalidRevisionNumberCoreException
 */
class Revision
{
    protected $revisionNumber = null;

    /**
     * @throws RevisionInvalidRevisionNumberCoreException
     * @param $revisionNumber
     */
    public function __construct($revisionNumber)
    {
        if ($revisionNumber == 'HEAD') {
            $this->revisionNumber = $revisionNumber;
            return;
        }

        if ((int)$revisionNumber < 0) {
            throw new RevisionInvalidRevisionNumberCoreException('Revision number must be positive.');
        }
        if ((string)(int)$revisionNumber != $revisionNumber) {
            throw new RevisionInvalidRevisionNumberCoreException('"' . $revisionNumber . '" is not a valid revision number.');
        }
        $this->revisionNumber = $revisionNumber;
    }

    /**
     * @return string
     */
    public function getAsString()
    {
        return (string)$this->revisionNumber;
    }

    /**
     * @return string
     */
    public function sGetRevertedAstring()
    {
        return '-' . $this->getAsString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getAsString();
    }
}

/**
 * Exception for errors in Revision
 *
 * @category   VersionControl
 * @package    RestfulSubversion
 * @subpackage Core
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPRestfulSubversion
 */
class RevisionInvalidRevisionNumberCoreException extends \Exception {}
