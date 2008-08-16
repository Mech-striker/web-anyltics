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

/**
 * Dashboard Count Metrics
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_dashCountsTraffic extends owa_metric {
	
	function owa_dashCountsTraffic($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function calculate() {
		
		$db = owa_coreAPI::dbSingleton();
		$db->selectColumn("count(distinct session.visitor_id) as unique_visitors, 
			sum(session.is_new_visitor) as new_visitor, sum(session.is_repeat_visitor) as repeat_visitor,
			count(session.id) as sessions, 
			sum(session.num_pageviews) as page_views");
									
		$db->selectFrom('owa_session', 'session');
		
		$db->join(OWA_SQL_JOIN_LEFT_OUTER, 'owa_referer', 'referer', 'referer_id', 'referer.id');		

		// pass constraints set by caller into where clause
		$db->multiWhere($this->getConstraints());

		$ret = $db->getAllRows();

		return $ret;

	
		/*

		$this->params['select'] = "count(distinct session.visitor_id) as unique_visitors, 
			sum(session.is_new_visitor) as new_visitor, sum(session.is_repeat_visitor) as repeat_visitor,
			count(session.id) as sessions, 
			sum(session.num_pageviews) as page_views ";
		
		$this->params['use_summary'] = true;
		
		$this->setTimePeriod($this->params['period']);
		
		$s = owa_coreAPI::entityFactory('base.session');
		
		$ref = owa_coreAPI::entityFactory('base.referer');
		
		$this->params['related_objs'] = array('referer_id' => $ref);
		
		return $s->query($this->params);
*/
		
	}
	
	
}


?>