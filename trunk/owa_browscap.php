<?

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

require_once(OWA_BASE_DIR.DIRECTORY_SEPARATOR.'owa_base.php');
require_once(OWA_BASE_DIR.DIRECTORY_SEPARATOR.'ini_db.php');

/**
 * Browscap Class
 * 
 * Used to load and lookup user agents in a local Browscap file
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_browscap extends owa_base {
	
	
	/**
	 * main browscap_db maintained by Gary Keith's 
	 * Browser Capabilities project.
	 *
	 * @var array
	 */
	var $browscap_db;
	
	/**
	 * Browscap Record for current User agent
	 *
	 * @var unknown_type
	 */
	var $browser;
	
	/**
	 * Current user Agent
	 *
	 * @var string
	 */
	var $ua;
	
	function owa_browscap($ua = '') {
		
		$this->owa_base();
		
		// set user agent
		$this->ua = $ua;
		
		// Load main browscap
		$this->browscap_db = $this->load($this->config['browscap.ini']);
		
		//lookup robot in main browscap db
		$this->browser = $this->lookup($this->ua);
		$this->e->debug('Browser Name: '. $this->browser->Browser);
		
		return;
	}
	
	function robotCheck() {
		
		if ($this->browser->Crawler == true):
			return true;
		else:
			if($this->robotRegexCheck() == true):
				return true;
			else:
				return false;
			endif;
		endif;
	}
	
	function lookup($user_agent) {
		
		$cap=null;
		
		foreach ($this->browscap_db as $key=>$value) {
			  if (($key!='*')&&(!array_key_exists('Parent',$value))) continue;
			  $keyEreg='^'.str_replace(
			   array('\\','.','?','*','^','$','[',']','|','(',')','+','{','}','%'),
			   array('\\\\','\\.','.','.*','\\^','\\$','\\[','\\]','\\|','\\(','\\)','\\+','\\{','\\}','\\%'),
			   $key).'$';
			  if (preg_match('%'.$keyEreg.'%i',$user_agent))
			  {
			   $cap=array('browser_name_regex'=>strtolower($keyEreg),'browser_name_pattern'=>$key)+$value;
			   $maxDeep=8;
			   while (array_key_exists('Parent',$value)&&(--$maxDeep>0))
			    $cap += ($value = $this->browscap_db[$value['Parent']]);
			   break;
			  }
		 }
		 
		return ((object)$cap);
	
	}
	
	
	
	function load($file) {
	
		return parse_ini_file($file, true);
		
	}
	
	function robotRegexCheck() {
		
		$db = new ini_db($this->config['robots.ini']);
		$match = $db->match($this->ua);
		
		if (!empty($match)):
			$this->e->debug(sprintf('Last chance robot detect string: %s', $match[0]));
			$this->browser->Crawler = true;
			return true;
		else:
			return false;
		endif;
	
	}
	
	
}



?>