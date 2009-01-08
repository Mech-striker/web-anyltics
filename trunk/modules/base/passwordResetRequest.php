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

require_once(OWA_BASE_DIR.'/owa_controller.php');

/**
 * Password Reset Request Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_passwordResetRequestController extends owa_controller {
	
	function owa_passwordResetRequestController($params) {
	
		return owa_passwordResetRequestController::__construct($params);
	}
	
	function __construct($params) {
		
		return parent::__construct($params);
	}
	
	function action() {
		
		// Check to see if this email exists in the db
		// fetch user object from the db
		$u = owa_coreAPI::entityFactory('base.user');
		$u->getByColumn('email_address', $this->getParam('email_address'));
		$uid = $u->get('user_id');	
		
		// If user exists then fire event and return view
		if (!empty($uid)) {
			
			// Log password reset request to event queue
			$eq = &eventQueue::get_instance();
			$eq->log(array('user_id' => $uid), 'base.reset_password');
		
			// return view
			$this->setView('base.passwordResetForm');
			$this->set('status_msg', $this->getMsg(2000, $this->getParam('email_address')));	
			
		// if user does not exists just return view with error
		} else {
			$this->setView('base.passwordResetForm');
			$this->set('error_msg', $this->getMsg(2001, $this->getParam('email_address')));
		}
		
		return;
	}
}



?>