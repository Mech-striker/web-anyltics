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
	require_once(OWA_DIR.'owa_observer.php');
}	
require_once(OWA_DIR.'owa_lib.php');

/**
 * Commerce Transaction Event handlers
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006-2011 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.4.0
 */

class owa_commerceTransactionHandlers extends owa_observer {
    	
    /**
     * Notify handler method
     *
     * @param 	object $event
     * @access 	public
     */
    function notify($event) {
		
    	$ct = owa_coreAPI::entityFactory('base.commerce_transaction_fact');
		$pk = $ct->generateId( $event->get( 'ct_order_id' ) );
		$ct->getByPk( 'id', $pk );
		$id = $ct->get('id'); 
		
		if (!$id) {
		
			// set missing properties from associated session
			// this is needed becuase the ecommerce transaction PHP API allows for 
			// events to be fired into OWA from a non-web request by passing
			// the session_id.
			if ( $event->get( 'session_id' ) && ! $event->get( 'lookup_state_from_session' ) ) {
				$s = owa_coreAPI::entityFactory( 'base.session' );
				$s->load( $event->get( 'session_id' ) );
				
				if ( $s->get( 'id' ) ) {
					$ct->setProperties( $s->_getProperties() );
				}
			}
			
			$ct->setProperties($event->getProperties());
			
			$ct->set( 'id', $pk ); 
			
			// Generate Location Id. Location data is comming from user input
			$location_id_string  = trim( strtolower( $event->get( 'country' ) ) );
			$location_id_string .= trim( strtolower ( $event->get( 'state' ) ) );
			$location_id_string .= trim( strtolower( $event->get( 'city' ) ) ); 
			$ct->set( 'location_id', $ct->generateId( $location_id_string ) );
			// set entity properties
			$ct->set( 'order_id', trim( $event->get( 'ct_order_id' ) ) );
			$ct->set( 'order_source', trim( strtolower( $event->get( 'ct_order_source' ) ) ) );
			$ct->set( 'gateway', trim( strtolower( $event->get( 'ct_gateway' ) ) ) );
			$ct->set( 'total_revenue', owa_lib::prepareCurrencyValue( round( $event->get( 'ct_total' ), 2 ) ) );
			$ct->set( 'tax_revenue', owa_lib::prepareCurrencyValue( round( $event->get( 'ct_tax' ), 2 ) ) );
			$ct->set( 'shipping_revenue', owa_lib::prepareCurrencyValue( round( $event->get( 'ct_shipping' ), 2 ) ) );
			$ct->set( 'days_since_first_session', $event->get('days_since_first_session') );
			$ct->set( 'num_prior_sessions', $event->get('num_prior_sessions') );
			
			$ret = $ct->create();
			
			// persist line items
			if ($ret) {
				$items = $event->get('ct_line_items');
				if ( $items ) {
					
					foreach ($items as $item) {
						$ret = $this->persistLineItem($item, $event);
					}
				}
			}
			
			if ($ret) {
				$dispatch = owa_coreAPI::getEventDispatch();
				$sce = $dispatch->makeEvent( 'ecommerce.transaction_persisted' );
				$sce->setProperties( $event->getProperties() );
				$dispatch->asyncNotify( $sce );
			}
			
			if ( $ret ) {
				return OWA_EHS_EVENT_HANDLED;
			} else {
				return OWA_EHS_EVENT_FAILED;
			}
			
		} else {
		
			owa_coreAPI::debug('Not Persisting. Transaction already exists');
			return OWA_EHS_EVENT_HANDLED;
		}	
    }
    
    function persistLineItem($item, $parent) {
    	
    	$ct = owa_coreAPI::entityFactory('base.commerce_line_item_fact');
    	$guid = $item['li_order_id'] . $item['li_sku'];
		$pk = $ct->generateId( $guid );
		$ct->getByPk( 'id', $pk );
		$id = $ct->get( 'id' ); 
		
		if ( ! $id ) {
			
			$ct->setProperties( $parent->getProperties() );
			
			$ct->set( 'id', $pk ); 
			
			// Generate Location Id. Location data is comming from user input
			$ct->set( 'order_id', trim( $item['li_order_id'] ) );
			$ct->set( 'sku', trim( $item['li_sku'] ) );
			$ct->set( 'product_name', trim( strtolower( $item['li_product_name'] ) ) );
			$ct->set( 'category', $item['li_category'] );
			$ct->set( 'unit_price', owa_lib::prepareCurrencyValue( round($item['li_unit_price'], 2 ) ) );
			$ct->set( 'quantity', round( $item['li_quantity'] ) );
			$revenue = round( $item['li_quantity'] * $item['li_unit_price'] , 2 );
			$ct->set( 'item_revenue', owa_lib::prepareCurrencyValue( $revenue ) );
			$ret = $ct->create();
			
			if ($ret) {
				return true;
			} else {
				return false;
			}
			
		} else {
		
			owa_coreAPI::debug('Not Persisting. line item already exists');
			return false;
		}	
    }
}

?>