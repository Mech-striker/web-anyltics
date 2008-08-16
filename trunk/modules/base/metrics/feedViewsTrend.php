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

class owa_feedViewsTrend extends owa_metric {
	
	function owa_feedViewsTrend($params = null) {
		
		$this->params = $params;
		
		$this->owa_metric();
		
		return;
		
	}
	
	function calculate() {
		
		$db = owa_coreAPI::dbSingleton();

		$db->selectFrom('owa_feed_request');
		$db->selectColumn("count(id) as fetch_count, count(distinct feed_reader_guid) as reader_count, year, month, day");
		// pass constraints into where clause
		$db->multiWhere($this->getConstraints());

		return $db->getAllRows();

		
		/*

		$this->params['select'] = "count(id) as fetch_count,
									count(distinct feed_reader_guid) as reader_count,
									year,
									month,
									day";
		
		
		$this->setTimePeriod($this->params['period']);
		
		$f = owa_coreAPI::entityFactory('base.feed_request');
		
		return $f->query($this->params);

*/		
	}
	
	
}


?>