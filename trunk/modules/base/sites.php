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
require_once(OWA_BASE_DIR.'/owa_view.php');

/**
 * Users Roster View
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_sitesView extends owa_view {
	
	function owa_sitesView() {
		
		$this->owa_view();
		$this->priviledge_level = 'admin';
		
		return;
	}
	
	function construct() {
		
		//page title
		$this->t->set('page_title', 'Sites Roster');
		
		// load body template
		$this->body->set_template('sites.tpl');
		
		$this->body->set('headline', 'Web Sites Roster');
		
		$s = owa_coreAPI::entityFactory('base.site');
		$sites = $s->find();
		
		$this->body->set('sites', $sites);
		
		
		//$tt = owa_coreAPI::entityFactory('base.testtable');
		//$tt->dropTable();
		//print_r($tt);

		
		return;
	}
	
	
}


?>