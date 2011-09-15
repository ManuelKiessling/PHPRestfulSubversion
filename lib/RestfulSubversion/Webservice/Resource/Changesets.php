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
 * @subpackage Webservice
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPRestfulSubversion
 */

/**
 * Changesets resource
 * @uri /changesets
 * @category   VersionControl
 * @package    RestfulSubversion
 * @subpackage Webservice
 * @author     Manuel Kiessling <manuel@kiessling.net>
 * @copyright  2011 Manuel Kiessling <manuel@kiessling.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://manuelkiessling.github.com/PHPRestfulSubversion
 */
class RestfulSubversion_Webservice_Resource_Changesets extends RestfulSubversion_Webservice_Resource
{
    public function get($request)
    {
        $responseHelper = new RestfulSubversion_Webservice_Helper_Response();

        $callback = NULL;
        if (isset($_GET['callback'])) {
            $callback = $_GET['callback'];
        }

        if (isset($_GET['with_message_containing'])) {
            $searchMode = 'with_message_containing';
            $searchTerm = $_GET['with_message_containing'];
        } elseif (isset($_GET['with_path_ending_on'])) {
            $searchMode = 'with_path_ending_on';
            $searchTerm = $_GET['with_path_ending_on'];
        } else {
            return $responseHelper->setFailedResponse(new Response($request), "You can't request an unfiltered list of all changesets. Use changesets?with_message_containing=TEXT or changesets?with_path_ending_on=TEXT instead.", $callback);
        }


        $sortOrder = 'descending';
        if (isset($_GET['sort_order'])) {
            $sortOrder = $_GET['sort_order'];
        }
        if (!($sortOrder === 'ascending' || $sortOrder === 'descending')) {
            return $responseHelper->setFailedResponse(new Response($request), "Sort order must be 'ascending' or 'descending'.", $callback);
        }

        $limit = NULL;
        if (isset($_GET['limit'])) {
            if (!is_numeric($_GET['limit']) || (string)(int)$_GET['limit'] !== $_GET['limit']) {
                return $responseHelper->setFailedResponse(new Response($request), "Limit must be an integer value.", $callback);
            }
            $limit = (int)$_GET['limit'];
        }
        if ($limit === 0) $limit = NULL;

        $cacheDbHandler = new PDO($this->configValues['repoCacheConnectionString'], NULL, NULL);
        $repoCache = new RestfulSubversion_Core_RepoCache($cacheDbHandler);
        $changesets = array();

        if ($searchMode == 'with_message_containing') {
            $changesets = $repoCache->getChangesetsWithMessageContainingText($searchTerm, $sortOrder, $limit);
        } elseif ($searchMode == 'with_path_ending_on') {
            $changesets = $repoCache->getChangesetsWithPathEndingOn($searchTerm, $sortOrder, $limit);
        }

        foreach ($changesets as $changeset) {
            $changesets[] = RestfulSubversion_Webservice_Helper_Result::getChangesetAsArray($changeset);
        }
        ksort($changesets);

        return $responseHelper->setResponse(new Response($request), array('changesets' => $changesets), $callback);
    }
}