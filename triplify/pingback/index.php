<?php
/**
 * This is the main file of the Semantic Pingback server implementation.
 *
 * @author     Philipp Frischmuth <pfrischmuth@googlemail.com>
 * @copyright  Copyright (c) 2010, {@link http://aksw.org AKSW}
 * @license    http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
 * @version    $Id: $
 */

require_once('../config.inc.php');


if (!isset($triplify['pingback']['enabled']) || ((boolean)$triplify['pingback']['enabled'] === false)) {
    // Pingback is disabled... do not accept xml-rpc requests...
    exit;
}

define('XMLRPC_REQUEST', true);

// Some browser-embedded clients send cookies. We don't want them.
$_COOKIE = array();

// A bug in PHP < 5.2.2 makes $HTTP_RAW_POST_DATA not set by default,
// but we can do it ourself.
if ( !isset( $HTTP_RAW_POST_DATA ) ) {
	$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
}

// fix for mozBlog and other cases where '<?xml' isn't on the very first line
if ( isset($HTTP_RAW_POST_DATA) )
	$HTTP_RAW_POST_DATA = trim($HTTP_RAW_POST_DATA);

// Serve the XML-RPC request.
require_once('classes/SPServer.inc.php');
$server = new SPServer($triplify);
$server->serveRequest();
