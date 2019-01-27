<?php

	include_once( PLOG_CLASS_PATH."class/summary/view/summarycachedview.class.php" );
    include_once( PLOG_CLASS_PATH."class/template/templateservice.class.php" );
	include_once( PLOG_CLASS_PATH."class/locale/locales.class.php" );
	include_once( PLOG_CLASS_PATH."class/config/config.class.php" );

    class SummaryRssView extends SummaryCachedView
	{

        var $_profile;

        function SummaryRssView( $profile, $data = Array())
        {
			if( $profile == "" ) {
				$config =& Config::getConfig();
				$profile = $config->getValue( "default_rss_profile", "rss090" );
			}
            $this->SummaryCachedView( $profile, $data );			
				
            $this->_profile = $profile;
			
			$this->setContentType( "text/xml" );
        }
		
        function generateTemplate()
        {
            $templateService = new TemplateService();
            $this->_template = $templateService->CachedTemplate( $this->_templateName, "summary/rss" );        
        }		

        function sendUncachedOutput()
        {
            //$templateService = new TemplateService();
            //$template = $templateService->Template( $this->_profile, "summary/rss" );

            $config =& Config::getConfig();
            $this->_locale =& Locales::getLocale( $config->getValue("default_locale" ));
            $this->_params->setValue( "version", new Version());
            $this->_params->setValue( "locale", $this->_locale );
            $this->_template->assign( $this->_params->getAsArray());
            print $this->_template->fetch( $this->_viewId );
        }
    }
?>