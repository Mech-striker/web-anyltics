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

require_once OWA_BASE_CLASS_DIR.DIRECTORY_SEPARATOR.'event.php';
require_once OWA_BASE_DIR.DIRECTORY_SEPARATOR.'owa_lib.php';
require_once OWA_BASE_DIR.DIRECTORY_SEPARATOR.'ini_db.php';

/**
 * Concrete Page Request Event Class
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_requestEvent extends owa_event {
	
	/**
	 * First hit flag
	 * 
	 * Used to tell if this request was loaded from the first hit cookie. 
	 *
	 * @var boolean
	 */
	var $first_hit = false;
	
	/**
	 * Constructor
	 *
	 * @return owa_request
	 * @access public
	 */
	function owa_requestEvent() {
		
		//Call to Parent Constructor
		$this->owa_event();
	
		return;
	
	}
	
	/**
	 * Log page request to event queue
	 *
	 */
	function log() {
		
		if ($this->state == 'page_request' || $this->state == 'first_page_request'):
			if ($this->config['delay_first_hit'] == true):	
				if ($this->first_hit != true):
					// If not, then make sure that there is an inbound visitor_id
					if (empty($this->properties['inbound_visitor_id'])):
						// Log request properties to a cookie for processing by a second request and return
						$this->e->debug('Logging this request to first hit cookie.');
						return $this->log_first_hit();
					endif;
				endif;
			endif;
		endif;
		
		$this->e->debug('Logging '.'base.'.$this->state.' to event queue...');
		
		return $this->eq->log($this->properties, 'base.'.$this->state);
		
	}	
}

?>