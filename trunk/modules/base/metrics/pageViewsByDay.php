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
 * Page View Metrics By Day
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_pageViewsByDay extends owa_metric {
	
	function owa_pageViewsByDay($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function generate() {
		
		$s = owa_coreAPI::entityFactory('base.session');
		
		$this->setTimePeriod($this->params['period']);
		
		$this->params['select'] = "sum(session.num_pageviews) as page_views,
									session.month, 
									session.day, 
									session.year";
								
		
		$this->params['orderby'] = array('session.year', 'session.month', 'session.day');
	
		return $s->query($this->params);
		
		/*
		
		$sql = sprintf("select 
				sum(sessions.num_pageviews) as page_views,
				sessions.month, 
				sessions.day, 
				sessions.year
			from
				%s as sessions
			where
				%s 
				%s
			group by 
				sessions.%s
			ORDER BY
				sessions.year %6\$s, 
				sessions.month %6\$s, 
				sessions.day %6\$s",
				
				$this->setTable($this->config['sessions_table']),
				$this->time_period($this->params['period']),
				$this->add_constraints($this->params['constraints']),
				$this->params['group_by'],
				$this->params['order']
			);
		
		
		
		return $this->db->get_results($sql);
		
		*/
	}
	
	
}


?>