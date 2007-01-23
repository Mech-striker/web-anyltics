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
 * Top Entry Pages Metric
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_topEntryPages extends owa_metric {
	
	function owa_topEntryPages($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function generate() {
		
		$s = owa_coreAPI::entityFactory('base.session');
		
		$d = owa_coreAPI::entityFactory('base.document');
		
		$this->params['related_objs'] = array('first_page_id' => $d);
		
		$this->setTimePeriod($this->params['period']);
		
		$this->params['select'] = "count(session.id) as count,
									document.page_title,
									document.page_type,
									document.url,
									document.id";
								
		$this->params['groupby'] = array('session.first_page_id');
		
		$this->params['orderby'] = array('count');
		
		return $s->query($this->params);
		
	}
	
	
}


?>