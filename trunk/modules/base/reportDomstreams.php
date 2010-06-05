<?php

//
// Open Web Analytics - An Open Source Web Analytics Framework
//
// Copyright 2006 Peter Adams. All rights reserved.
//
// Licensed under GPL v2.0 http://www.gnu.org/copyleft/gpl.html
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// $Id$
//

require_once(OWA_BASE_DIR.'/owa_reportController.php');
require_once(OWA_BASE_DIR.'/owa_view.php');

/**
 * Domstream Report Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.2.1
 */

class owa_reportDomstreamsController extends owa_reportController {

	function owa_reportDomstreamsController($params) {
	
		return owa_reportDomstreamsController::__construct($params);
	}
	
	function __construct($params) {
		
		return parent::__construct($params);
	}
	
	function action() {
		
		$document_id = '';
		
		// get period		
		$p = $this->getPeriod();
				
		// check for limits
		if ($this->getParam('document_id') || $this->getParam('pageUrl')) {
			$doc = owa_coreAPI::entityFactory('base.document');
			
			if ($this->getParam('pageUrl')) {
				$doc->getByColumn($this->getParam('pageUrl'));
				
			} else {
				$doc->load($this->getParam('document_id'));
			}
			
			$document_id = $doc->get('id');
			
			$this->setTitle('Domstream Recordings: ', $doc->get('url'));
			$this->set('document', $doc->_getProperties());
		} else {
			// latest domstream report
			$this->setTitle('Latest Domstreams');
		}
		
		$ds = owa_coreAPI::executeApiCommand(array(
			
			'do'				=> 'getDomstreams',
			'startDate' 		=> $p->getStartDate()->getYyyymmdd(),
			'endDate'			=> $p->getEndDate()->getYyyymmdd(),
			'document_id'		=> $document_id,
			'site_id'			=> $this->getParam('site_id'),
			'page'				=> $this->getParam('page'),
			'resultsPerPage'	=> $this->getParam('resultsPerPage'),
			'format'			=> $this->getParam('format')
		));
		
		$this->set('domstreams', $ds);
		//print_r($ds);
		
		// set view stuff
		$this->setSubview('base.reportDomstreams');
		
							
	}
	
}

/**
 * Domstream Report Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.2.1
 */

class owa_reportDomstreamsView extends owa_view {

	function owa_reportDomstreamsView() {
	
		return owa_reportDomstreamsView::__construct();
	}
	
	function __construct() {
		
		return parent::__construct();
	}
	
	function render() {
		
		$this->body->set('domstreams', $this->get('domstreams'));
		$this->body->set_template('report_domstreams.tpl');
		$doc = $this->get('document');
		$this->body->set('document', $doc);
	}

}

?>