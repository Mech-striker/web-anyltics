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

require_once(OWA_BASE_DIR.'/owa_view.php');
require_once(OWA_BASE_CLASS_DIR.'installController.php');

/**
 * Installation Finish
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */


class owa_installFinishController extends owa_installController {

	function owa_installFinishController($params) {
	
		return owa_installFinishController::__construct($params); 	
	}
	
	function __construct($params) {
		
		return parent::__construct($params);
	}
	
	function action() {
	
		// Persist install complete flag. 
		$this->c->setSetting('base', 'install_complete', true);
		$save_status = $this->c->save();
		
		if ($save_status == true):
			$this->e->notice('Install Complete Flag added to configuration');
		else:
			$this->e->notice('Could not persist Install Complete Flag to the Database');
		endif;
		
		$site = owa_coreAPI::entityFactory('base.site');
		$site->getByPk('id', '1');
		$this->setView('base.install');
		$this->setSubview('base.installFinish');
		$this->set('site_id', $site->get('site_id'));
		$this->set('status_code', $this->getParam('status_code')); 
		$this->set('u', $this->getParam('u'));
		$this->set('k', $this->getParam('k'));
		
		return;
	}
}


class owa_installFinishView extends owa_view {
	
	function owa_installFinishView() {
				
		return owa_installFinishView::__construct();
	}
	
	function __construct() {
		
		return parent::__construct();
	}
	
	function render($data) {
		
		// Set Page title
		$this->t->set('page_title', 'Installation Complete');
		
		// Set Page headline
		$this->body->set('headline', 'Installation is Complete');
		
		$this->body->set('site_id', $this->get('site_id'));
		$this->body->set('u', $this->get('u'));
		$this->body->set('key', $this->get('k'));
		// load body template
		$this->body->set_template('install_finish.tpl');
		
		return;
	}
	
	
}





?>