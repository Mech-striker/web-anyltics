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

if(!class_exists('owa_observer')) {
	require_once(OWA_BASE_DIR.'owa_observer.php');
}	

/**
 * OWA user management Event handlers
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.2.1
 */

class owa_domstreamHandlers extends owa_observer {
    	
    /**
     * Notify method
     *
     * @param 	object $event
     * @access 	public
     */
    function notify($event) {
		
    	$ds = owa_coreAPI::entityFactory('base.domstream');
		$ds->set('id', $ds->generateRandomUid());
		//$ds->set('id', $event->get('guid'));
		$ds->set('visitor_id', $event->get('inbound_visitor_id'));
		$ds->set('session_id', $event->get('inbound_session_id'));
		$ds->set('site_id', $event->get('site_id'));
		$ds->set('document_id', $ds->generateId($event->get('page_url')));
		
		$ds->set('page_url', $event->get('page_url'));
		$ds->set('events', $event->get('stream_events'));
		$ds->set('duration', $event->get('duration'));
		$ds->set('timestamp', $event->get('timestamp'));
		//require_once(OWA_DIR.'owa_lib.php');
		$ds->set('yyyymmdd', owa_lib::timestampToYyyymmdd($event->get('timestamp')));
		owa_coreAPI::debug("yyyymmdd: ".owa_lib::timestampToYyyymmdd($event->get('timestamp')));
		$ds->create();
    }
    
}

?>