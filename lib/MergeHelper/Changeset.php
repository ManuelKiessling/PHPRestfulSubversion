<?php

/**
 * PHPMergeHelper
 *
 * Copyright (c) 2010, Manuel Kiessling <manuel@kiessling.net>
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
 * @package    PHPMergeHelper
 * @subpackage Repository
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2010 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPMergeHelper
 */

/**
 * Class representing a revision or range of revisions
 *
 * @category   VersionControl
 * @package    PHPMergeHelper
 * @subpackage Repository
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2010 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPMergeHelper
 */
class MergeHelper_Changeset {

	protected $oRevision = NULL;
	protected $sAuthor = NULL;
	protected $sDateTime = NULL;
	protected $sMessage = NULL;
	protected $aaPathOperations = array();
	
	public function __construct(MergeHelper_Revision $oRevision) {
		$this->oRevision = $oRevision;
	}
	
	public function setAuthor($sAuthor) {
		$this->sAuthor = $sAuthor;
	}
	
	public function setDateTime($sDateTime) {
		$this->sDateTime = $sDateTime;
	}
	
	public function setMessage($sMessage) {
		$this->sMessage = $sMessage;
	}
	
	public function addPathOperation($sAction, MergeHelper_RepoPath $oPath, MergeHelper_RepoPath $oCopyfromPath = NULL, MergeHelper_Revision $oCopyfromRev = NULL) {
		$aPathOperation = array('sAction' => $sAction,
		                        'oPath' => $oPath
		                       );
		if ($oCopyfromPath) $aPathOperation['oCopyfromPath'] = $oCopyfromPath;
		if ($oCopyfromRev) $aPathOperation['oCopyfromRev'] = $oCopyfromRev;
		$this->aaPathOperations[] = $aPathOperation;
	}

	public function oGetRevision() {
		return $this->oRevision;
	}

	public function sGetAuthor() {
		return $this->sAuthor;
	}

	public function sGetDateTime() {
		return $this->sDateTime;
	}

	public function sGetMessage() {
		return $this->sMessage;
	}

	public function aaGetPathOperations() {
		return $this->aaPathOperations;
	}
	
}