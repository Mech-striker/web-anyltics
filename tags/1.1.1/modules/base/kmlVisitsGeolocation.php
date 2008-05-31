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
require_once(OWA_BASE_DIR.'/owa_reportController.php');

/**
 * Visits Geolocation Report Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_kmlVisitsGeolocationController extends owa_reportController {

	function owa_kmlVisitsGeolocationController($params) {
		
		$this->owa_reportController($params);
		$this->priviledge_level = 'viewer';
	
		return;
	}
	
	function action() {

		// Load the core API
		$api = &owa_coreAPI::singleton($this->params);
		
		$data = array();
		
		$data['params'] = $this->params;
			
		if ($this->params['site_id']):
			//get site labels
			$s = owa_coreAPI::entityFactory('base.site');
			$s->getByColumn('site_id', $this->params['site_id']);
			$data['site_name'] = $s->get('name');
			$data['site_description'] = $s->get('description');
		else:
			$data['site_name'] = 'All Sites';
			$data['site_description'] = 'All Sites Tracked by OWA';
		endif;
		
		$data['latest_visits'] = $api->getMetric('base.latestVisits', array(
		
			'constraints'	=> array('site_id'	=> $this->params['site_id']),
			'limit'			=> 15,
			//'period'		=> 'last_thirty_days',
			'orderby'		=> array('session.timestamp'),
			'order'			=> 'DESC'
		
		));
		

		
		$data['view'] = 'base.kmlVisitsGeolocation';
			
		
		return $data;	
		
	}
	
}
		


/**
 * Visits Geolocation KML View
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_kmlVisitsGeolocationView extends owa_view {
	
	function owa_kmlVisitsGeolocationView() {
		
		$this->owa_view();
		$this->priviledge_level = 'guest';
		
		return;
	}
	
	function construct($data) {
		
		$this->t->set_template('wrapper_blank.tpl');
		
		// load body template
		$this->body->set_template('kml_visits_geolocation.tpl');
		$this->body->set('visits', $data['latest_visits']);
		$this->body->set('site_name', $data['site_name']);
		$this->body->set('site_domain', $data['site_domain']);
		$this->body->set('site_description', $data['site_description']);
	
		$this->_setLinkState();
		
		$this->body->set('xml', '<?xml version="1.0" encoding="UTF-8"?>');
				
		header('Content-type: application/vnd.google-earth.kml+xml; charset=UTF-8', true);
		
		header('Content-Disposition: inline; filename="owa.kml"');
		//header('Content-type: text/plain', true);		
		return;
	}
	
	
}


?>