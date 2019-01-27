<?php

	include_once( PLOG_CLASS_PATH."class/locale/locale.class.php" );
	
	/**
	 * \ingroup Locale
	 *
	 * Allows plugins to provide their custom locale files, in addition to the blog-wide
	 * ones located under the locale/ folder.
	 * The only thing this class does is extend the _loadLocaleFile private method, so that
	 * we can load the file from a different folder. The constructor also takes one additional
	 * parameter that represents the plugin identifier, so that we know where the plugin files
	 * are stored.
	 *
	 * @see Locales
	 * @see Locale
	 */
	class PluginLocale extends Locale
	{
	
		var $_pluginId;
	
		/**
		 * New constructor. Works exactly as Locale::Locale() but takes an additional parameter
		 * the plugin identifier
		 *
		 * @param pluginId The plugin identifier
		 * @param code The locale code
		 * @see Locale
		 */
		function PluginLocale( $pluginId, $code )
		{
			// save the plugin identifier
			$this->_pluginId = $pluginId;
			
			// and initialize the parent class
			$this->Locale( $code );
		}
	
        /**
		 * Loads a plugin locale file from disk
		 *
         * @private
         */
		function _loadLocaleFile()
		{
			$fileName = PLOG_CLASS_PATH."plugins/".$this->_pluginId."/locale/locale_".$this->_code.".php";

			include( $fileName );

			$this->_messages = $messages;
		}		
	}
?>