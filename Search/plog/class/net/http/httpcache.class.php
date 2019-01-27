<?php

	include_once( PLOG_CLASS_PATH."class/net/http/httpvars.class.php" );


//In RSS/ATOM feedMode, contains the date of the clients last update.
$clientCacheDate=0; //Global variable because PHP4 does not allow conditional arguments by reference

/**
 *
 * \ingroup Net_HTTP
 *
 *
 * Enable support for HTTP/1.x conditional requests in PHP.
 * Goal: Optimisation
 * - If the client sends a HEAD request, avoid transferring data and return the correct headers.
 * - If the client already has the same version in its cache, avoid transferring data again (304 Not Modified).
 * - Possibility to control cache for client and proxies (public or private policy, life time).
 * - When $feedMode is set to true, in the case of a RSS/ATOM feed,
 *  it puts a timestamp in the global variable $clientCacheDate to allow the sending of only the articles newer than the client's cache.
 * - When $compression is set to true, compress the data before sending it to the client and persitent connections are allowed. (Beta1 version)
 *
 *Interface:
 * - function httpConditional($UnixTimeStamp,$cacheSeconds=0,$cachePrivacy=0,$feedMode=false,$compression=false)
 * [Required] $UnixTimeStamp: Date of the last modification of the data to send to the client (Unix Timestamp format).
 * [Implied] $cacheSeconds=0: Lifetime in seconds of the document. If $cacheSeconds>0, the document will be cashed and not revalidated against the server for this delay.
 * [Implied] $cachePrivacy=0: 0=private, 1=normal (public), 2=forced public. When public, it allows a cashed document ($cacheSeconds>0) to be shared by several users.
 * [Implied] $feedMode=false: Special RSS/ATOM feeds. When true, it sets $cachePrivacy to 0 (private), does not use the modification time of the script itself, and puts the date of the client's cache (or a old date from 1980) in the global variable $clientCacheDate.
 * [implied] $compression=false: Enable the compression and allows persistant connections.
 * Return: True if the connection can be closed (e.g.: the client has already the lastest version), false if the new content has to be send to the client.
 *
 * Typical use:
 *
 * <pre>
 * <?php
 * require_once('http-conditional.php');
 * //Date of the last modification of the content (Unix Timestamp format).
 * //Examples: query the database, or last modification of this script.
 * $dateLastModification=...;
 * if (httpConditional($dateLastModification))
 * {
 *  ... //Close database connections, and other cleaning.
 *  exit(); //No need to send anything
 * }
 * //Do not send any text to the client before this line.
 * ... //Rest of the script, just as you would do normally.
 * ?>
 * </pre>
 *
 * Version 1.3, 2004-08-05, http://alexandre.alapetite.net/doc-alex/php-http-304/
 *
 * ------------------------------------------------------------------
 * Written by Alexandre Alapetite, http://alexandre.alapetite.net/cv/
 *
 * Copyright 2004, Licence: Creative Commons "Attribution-ShareAlike 2.0" BY-SA,
 * http://creativecommons.org/licenses/by-sa/2.0/
 * http://alexandre.alapetite.net/divers/apropos/#by-sa
 * - Attribution. You must give the original author credit
 * - Share Alike. If you alter, transform, or build upon this work,
 *  you may distribute the resulting work only under a license identical to this one
 * - Any of these conditions can be waived if you get permission from Alexandre Alapetite
 * - Please send to Alexandre Alapetite the modifications you make,
 *  in order to improve this file for the benefit of everybody
 *
 * If you want to distribute this code, please do it as a link to:
 * http://alexandre.alapetite.net/doc-alex/php-http-304/
 */
class HttpCache {
	function httpConditional($UnixTimeStamp,$cacheSeconds=0,$cachePrivacy=0,$feedMode=false,$compression=false)
	{//Credits: http://alexandre.alapetite.net/doc-alex/php-http-304/
	 //RFC2616 HTTP/1.1: http://www.w3.org/Protocols/rfc2616/rfc2616.html
	 //RFC1945 HTTP/1.0: http://www.w3.org/Protocols/rfc1945/rfc1945.txt
	 
			$SERVER = HttpVars::getServer();
			
			if( $cacheSeconds == 0 )
				$cacheSeconds = 788400000;
			
			if (headers_sent()) return false;
			
			if (isset($SERVER['SCRIPT_FILENAME'])) $scriptName=$SERVER['SCRIPT_FILENAME'];
			elseif (isset($SERVER['PATH_TRANSLATED'])) $scriptName=$SERVER['PATH_TRANSLATED'];
			else return false;
			
			if ((!$feedMode)&&(($modifScript=filemtime($scriptName))>$UnixTimeStamp))
				$UnixTimeStamp=$modifScript;
			$UnixTimeStamp=min($UnixTimeStamp,time());
			$is304=true;
			$is412=false;
			$nbCond=0;
			
			//rfc2616-sec3.html#sec3.3.1
			$dateLastModif=gmdate('D, d M Y H:i:s \G\M\T',$UnixTimeStamp);
			$dateCacheClient='Tue, 10 Jan 1980 20:30:40 GMT';
			
			//rfc2616-sec14.html#sec14.19 //='"0123456789abcdef0123456789abcdef"'
			if (isset($SERVER['QUERY_STRING'])) $myQuery='?'.$SERVER['QUERY_STRING'];
			else $myQuery='';
			$etagServer='"'.md5($scriptName.$myQuery.'#'.$dateLastModif).'"';
			
			if ((!$is412)&&isset($SERVER['HTTP_IF_MATCH']))
			{//rfc2616-sec14.html#sec14.24
				$etagsClient=stripslashes($SERVER['HTTP_IF_MATCH']);
				$is412=(($etagClient!='*')&&(strpos($etagsClient,$etagServer)===false));
			}
			if ($is304&&isset($SERVER['HTTP_IF_MODIFIED_SINCE']))
			{//rfc2616-sec14.html#sec14.25 //rfc1945.txt
				$nbCond++;
				$dateCacheClient=$SERVER['HTTP_IF_MODIFIED_SINCE'];
				$is304=($dateCacheClient==$dateLastModif);
			}
			if ($is304&&isset($SERVER['HTTP_IF_NONE_MATCH']))
			{//rfc2616-sec14.html#sec14.26
				$nbCond++;
				$etagClient=stripslashes($SERVER['HTTP_IF_NONE_MATCH']);
				$is304=(($etagClient==$etagServer)||($etagClient=='*'));
			}
			if ((!$is412)&&isset($SERVER['HTTP_IF_UNMODIFIED_SINCE']))
			{//rfc2616-sec14.html#sec14.28
				$dateCacheClient=$SERVER['HTTP_IF_UNMODIFIED_SINCE'];
				$is412=($dateCacheClient!=$dateLastModif);
			}
			if ($feedMode)
			{//Special RSS/ATOM
				global $clientCacheDate;
				$clientCacheDate=strtotime($dateCacheClient);
				$cachePrivacy=0;
			}
			
			if ($is412)
			{//rfc2616-sec10.html#sec10.4.13
				header('HTTP/1.1 412 Precondition Failed');
				header('Cache-Control: private, max-age=0, must-revalidate');
				header('Content-Type: text/plain');
				echo "HTTP/1.1 Error 412 Precondition Failed: Precondition request failed positive evaluation\n";
				return true;
			}
			elseif ($is304&&($nbCond>0))
			{//rfc2616-sec10.html#sec10.3.5
				header('HTTP/1.0 304 Not Modified');
				header('Etag: '.$etagServer);
				if ($feedMode) header('Connection: close'); //Comment this line under IIS
				
				if ($cacheSeconds==0)
				{
					$cache='private, must-revalidate, ';
					//$cacheSeconds=-1500000; //HTTP/1.0
				}
				elseif ($cachePrivacy==0) $cache='private, ';
				elseif ($cachePrivacy==2) $cache='public, ';
				else $cache='';
				$cache.='max-age='.floor($cacheSeconds);
				header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T',time()+$cacheSeconds)); //HTTP/1.0 //rfc2616-sec14.html#sec14.21a
				header('Cache-Control: '.$cache); //rfc2616-sec14.html#sec14.9
				header('Pragma: public');
				
				return true;
			}
			else
			{//rfc2616-sec10.html#sec10.2.1
			 //rfc2616-sec14.html#sec14.3
				if ($compression&&isset($SERVER['HTTP_ACCEPT_ENCODING'])&&
					extension_loaded('zlib')&&(!ini_get('zlib.output_compression')))
					ob_start('_httpConditionalCallBack');
				//header('HTTP/1.0 200 OK');
				if ($cacheSeconds==0)
				{
					$cache='private, must-revalidate, ';
					//$cacheSeconds=-1500000; //HTTP/1.0
				}
				elseif ($cachePrivacy==0) $cache='private, ';
				elseif ($cachePrivacy==2) $cache='public, ';
				else $cache='';
				$cache.='max-age='.floor($cacheSeconds);
				header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T',time()+$cacheSeconds)); //HTTP/1.0 //rfc2616-sec14.html#sec14.21a
				header('Cache-Control: '.$cache); //rfc2616-sec14.html#sec14.9
				
				header('Last-Modified: '.$dateLastModif);
				header('Etag: '.$etagServer);
				if ($feedMode) header('Connection: close'); //rfc2616-sec14.html#sec14.10 //Comment this line under IIS
				return $SERVER['REQUEST_METHOD']=='HEAD'; //rfc2616-sec9.html#sec9.4
			}
	}

}

function _httpConditionalCallBack($buffer,$mode=5)
{//Private function automatically called at the end of the script when compression is enabled
 //rfc2616-sec14.html#sec14.11
 //You can adjust the level of compression with zlib.output_compression_level in php.ini
	$buffer=ob_gzhandler($buffer,$mode); //Will check HTTP_ACCEPT_ENCODING and put correct headers
	header('Content-Length: '.strlen($buffer));
	return $buffer;
}

?>