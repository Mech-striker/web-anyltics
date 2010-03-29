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

/**
 * OWA Request Params
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0 
 */

class owa_requestContainer {
	
	var $cli_args;
	var $is_https;
	var $owa_params = array();
	var $cookies = array();
	var $owa_cookies = array();
	var $session = array();
	var $request = array();
	var $server;
	var $guid;
	var $state;
	var $request_type;
	
	/**
	 * Singleton returns request params
	 *
	 * @return array
	 * @todo DEPRICATED
	 */
	function &getInstance() {
		
		static $params;
		
		if(empty($params)):
			
			$params = owa_lib::getRequestParams();
			// Clean Input arrays
			$params = owa_lib::inputFilter($params);
			//strip all params that do not include the namespace
			$params = owa_lib::stripParams($params, owa_coreAPI::getSetting('base', 'ns'));
			// translate certain request variables that are reserved in javascript
			$params = owa_lib::rekeyArray($params, array_flip(owa_coreAPI::getSetting('base', 'reserved_words')));
			
			$params['guid'] = crc32(microtime().getmypid());
			
			return $params;
			
		else:
		
			return $params;
		
		endif;
		
	}
	
	function owa_requestContainer() {
	
		return owa_requestContainer::__construct();
	}
	
	function __construct() {
		
		$this->guid = crc32(microtime().getmypid());
		
		// CLI args
		if (array_key_exists('argv', $_SERVER)) {
			
			$this->cli_args = $_SERVER['argv'];
		}
		
		// php's server variables
		$this->server = $_SERVER;
		
		// files
		if (!empty($_FILES)) {
			$this->files = $_FILES;	
		}
		
		// cookies
		if (!empty($_COOKIE)) {
			$this->cookies = $_COOKIE;
			$this->owa_cookies = owa_lib::stripParams($_COOKIE, owa_coreAPI::getSetting('base', 'ns'));
		}
		
		// cookies
		if (!empty($_SESSION)) {
			$this->session = $_SESSION;
		}
		
		/* STATE CONTAINER */
		
		// state
		$this->state = owa_coreAPI::supportClassFactory('base', 'state');
		// merges session
		if (!empty($this->session)) {
			$this->state->addStores(owa_lib::stripParams($this->session, owa_coreAPI::getSetting('base', 'ns')));
		}
		
		// merges cookies
		foreach ($this->owa_cookies as $k => $cookie) {
			
			if (strpos($cookie, "|||")) {
				$cookie = owa_lib::assocFromString($cookie);
			}
			
			$this->state->loadState($k, '', $cookie, 'cookie');
		}
		
		
		//print_r($this->state);
		// create request params from GET or POST or CLI args
		$params = array();
		
		if (!empty($_POST)):
			// get params from _POST
			$params = $_POST;
			$this->request_type = 'post';
		elseif (!empty($_GET)):
			// get params from _GET
			$params = $_GET;
			$this->request_type = 'get';
		elseif (!empty($this->cli_args)):
			// get params from the command line args
			// $argv is a php super global variable
			
			   for ($i=1; $i<count($this->cli_args);$i++)
			   {
				   $it = split("=",$this->cli_args[$i]);
				   $params[$it[0]] = $it[1];
			   }
			   $this->request_type = 'cli';
		endif;
		
		// merge in cookies into the request params
		if (!empty($_COOKIE)) {
			//$params = array_merge($params, $this->owa_cookies);
		}
		
		// Clean Input arrays
		$this->request = owa_lib::inputFilter($params);	
		if (array_key_exists('owa_action', $this->request)) {
			
			$this->request['owa_action'] = owa_lib::fileInclusionFilter($this->request['owa_action']);
		}
		
		if (array_key_exists('owa_do', $this->request)) {
			
			$this->request['owa_do'] = owa_lib::fileInclusionFilter($this->request['owa_do']);
		}
		// strip owa namespace
		$this->owa_params = owa_lib::stripParams($this->request, owa_coreAPI::getSetting('base', 'ns'));
		// translate certain request variables that are reserved in javascript
		$this->owa_params = owa_lib::rekeyArray($this->owa_params, array_flip(owa_coreAPI::getSetting('base', 'reserved_words')));
		
		if(isset($_SERVER['HTTPS'])):
			$this->is_https = true;
		endif;
			
		return;
	
	}
		
	function getParam($name) {
		
		if (array_key_exists($name, $this->owa_params)) {
			return $this->owa_params[$name];
		} else {
			return false;
		}

	}
	
	function setParam($name, $value) {
		
		$this->owa_params[$name] = $value;
		return true;
	}
	
	function getCookie($name) {
		
		if (array_key_exists($name, $this->cookies)) {
			return $this->cookies[$name];
		} else {
			return false;
		}
		
	}
	
	function getRequestParam($name) {
	
		if (array_key_exists($name, $this->request)) {
			return $this->request[$name];
		} else {
			return false;
		}
	}
	
	function getAllRequestParams() {
		
		return $this->request;
	}
	
	function getAllOwaParams() {
		
		return $this->owa_params;
	}
	
	function mergeParams($params) {
		
		$this->owa_params = array_merge($this->owa_params, $params);
		return;	
	}
	
	function getServerParam($name) {
	
		if (array_key_exists($name, $this->server)) {
			return $this->server[$name];
		} else {
			return false;
		}
	}
	
	function decodeRequestParams() {
	
		$params = array();
		// Apply caller specific params
		foreach ($this->owa_params as $k => $v) {
				
			$params[$k] = base64_decode(urldecode($v));
				
		}
		
		// clean params after decode
		$params = owa_lib::inputFilter($params);
		// replace owa params
		$this->owa_params = $params;
		//debug
		owa_coreAPI::debug('decoded OWA params: '. print_r($this->owa_params, true));
		return;
	
	}
	
	function getOwaCookie($name) {
		
		if (array_key_exists($name, $this->owa_cookies)) {
			return $this->owa_cookies[$name];
		} else {
			return false;
		}
		
	}
	
}

?>