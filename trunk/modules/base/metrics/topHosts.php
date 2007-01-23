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
 * Top Hosts Metric
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_topHosts extends owa_metric {
	
	function owa_topHosts($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function generate() {
		
		$this->params['select'] = "count(session.host_id) as count, " .
				"					host.id,
									host.host,
									host.full_host,
									host.ip_address";
		
		$this->setTimePeriod($this->params['period']);
		
		$s = owa_coreAPI::entityFactory('base.session');
		$h = owa_coreAPI::entityFactory('base.host');
		
		$this->params['related_objs'] = array('host_id' => $h);
		$this->params['groupby'] = array('host.id');
		$this->params['orderby'] = array('count');
		$this->params['order'] = 'DESC';
	
		return $s->query($this->params);
		
		/*
		 
		SELECT 
			count(sessions.host_id) as count,
			hosts.id,
			hosts.host,
			hosts.full_host,
			hosts.ip_address
			
		FROM 
			%s as sessions, %s as hosts 
		WHERE
			sessions.host_id = hosts.id
			%s
			%s 
		GROUP BY
			hosts.id
		ORDER BY
			count DESC
		LIMIT 
			%s",
			$this->setTable($this->config['sessions_table']),
			$this->setTable($this->config['hosts_table']),
			$this->time_period($this->params['period']),
			$this->add_constraints($this->params['constraints']),
			$this->params['limit']
		);
		 
		 */
		
	}
	
	
}


?>