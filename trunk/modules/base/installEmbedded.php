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
 * Embedded Install Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_installEmbeddedController extends owa_controller {
	
	function owa_installEmbeddedController($params) {
		$this->owa_controller($params);
		$this->priviledge_level = 'guest';
	}
	
	function action() {
		
	    $api = &owa_coreAPI::singleton();
	    
		// install schema
		$status = $api->modules['base']->install();
		    
		// insert default site
		if ($status == true):   
		    
			$site = owa_coreAPI::entityFactory('base.site');
			$site->set('site_id', $this->params['site_id']);
			$site->set('name', $this->params['name']);
			$site->set('description', $this->params['description']);
			$site->set('domain', $this->params['domain']);
			$site->set('site_family', $this->params['site_family']);
					
			$site->create();
			
			//clean up any open db connection
			if ($this->config['async_db'] == false):
				$db = &owa_coreAPI::dbSingleton();
				$db->close();
			endif;
				
			return true;
		else:
			// owa already installed or some other problem
	    	return false;
		endif;	
			
	}
	
	
}

?>