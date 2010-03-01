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

ignore_user_abort(true);
set_time_limit(180);

include_once('owa_env.php');
require_once(OWA_BASE_DIR.'/owa_php.php');

/**
 * Remote Event Queue Front Controller
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */


$owa = new owa_php();
$owa->setSetting('base', 'is_remote_event_queue', true);
$owa->e->debug($_POST);
$req = owa_coreAPI::getRequest();
$ev = $owa->makeEvent();
$event = unserialize(base64_decode(owa_coreAPI::getRequestParam('event')));
$owa->e->debug(print_r($event,true));
$dispatch = owa_coreAPI::getEventDispatch();
$dispatch->asyncNotify($event);

?>