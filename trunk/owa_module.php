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
 * Abstract Module Class
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */

class owa_module extends owa_base {
	
	/**
	 * Name of module
	 *
	 * @var string
	 */
	var $name;
	
	/**
	 * Description of Module
	 *
	 * @var string
	 */
	var $description;
	
	/**
	 * Version of Module
	 *
	 * @var string
	 */
	var $version;
	
	/**
	 * Schema Version of Module
	 *
	 * @var string
	 */
	var $schema_version = 1100;
	
	/**
	 * Name of author of module
	 *
	 * @var string
	 */
	var $author;
	
	/**
	 * URL for author of module
	 *
	 * @var unknown_type
	 */
	var $author_url;
	
	/**
	 * Wiki Page title. Used to generate link to OWA wiki for this module.
	 * 
	 * Must be unique or else it will could clobber another wiki page.
	 *
	 * @var string
	 */
	var $wiki_title;
	
	/**
	 * name used in display situations
	 *
	 * @var unknown_type
	 */
	var $display_name;
	
	/**
	 * Array of event names that this module has handlers for
	 *
	 * @var array
	 */
	var $subscribed_events;
	
	/**
	 * Array of link information for admin panels that this module implements.
	 *
	 * @var array
	 */
	var $admin_panels;
	
	/**
	 * Array of navigation links that this module implements
	 *
	 * @var unknown_type
	 */
	var $nav_links;
	
	/**
	 * Array of metric names that this module implements
	 *
	 * @var unknown_type
	 */
	var $metrics;
	
	/**
	 * Array of graphs that are implemented by this module
	 *
	 * @var array
	 */
	var $graphs;
	
	/**
	 * The Module Group that the module belongs to. 
	 * 
	 * This is used often to group a module's features or functions together in the UI
	 * 
	 * @var string 
	 */
	var $group;
	
	/**
	 * Array of Entities that are implmented by the module
	 * 
	 * @var array 
	 */
	var $entities = array();
	
	/**
	 * Required Schema Version
	 * 
	 * @var array 
	 */
	var $required_schema_version;
	
	/**
	 * Available Updates
	 * 
	 * @var array 
	 */
	var $updates = array();
	
	/**
	 * Constructor
	 *
	 * @return owa_module
	 */
	function owa_module() {
		
		$this->owa_base();
		
		// register event handlers unless OWA is operating in async handling mode
		if ($this->config['async_db'] == false):
			$this->_registerEventHandlers();
		endif;
		
		$this->_registerEntities();
		
		return;
		
	}
	
	/**
	 * Returns array of admin Links for this module to be used in navigation
	 * 
	 * @access public
	 * @return array
	 */
	function getAdminPanels() {
		
		return $this->admin_panels;
	}
	
	/**
	 * Returns array of report links for this module that will be 
	 * used in report navigation
	 *
	 * @access public
	 * @return array
	 */
	function getNavigationLinks() {
		
		return $this->nav_links;
	}
	
	/**
	 * Abstract method for registering event handlers
	 * 
	 * @access public
	 * @return array
	 */
	function _registerEventHandlers() {
		
		return;
	}
	
	/**
	 * Abstract method for registering administration panels
	 * 
	 * @access public
	 * @return array
	 */
	function _registerAdminPanels() {
		
		return;
	}
	
	/**
	 * Attaches an event handler to the event queue
	 *
	 * @param array $event_name
	 * @param string $handler_name
	 * @return boolean
	 */
	function _addHandler($event_name, $handler_name) {
		
		$handler_dir = OWA_BASE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR.'handlers';
		
		$class = 'owa_'.$handler_name;
		
		// Require class file if class does not already exist
		if(!class_exists('owa_'.$handler_name)):	
			require_once($handler_dir.DIRECTORY_SEPARATOR.$handler_name.'.php');
		endif;
		
		$handler = &owa_lib::factory($handler_dir,'owa_', $handler_name);
		$handler->_priority = PEAR_LOG_INFO;
		
		$eq = &eventQueue::get_instance();
			
		// Register event names for this handler
		if(is_array($event_name)):
			
			foreach ($event_name as $k => $name) {	
				$handler->_event_type[] = $name;	
			}
			
		else:
			$handler->_event_type[] = $event_name;
			
		endif;
			
		$eq->attach($handler);
		
		return ;
		
	}
	
	/**
	 * Registers an admin panel with this module 
	 *
	 */
	function addAdminPanel($panel) {
		
		$this->admin_panels[] = $panel;
		
		return true;
	}
	
	/**
	 * Registers Navigation Link with a particular View
	 * 
	 */
	/*
function addNavigationLink($link) {
		
		$this->nav_links[] = $link;
		
		return;
	}
*/
	
	/**
	 * Registers Group Link with a particular View
	 * 
	 */
	function addNavigationLink($group, $subgroup = '', $ref, $anchortext, $order = 0, $priviledge = 'viewer') {
		
		$link = array('ref' => $ref, 
					'anchortext' => $anchortext, 
					'order' => $order, 
					'priviledge' => $priviledge);
					
		if (!empty($subgroup)):
			$this->nav_links[$group][$subgroup]['subgroup'][] = $link;
		else:
			$this->nav_links[$group][$anchortext] = $link;			
		endif;

		return;
	}
	
	
	/**
	 * Registers Entity
	 * 
	 */
	function _addEntity($entity_name) {
		
		if (is_array($entity_name)):
			$this->entities = array_merge($this->entities, $entity_name);
		else:
			$this->entities[] = $entity_name;
		endif;
		
		return;
	}
	
	function getEntities() {
		
		return $this->entities;
	}
	
	/**
	 * Installation method
	 * 
	 * Creates database tables and sets schema version
	 * 
	 */
	function install() {
		
		$this->e->notice('Starting installation of module: '.$this->name);

		$errors = '';

		// Install schema
		if (!empty($this->entities)):
		
			foreach ($this->entities as $k => $v) {
			
				$entity = owa_coreAPI::entityFactory($this->name.'.'.$v);
				//$this->e->debug("about to  execute createtable");
				$status = $entity->createTable();
				
				if ($status != true):
					$this->e->notice("Entity Installation Failed.");
					$errors = true;
					//return false;
				endif;
				
			}
		
		endif;
		
		// activate module and persist configuration changes 
		if ($errors != true):
			
			// run post install hook
			$ret = $this->postInstall();
			
			if ($ret == true):
				// save schema version to configuration
				$this->c->setSetting($this->name, 'schema_version', $this->schema_version);
				//activate the module and save the configuration
				$this->activate();
				$this->e->notice("Installation complete.");
				return true;
			else:
				$this->e->notice("Post install proceadure failed.");
				return true;
			endif;
		else:
			$this->e->notice("Installation failed.");
			return false;
		endif;

	}
	
	/**
	 * Post installation hook
	 *
	 */
	function postInstall() {
	
		return false;
	}
		
	/**
	 * Checks for and applies schema upgrades for the module
	 *
	 */
	function update() {
		
		// list files in a directory
		$files = owa_lib::listDir(OWA_DIR.'modules'.DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR.'updates', false);
		//print_r($files);
		
		$current_schema_version = $this->c->get($this->name, 'schema_version');
		
		// extract sequence
		foreach ($files as $k => $v) {
			// the use of %d casts the sequence number as an int which is critical for maintaining the 
			// order of the keys in the array that we are going ot create that holds the update objs
			//$n = sscanf($v['name'], '%d_%s', $seq, $classname);
			$seq = substr($v['name'], 0, -4);
			
			settype($seq, "integer");
			
			if ($seq > $current_schema_version):
			
				if ($seq <= $this->required_schema_version):
					$this->updates[$seq] = owa_coreAPI::updateFactory($this->name, substr($v['name'], 0, -4));
					// set schema version from sequence number in file name. This ensures that only one update
					// class can ever be in use for a particular schema version
					$this->updates[$seq]->schema_version = $seq;
				endif;
			endif;	
			
		}
		
		// sort the array
		ksort($this->updates, SORT_NUMERIC);
		
		//print_r(array_keys($this->updates));
		
		foreach ($this->updates as $k => $obj) {
			
			$this->e->notice(sprintf("Applying Update %d (%s)", $k, get_class($obj)));
			
			$ret = $obj->apply();
			
			if ($ret == true):
				$this->e->notice("Update Suceeded");
			else:
				$this->e->notice("Update Failed");
				return false;
			endif;
		}
		
		return true;
	}
	
	/**
	 * Deactivates and removes schema for the module
	 * 
	 */
	function uninstall() {
		
		return;
	}
	
	/**
	 * Places the Module into the active module list in the global configuration
	 * 
	 */
	function activate() {
		
		if ($this->name != 'base'):
		
			$this->c->setSetting($this->name, 'is_active', true);
			$this->c->save();
			
		endif;
		
		return;
	}
	
	/**
	 * Deactivates the module by removing it from 
	 * the active module list in the global configuration
	 * 
	 */
	function deactivate() {
		
		if ($this->name != 'base'):
			
			$this->c->setSetting($this->name, 'is_active', false);
			$this->c->save();
			
		endif;
		
		return;
	}
	
	/**
	 * Registers a set of entities for the module
	 * 
	 */
	function _registerEntities() {
		
		return false;
	}
	
	/**
	 * Checks to se if the schema is up to date
	 *
	 */
	function isSchemaCurrent() {
		
		$current_schema = $this->c->get($this->name, 'schema_version');
		
		if ($current_schema >= $this->required_schema_version):
			return true;
		else:
			return false;
		endif;
	}
	
	/**
	 * Registers updates
	 *
	 */
	function _registerUpdates() {
		
		
		
		return;
	
	}
	
	/**
	 * Adds an update class into the update array.
	 * This should be used to within the _registerUpdates method or else
	 * it will not get called.
	 *
	 */
	function _addUpdate($sequence, $class) {
		
		$this->updates[$sequence] = $class;
		
		return true;
	}
	
}

?>