<?php

    if (!defined( "PLOG_CLASS_PATH" )) {
        define( "PLOG_CLASS_PATH", dirname(__FILE__)."/");
    }

    include_once( PLOG_CLASS_PATH."class/controller/controller.class.php" );
    include_once( PLOG_CLASS_PATH."class/net/http/session/sessioninfo.class.php" );
    include_once( PLOG_CLASS_PATH."class/net/http/httpvars.class.php" );
	include_once( PLOG_CLASS_PATH."class/plugin/pluginmanager.class.php" );

    // create our own action map
    $actionMap = Array( "Default" => "RssAction" );
    $controller = new Controller( $actionMap, "op" );
	
	$request =& HttpVars::getRequest();
	if( isset($request["summary"])) {
		$request["op"] = "rss";
		include_once( PLOG_CLASS_PATH."summary.php" );
		die();
	}

    //
    // if there is no session object, we better create one
    //
    $session = HttpVars::getSession();
    if( $session["SessionInfo"] == "" ) {
        $session["SessionInfo"] = new SessionInfo();
        HttpVars::setSession( $session );
    }
	
    // load the plugins, this needs to be done *before* we call the
    // Controller::process() method, as some of the plugins _might_
    // add new actions to the controller
    $pluginManager =& PluginManager::getPluginManager();
    $pluginManager->loadPlugins();	

    // and call the controller
    $controller->process( HttpVars::getRequest());
?>
