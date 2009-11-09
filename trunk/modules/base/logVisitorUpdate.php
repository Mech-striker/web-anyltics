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
 * Log Visitor Update Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_logVisitorUpdateController extends owa_controller {
	
	function owa_logVisitorUpdateController($params) {
		$this->owa_controller($params);
	}
	
	function action() {
		
		// Control logic
		
		$v = owa_coreAPI::entityFactory('base.visitor');
		
		$event = $this->getParam('event');
		
		$v->getByPk('id', $event->get('visitor_id'));
		
		if ($event->get('user_name')) {
			$v->set('user_name', $event->get('user_name'));
		}
		
		if ($event->get('user_email')) {
			$v->set('user_email', $event->get('user_email'));
		}
		$v->set('last_session_id', $event->get('session_id'));
		$v->set('last_session_year', $event->get('year'));
		$v->set('last_session_month', $event->get('month'));
		$v->set('last_session_day', $event->get('day'));
		$v->set('last_session_dayofyear', $event->get('dayofyear'));		
		
		$id = $v->get('id');
		
		if (!empty($id)) {
			$v->update();
			
		// insert the visitor object just in case it's not found in the db	
		} else {
			$v->set('id', $event->get('visitor_id'));
			$v->set('first_session_id', $event->get('session_id'));
			$v->set('first_session_year', $event->get('year'));
			$v->set('first_session_month', $event->get('month'));
			$v->set('first_session_day', $event->get('day'));
			$v->set('first_session_dayofyear', $event->get('dayofyear'));	
			$v->set('first_session_timestamp', $event->get('timestamp'));		
			$v->create();
		}
		
		return;
			
	}
	
	
}

?>