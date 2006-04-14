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

require_once(WA_BASE_DIR.'/owa_settings_class.php');

/**
 * Geo-location abstract class
 * 
 * Looks up the geographic location of a request based on IP address lookups in a variety of
 * databses or web services.
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */
class owa_location {
	
	/**
	 * City 
	 *
	 * @var string
	 */
	var $city;
	
	/**
	 * Country
	 *
	 * @var string
	 */
	var $country;
	
	/**
	 * Latitude coordinates
	 *
	 * @var string
	 */
	var $latitude;
	
	/**
	 * Longitude coordinates
	 *
	 * @var string
	 */
	var $longitude;
	
	/**
	 * Location of concrete class plugins
	 *
	 * @var unknown_type
	 */
	var $plugin_dir;
	
	/**
	 * Configuration
	 *
	 * @var array
	 */
	var $config;
	
	/**
	 * Constructor
	 *
	 * @return owa_location
	 */
	function owa_location() {
		
		$this->config = &owa_settings::get_settings();
		
		return;
	}
	
	function &factory($class_path, $plugin, $name = '', $ident = '', $conf = array()) {
		
        $classfile = $class_path . $plugin . '.php';

        $class = 'owa_'.$plugin;
        
        /*
         * Attempt to include our version of the named class, but don't treat
         * a failure as fatal.  The caller may have already included their own
         * version of the named class.
         */
        if (!class_exists($class)) {
            include_once $classfile;
        }

        /* If the class exists, return a new instance of it. */
        if (class_exists($class)) {
            $obj = new $class;
            return $obj;
        }

        return null;
    }
	
}

?>