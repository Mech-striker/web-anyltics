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

require_once OWA_PEARLOG_DIR . '/Log.php';
require_once 'owa_observer.php';

/**
 * Event Queue
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */
class eventQueue {

	/**
	 * Configuration
	 *
	 * @var array
	 */
	var $config;
	
	/**
	 * Constructor
	 *
	 * @return eventQueue
	 */
	function eventQueue() {
 
		return;	
	}

	/**
	 * Event Queue factory
	 * @static 
	 * @return 	object
	 * @access 	public
	 */
	function &get_instance() {
	
		static $eq;
	
		$this->config = &owa_settings::get_settings();	
		
		if (!isset($eq)):
			// Create an async event queue
			if ($this->config['async_db'] == true):
				$conf = array('mode' => 600, 'timeFormat' => '%X %x');
				$eq = &Log::singleton('async_queue', $this->config['async_log_file'], 'ident', $conf);
				$eq->_lineFormat = '%1$s|*|%2$s|*|[%3$s]|*|%4$s|*|%5$s';
				// not sure why this is needed but it is.
				$eq->_filename	= $this->config['async_log_file'];
					
				// This observer will watch the queue and exec a new php process that will process the events
				$async_helper = &owa_observer::factory(OWA_REQ_PLUGINS_DIR.'/async', 'async_helper', PEAR_LOG_INFO);
				$eq->attach($async_helper);
					
			else:
				//Create a normal event queue using 'queue' which is an extension to PEAR LOG.
				$eq = Log::singleton('queue', '', 'event_queue');
			
				if ($dir = @opendir(OWA_REQ_PLUGINS_DIR)):
					while (($file = @readdir($dir)) !== false) {
						if (strstr($file, '.php') &&
							substr($file, -1, 1) != "~" &&
							substr($file,  0, 1) != "#"):
								$class  = substr($file, 9, -4);
								$plugin_name = &owa_observer::factory(OWA_REQ_PLUGINS_DIR, $class, PEAR_LOG_INFO);
								$eq->attach($plugin_name);
						endif;
						
					}
		
					@closedir($dir);
				endif;	
			endif;
		endif;
	
		return $eq;
	}

}

?>
