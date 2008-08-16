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
 * Dashboard Core metrics By Day
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_dashCoreByDay extends owa_metric {
	
	function owa_dashCoreByDay($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function calculate() {
		
		$db = owa_coreAPI::dbSingleton();
		
		$db->selectFrom('owa_session', 'session');
		
		$db->selectColumn("session.month, 
							session.day, 
							session.year, 
							count(distinct session.visitor_id) as unique_visitors, 
							count(session.id) as sessions, 
							sum(session.num_pageviews) as page_views");
									
		// pass constraints set by caller into where clause
		$db->multiWhere($this->getConstraints());
		$db->groupBy('day');
		$db->groupBy('month');
		$db->orderBy('year');
		$db->orderBy('month');
		$db->orderBy('day');
		
		$ret = $db->getAllRows();
		
		return $ret;
		
		
/*
		
		$this->params['select'] = "session.month, 
			session.day, 
			session.year, 
			count(distinct session.visitor_id) as unique_visitors, 
			count(session.id) as sessions, 
			sum(session.num_pageviews) as page_views ";
		
		
		$this->params['groupby'] = array('day', 'month');

		$this->params['orderby'] = array('year', 'month', 'day');
		
		$this->setTimePeriod($this->params['period']);

		$s = owa_coreAPI::entityFactory('base.session');
		
		return $s->query($this->params);

*/		
	}
	
	
}


?>