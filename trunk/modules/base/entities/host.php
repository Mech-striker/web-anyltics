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
 * Host Entity
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_host extends owa_entity {
	
	var $id = array('data_type' => OWA_DTD_BIGINT, 'is_primary_key' => true); // BIGINT,
	var $ip_address = array('data_type' => OWA_DTD_VARCHAR255); // VARCHAR(255),
	var $host = array('data_type' => OWA_DTD_VARCHAR255); // VARCHAR(255),
	var $full_host = array('data_type' => OWA_DTD_VARCHAR255); // VARCHAR(255),
	var $city = array('data_type' => OWA_DTD_VARCHAR255); // varchar(255),
	var $country = array('data_type' => OWA_DTD_VARCHAR255); // varchar(255),
	var $latitude = array('data_type' => OWA_DTD_VARCHAR255); // varchar(255),
	var $longitude = array('data_type' => OWA_DTD_VARCHAR255); // varchar(255),
	
	function owa_host() {
		
		$this->owa_entity();
		
		return;
			
	}
	
	
	
}



?>