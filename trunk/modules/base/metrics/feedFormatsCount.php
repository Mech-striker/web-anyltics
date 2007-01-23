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

require_once(OWA_BASE_CLASSES_DIR.'owa_metric.php');

/**
 * Feed Formats Count
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_feedFormatsCount extends owa_metric {
	
	function owa_feedFormatsCount($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function generate() {
		
		$this->params['select'] = "count(id) as count, 
									feed_format";
		
		
		//$this->params['orderby'] = array('year', 'month', 'day');
		
		$this->setTimePeriod($this->params['period']);
		
		$f = owa_coreAPI::entityFactory('base.feed_request');
		
		
		$this->params['groupby'] = array('feed_format');
		
		return $f->query($this->params);
		
		/*
		 
		SELECT 
			count(request_id) as count, 
			feed_format
		FROM 
			%s as feed_requests
		WHERE
			true 
			%s 
			%s
		GROUP BY
			feed_format
		",
			$this->setTable($this->config['feed_requests_table']),
			$this->time_period($this->params['period']),
			$this->add_constraints($this->params['constraints'])
		);
		 
		 */
		
	}
	
	
}


?>