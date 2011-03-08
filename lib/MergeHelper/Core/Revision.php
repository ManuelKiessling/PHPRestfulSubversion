<?php

/**
 * PHPMergeHelper
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
 * @package    MergeHelper
 * @subpackage Core
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPMergeHelper
 */

/**
 * Class representing a revision number in a SVN repository
 *
 * @category   VersionControl
 * @package    MergeHelper
 * @subpackage Core
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPMergeHelper
 */
class MergeHelper_Core_Revision {

	protected $sRevisionNumber = NULL;
	
	public function __construct($sRevisionNumber) {
		if ($sRevisionNumber == 'HEAD') {
			$this->sRevisionNumber = $sRevisionNumber;
			return;
		}

		if ((int)$sRevisionNumber < 0) {
			throw new MergeHelper_Core_RevisionInvalidRevisionNumberCoreException('Revision number must be positive.');
		}
		if ((string)(int)$sRevisionNumber != $sRevisionNumber) {
			throw new MergeHelper_Core_RevisionInvalidRevisionNumberCoreException('"'.$sRevisionNumber.'" is not a valid revision number.');
		}
		$this->sRevisionNumber = $sRevisionNumber;
	}
	
	public function sGetAsString() {
		return (string)($this->sRevisionNumber);
	}
	
	public function sGetRevertedAsString() {
		return '-'.$this->sGetAsString();
	}
	
	public function __toString() {
		return (string)$this->sGetAsString();
	}
	
}

/**
 * Exception for errors in MergeHelper_Core_Revision
 *
 * @category   VersionControl
 * @package    MergeHelper
 * @subpackage Core
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPMergeHelper
 * @uses       MergeHelper_Core_Exception
 */
class MergeHelper_Core_RevisionInvalidRevisionNumberCoreException extends MergeHelper_Core_Exception {};