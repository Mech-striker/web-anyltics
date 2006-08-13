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

require_once 'owa_event_class.php';
require_once 'owa_lib.php';
require_once 'ini_db.php';

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

class owa_request extends owa_event {
	
	/**
	 * First hit flag
	 * 
	 * Used to tell if this request was loaded from the first hit cookie. 
	 *
	 * @var boolean
	 */
	var $first_hit = false;
	
	/**
	 * Time since last request.
	 * 
	 * Used to tell if a new session should be created.
	 *
	 * @var integer $time_since_lastreq
	 */
	var $time_since_lastreq;
	
	/**
	 * Browsecap browser info object
	 *
	 * @var object
	 */
	var $bcap;
	
	/**
	 * Constructor
	 *
	 * @return owa_request
	 * @access public
	 */
	function owa_request() {
		
		//Call to Parent Constructor
		$this->owa_event();
	
		// Set GUID for this request
		$this->properties['request_id'] = $this->guid;
		
		// Record HTTP request variables
		if (!empty($this->properties['referer'])):
			$this->properties['referer_id'] = $this->set_string_guid($this->properties['referer']);
		endif;
		
		//$this->properties['inbound_uri'] = $_SERVER['REQUEST_URI'];
		$this->properties['inbound_uri'] = owa_lib::get_current_url();
		$this->properties['uri'] = $this->stripDocumentUrl($this->properties['inbound_uri']);
		
		// Calc time sinse the last request
		$this->time_sinse_lastreq = $this->time_sinse_last_request();
		
		// Assume request is made by a browser. Can be overwriten by caller later on.
		$this->properties['is_browser'] = true;
		
		// Feed subscription tracking code
		$this->properties['feed_subscription_id'] = $_GET[$this->config['ns'].$this->config['feed_subscription_param']];
		
		// Traffic Source code
		$this->properties['source'] = $_GET[$this->config['source_param']];
		
		return;
	
	}
	
	function process() {
		
		// Do not log if the first_hit cookie is still present.
        if (!empty($_COOKIE[$this->config['ns'].$this->config['first_hit_param']])):
			return;
		endif;

		//Load browscap
		$this->bcap = new owa_browscap($this->properties['ua']);
		
		//Check for Robot
		$is_robot = $this->bcap->robotCheck();
		
		if ($is_robot == true):
			$this->is_robot = true;
		else:
			// If no match in the supplemental browscap db, do a last check for robots strings.
			$this->last_chance_robot_detect($this->properties['ua']);
		endif;
		
		// Log requests from known robots or else dump the request
		if ($this->is_robot == true):
			if ($this->config['log_robots'] == true):
				$this->properties['is_browser'] = false;
				$this->state = 'robot_request';	
			else:
				return;
			endif;
		
		// Log requests from feed readers
		elseif ($this->properties['is_feedreader'] == true):
			if ($this->config['log_feedreaders'] == true):
				$this->properties['is_browser'] = false;
				$this->properties['feed_reader_guid'] = $this->setEnvGUID();
				$this->state = 'feed_request';
			else:
				return;
			endif;	
		else:
			$this->state = 'page_request';
			$this->properties['is_browser'] = true;
			$this->assign_visitor();
			$this->sessionize();
		endif;	
		
		// Make ua id
		$this->properties['ua_id'] = $this->set_string_guid($this->properties['ua']);
		
		// Determine Browser type
		$this->setBrowscap($this->properties['ua']);
		
		// Make os id
		//$this->properties['os'] = $this->determine_os($this->properties['ua']);
		$this->properties['os_id'] = $this->set_string_guid($this->properties['os']);
	
		// Make document id	
		$this->properties['document_id'] = $this->set_string_guid($this->properties['uri']);
		
		// Resolve host name
		if ($this->config['resolve_hosts'] = true):
			$this->resolve_host();
		endif;
		
		//update last-request time cookie
		setcookie($this->config['ns'].$this->config['last_request_param'], $this->properties['sec'], time()+3600*24*365*30, "/", $this->properties['site']);
		
		$this->log();
		
		return;
	}
	
	function log() {
		
		if ($this->state == 'page_request'):
			if ($this->config['delay_first_hit'] == true):	
				if ($this->first_hit != true):
					// If not, then make sure that there is an inbound visitor_id
					if (empty($this->properties['inbound_visitor_id'])):
						// Log request properties to a cookie for processing by a second request and return
						$this->e->debug('Logging this request to first hit cookie.');
						$this->log_first_hit();
						return;
					endif;
				endif;
			endif;
		endif;
		
		$this->eq->log($this->properties, $this->state);
		$this->e->debug('Logged '.$this->state.' to event queue...');
		
		return;
		
	}
	
	/**
	 * Saves Request to DB
	 *
	 */
	function save() {	
		
		// Setup databse acces object
		$this->db = &owa_db::get_instance();
	
		$request = array(
					'request_id',
					'visitor_id', 
					'session_id',
					'inbound_visitor_id', 
					'inbound_session_id',
					'inbound_first_hit_properties',
					'user_name',
					'user_email',
					'timestamp',
					'last_req',
					'year',
					'month',
					'day',
					'dayofweek',
					'dayofyear',
					'weekofyear',
					'hour',
					'minute',
					'second',
					'msec',
					'feed_subscription_id',
					'referer_id',
					'document_id',
					'site',
					'site_id',
					'ip_address',
					'host_id',
					'os',
					'os_id',
					'ua_id',
					'is_new_visitor',
					'is_repeat_visitor',	
					'is_comment',
					'is_entry_page',
					'is_browser',
					'is_robot',
					'is_feedreader'
					);
					
			foreach ($request as $key => $value) {
			
				$sql_cols = $sql_cols.$value;
				$sql_values = $sql_values."'".$this->properties[$this->db->prepare($value)]."'";
				
				if (!empty($request[$key+1])):
				
					$sql_cols = $sql_cols.", ";
					$sql_values = $sql_values.", ";
					
				endif;	
			}
						
			$this->db->query(
				sprintf(
					"INSERT into %s (%s) VALUES (%s)",
					$this->config['ns'].$this->config['requests_table'],
					$sql_cols,
					$sql_values
				)
			);	
				
		return;
		
	}
	
	function setupNewRequest() {
		
		$this->bcap = new owa_browscap($this->properties['ua']);
		
		return;
	}
	
	/**
	 * Load request properties from delayed first hit cookie.
	 *
	 * @param 	array $properties
	 * @access 	public
	 */
	function load_first_hit_properties($properties) {
	
		$this->properties['inbound_first_hit_properties'] = $properties;
		$array = explode(",", $properties);
		
		foreach ($array as $key => $value):
		
			list($realkey, $realvalue) = split('=>', $value);
			$this->properties[$realkey] = $realvalue;
	
		endforeach;
		
		// Mark the request to avoid logging it to the first hit cookie again
		$this->first_hit = true;
		
		// Delete first_hit Cookie
		setcookie($this->config['ns'].$this->config['first_hit_param'], '', time()-3600*24*365*30, "/", $this->properties['site']);
		
		return;
	}
	
	
	/**
	 * Log request properties of the first hit from a new visitor to a special cookie.
	 * 
	 * This is used to determine if the request is made by an actual browser instead 
	 * of a robot with spoofed or unknown user agent.
	 * 
	 * @access 	public
	 */
	function log_first_hit() {
		
		$values = owa_lib::implode_assoc('=>', ',', $this->properties);
		
		setcookie($this->config['ns'].$this->config['first_hit_param'], $values, time()+3600*24*365*30, "/", $this->properties['site']);
		
		return;
	
	}
	
	/**
	 * Assigns visitor IDs
	 *
	 */
	function assign_visitor() {
		
		// is this new visitor?
	
		if (empty($this->properties['inbound_visitor_id'])):
			$this->set_new_visitor();
		else:
			$this->properties['visitor_id'] = $this->properties['inbound_visitor_id'];
			$this->properties['is_repeat_visitor'] = true;
		endif;
		
		return;
	}
	
	/**
	 * Make Session IDs
	 *
	 */
	function sessionize() {
		
			// check for inbound session id
			if (!empty($this->properties['inbound_session_id'])):
				 
				 if (!empty($this->properties['last_req'])):
							
					if ($this->time_sinse_lastreq < $this->config['session_length']):
						$this->properties['session_id'] = $this->properties['inbound_session_id'];			
					else:
					//prev session expired, because no hits in half hour.
						$this->create_new_session($this->properties['visitor_id']);
					endif;
				else:
				//session_id, but no last_req value. whats up with that?  who cares. just make new session.
					$this->create_new_session($this->properties['visitor_id']);
				endif;
			else:
			//no session yet. make one.
				$this->create_new_session($this->properties['visitor_id']);
			endif;
						
		return;
	}
	
	/**
	 * Creates new session id 
	 *
	 * @param 	integer $visitor_id
	 * @access 	public
	 */
	function create_new_session($visitor_id) {
	
		//generate new session ID 
	    $this->properties['session_id'] = $this->set_guid();
	
		//mark entry page flag on current request
		$this->properties['is_entry_page'] = true;
		
		//Set the session cookie
        setcookie($this->config['ns'].$this->config['session_param'], $this->properties['session_id'], time()+3600*24*365*30, "/", $this->properties['site']);
	
		return;
	
	}
	
	/**
	 * Creates new visitor
	 * 
	 * @access 	public
	 *
	 */
	function set_new_visitor() {
	
		// Create guid
        $this->properties['visitor_id'] = $this->set_guid();
		
        // Set visitor cookie
        setcookie($this->config['ns'].$this->config['visitor_param'], $this->properties['visitor_id'] , time()+3600*24*365*30, "/", $this->properties['site']);
		
		$this->properties['is_new_visitor'] = true;
		
		return;
	
	}
	
	/**
	 * Determines the time sinse the last request from this borwser
	 * 
	 * @access private
	 * @return integer
	 */
	function time_sinse_last_request() {
	
        return ($this->properties['timestamp'] - $this->properties['last_req']);
	
	}
	
	/**
	 * Determine the operating system of the browser making the request
	 *
	 * @param string $user_agent
	 * @return string
	 */
	function determine_os($user_agent) {
	
			$matches = array(
				'Win.*NT 5\.0'=>'Windows 2000',
				'Win.*NT 5.1'=>'Windows XP',
				'Win.*(Vista|XP|2000|ME|NT|9.?)'=>'Windows $1',
				'Windows .*(3\.11|NT)'=>'Windows $1',
				'Win32'=>'Windows [prior to 1995]',
				'Linux 2\.(.?)\.'=>'Linux 2.$1.x',
				'Linux'=>'Linux [unknown version]',
				'FreeBSD .*-CURRENT$'=>'FreeBSD -CURRENT',
				'FreeBSD (.?)\.'=>'FreeBSD $1.x',
				'NetBSD 1\.(.?)\.'=>'NetBSD 1.$1.x',
				'(Free|Net|Open)BSD'=>'$1BSD [unknown]',
				'HP-UX B\.(10|11)\.'=>'HP-UX B.$1.x',
				'IRIX(64)? 6\.'=>'IRIX 6.x',
				'SunOS 4\.1'=>'SunOS 4.1.x',
				'SunOS 5\.([4-6])'=>'Solaris 2.$1.x',
				'SunOS 5\.([78])'=>'Solaris $1.x',
				'Mac_PowerPC'=>'Mac OS [PowerPC]',
				'Mac OS X'=>'Mac OS X',
				'X11'=>'UNIX [unknown]',
				'Unix'=>'UNIX [unknown]',
				'BeOS'=>'BeOS [unknown]',
				'QNX'=>'QNX [unknown]',
			);
			$uas = array_map(create_function('$a', 'return "#.*$a.*#";'), array_keys($matches));
			
			return preg_replace($uas, array_values($matches), $user_agent);
		
	}
	
	function determine_os_new($user_agent) {
		
		$db = new ini_db($this->config['os.ini'], $sections = true);
		$string = $db->fetch_replace($user_agent);
		
		return $string;
	}
	
	/**
	 * Determine the type of browser
	 * 
	 * @param 	string
	 * @access 	private
	 */
	function setBrowscap($user_agent) {
		
		if ($this->bcap->browscap->browser != 'Default Browser'):
			$this->properties['browser_type'] = $this->bcap->browscap->browser;
			$this->properties['os'] = $this->bcap->browscap->platform;
		elseif ($this->bcap->browscap_supplemental->browser != 'Default Browser'):
			$this->properties['browser_type'] = $this->bcap->browscap_supplemental->browser;
			if (!empty($this->browscap_supplemental->platform)):
				$this->properties['os'] = $this->bcap->browscap_supplemental->platform;
			else:
				$this->properties['os'] = $this->determine_os($user_agent);
			endif;
		else:
			$this->properties['os'] = $this->determine_os($user_agent);
		endif;
		
		return;
	}
	
	
	function last_chance_robot_detect($user_agent) {
		
		$db = new ini_db($this->config['robots.ini']);
		$match = $db->match($user_agent);
		
		if (!empty($match)):
			$this->e->debug(sprintf('Last chance robot detect string: %s', $match[0]));
			$this->is_robot = true;
		endif;
		
		return;
	}
	
	
	
	
}

?>
