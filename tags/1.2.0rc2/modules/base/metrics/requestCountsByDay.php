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

class owa_requestCountsByDay extends owa_metric {
	
	function owa_requestCountsByDay($params = null) {
		
		return owa_requestCountsByDay::__construct($params);
		
	}
	
	function __construct($params = null) {
	
		return parent::__construct($params);
	}
	
	function calculate() {
				
		$this->db->selectFrom('owa_request', 'request');
		$this->db->selectColumn("request.month, request.day, request.year, 
							count(distinct request.visitor_id) as unique_visitors, 
							count(distinct request.session_id) as sessions, 
							count(request.id) as page_views");
		
		$p = $this->getPeriod();
		$num_months = $p->getMonthsDifference();
		
		// set groupby and orderby
		if ($num_months > 3):
			$this->db->groupBy('year');
			$this->db->groupBy('month');
			$this->db->orderBy('year', $this->getOrder());
			$this->db->orderBy('month', $this->getOrder());
		else:
			$this->db->groupBy('year');
			$this->db->groupBy('month');
			$this->db->groupBy('day');
			$this->db->orderBy('year', $this->getOrder());
			$this->db->orderBy('month', $this->getOrder());
			$this->db->orderBy('day', $this->getOrder());
		endif;
		
		return $this->db->getAllRows();
				
	}
	
	
}


?>