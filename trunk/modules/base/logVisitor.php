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

require_once(OWA_BASE_DIR.'/owa_lib.php');
require_once(OWA_BASE_DIR.'/owa_controller.php');
require_once(OWA_BASE_DIR.DIRECTORY_SEPARATOR.'owa_coreAPI.php');


/**
 * Log New Visitor Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_logVisitorController extends owa_controller {
	
	function owa_logVisitorController($params) {
		return owa_logVisitorController::__construct($params);
	}
	
	function __construct($params) {
		return parent::__construct($params);
	}
	
	function action() {
		
		// Control logic
		
		$v = owa_coreAPI::entityFactory('base.visitor');
	
		$v->setProperties($this->params);
	
		// Set Primary Key
		$v->set('id', $this->params['visitor_id']);
		
		$v->set('user_name', $this->params['user_name']);
		$v->set('user_email', $this->params['user_email']);
		$v->set('first_session_id', $this->params['session_id']);
		$v->set('first_session_year', $this->params['year']);
		$v->set('first_session_month', $this->params['month']);
		$v->set('first_session_day', $this->params['day']);
		$v->set('first_session_dayofyear', $this->params['dayofyear']);
		$v->set('first_session_timestamp', $this->params['timestamp']);		
		
		$v->create();
			
		return;
			
	}
	
	
}

?>