<?php

	include_once( PLOG_CLASS_PATH."class/object/object.class.php" );
    include_once( PLOG_CLASS_PATH."class/file/fileupload.class.php" );

	define( "FILE_UPLOADS_NOT_ENABLED", -200 );

	/**
	 * \ingroup File
	 *
     * Handles file uploads in pLog.
	 * @see FileUpload
     */
	class FileUploads extends Object 
	{

    	var $_files;
        var $_blogInfo;

    	/**
         * Creates a new object to handle file uploads. $files is either the
         * contents of the $_FILES variable (if using PHP >= 4.1.0) or
         * $HTTP_POST_FILES if earlier version.
         *
         * @param files The contents of the array generated by php after a file
         * has been uploaded.
         */
    	function FileUploads( $files )
        {
        	$this->Object();

            $this->_files = $files;
        }
		
		/**
		 * processes only one FileUpload object instead of the whole bunch
		 *
		 * @param uplaod A FileUpload object
		 * @param destinationFolder the destination folder
		 * @return true if successful or false otherwise
		 */
		function processFile( $upload, $destinationFolder ) 
		{
        	// first, check if the upload feature is available
            $config =& Config::getConfig();
            if( !$config->getValue( "uploads_enabled" )) {
            	return FILE_UPLOADS_NOT_ENABLED;
            }
			
            if( $destinationFolder[strlen($destinationFolder-1)] != "/" )
            	$destinationFolder .= "/";
				
			//use basename of tmp_name istead of filename
			//because there is locale problem with filename
			//$fileName = $upload->getFileName();
			if( $this->my_move_uploaded_file( $upload->getTmpName(), 
			                                   $destinationFolder.basename($upload->getTmpName()))){//$fileName)) {
               	$upload->setFolder( $destinationFolder );
				$error = 0;
            }
            else {
				$error = 1;
			}				
			
			$upload->setError( $error );
			
			return $error;
		}

        /**
         * Goes through the array of files and processes them accordingly.
         * The result is an array of the appropiate Resource class that has been
         * created using the ResourceFactory class.
         *
         * @return An array of Upload objects that have already been moved to a safer
         * location.
         */
        function process( $destinationFolder )
        {
        	// first, check if the upload feature is available
            $config =& Config::getConfig();
            if( !$config->getValue( "uploads_enabled" )) {
            	return FILE_UPLOADS_NOT_ENABLED;
            }

        	// array used to store the files that have already been saved
        	$uploads = Array();

            if( $destinationFolder[strlen($destinationFolder-1)] != "/" )
            	$destinationFolder .= "/";

        	foreach( $this->_files as $file ) {
                $upload = new FileUpload( $file );
                $fileName = $upload->getFileName();
                if( $this->my_move_uploaded_file( $upload->getTmpName(), $destinationFolder.$fileName)) {
                	$upload->setFolder( $destinationFolder );
                    $upload->setError( 0 );
                }
                else {
                	$upload->setError( 1 );
                }
                array_push( $uploads, $upload );
            }

            return $uploads;
        }
        
        /**
         * Implements the a move_uploaded_file that handles multiple partitions.
         *
         * @return a boolean that indicates if the file was successfully moved.  This will 
         * return false if the file is not an uploaded file.
         */
         function my_move_uploaded_file( $filename, $destination )
         {
            // first check to make sure that this file is an uploaded file
            if ( !is_uploaded_file($filename) )
            {
                // This file was not an uploaded file, return false
                return FALSE;
            }
            
            return File::rename( $filename, $destination );
         }
    }
?>
