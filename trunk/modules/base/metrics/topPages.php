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

require_once(OWA_BASE_DIR.DIRECTORY_SEPARATOR.'owa_metric.php');

/**
 * Top Web Pages Metric
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_toppages extends owa_metric {
	
	function owa_topPages($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function generate() {
		
		$this->params['select'] = "count(request.document_id) as count,
			document.page_title,
			document.page_type,
			document.url,
			document.id as document_id";
		
		$this->params['use_summary'] = true;
		
		$this->params['orderby'] = array('year', 'month', 'day');
		
		$this->setTimePeriod($this->params['period']);
		
		$r = owa_coreAPI::entityFactory('base.request');
		$d = owa_coreAPI::entityFactory('base.document');
		
		$this->params['related_objs'] = array('document_id' => $d);
		$this->params['groupby'] = array('document.id');
		$this->params['orderby'] = array('count');
		$this->params['order'] = 'DESC';
		
		$this->params['constraints']['document.page_type'] = array('operator' => '!=', 'value' => 'feed');
		
		return $r->query($this->params);
		
	}
	
	
}


?>