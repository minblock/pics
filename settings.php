<?PHP
/*
#
#	$Id: SETTINGS.PHP
#	Version 2.0.0
#
*/

/* DB VARIABLES*/
$dbhost 	= "localhost";
$dbuser 	= "picme";
$dbpass 	= "";
$dbname 	= "picme";
$adminpass 	= "";

/* INFORMATION */
$ip 		= GetHostByName($REMOTE_ADDR); 
$today 	= date("Y-m-d");

/* SITE COLORS */
$boxcolor = "cccccc";
$menucolor = "cc99cc";
$pagecolor = "FFFFFF";
$backcolor = "111111";

//  GRAB THE DATABASE 

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass") or die ("Cannot connect to your database at this time");
$linkdb = mysql_select_db("$dbname") or die ("Sorry, PicMe was unable to establish a connection with the database. 
Please check that you have set the variables correctly<br><br><i>Unable to establish database connection
</i><br><br><b>Possible Causes<br><ul><li>Invalid Database Name/Username/Password Specified</li><li>
Database is not running</li></ul>");

/* PATHS */
$mainurl 		= "/";
$thumbType 		= "1";  		// 0 = GD, 1 = ImagageMagic
$thumbdir 		= "thumb";  	// short path
$imagedir 		= "uploads";  	// short path
$fullpathuploads 	= "/srv/users/serverpilot/apps/bitpix/public/uploads";
$fullpaththumb 	= "/srv/users/serverpilot/apps/bitpix/public/thumb";
$imgloc = "../banners/"; 	// path to where the banners are

/*ADMINISTRATION OPTIONS*/
$notifyAdmin 	= "1";		// 0 = off, 1 = on when new photo submitted
$preapprove 	= "1";      	// 0 = admin approves first  = everything approved
$showvotes 		= "1";  		// 0 = off, 1 = on vote tracking on and off.
$maxupSize 		= "900000"; 	// maximum upload size in bytes
$new_w 		= "320";		// thumbnail width after upload.
$maxupWidth 	= "800";  		// maximum width of pic to accept
$maxupHeight 	= "600";  		// maximum height of pic to accept
$maxvotes		= "30"; 		// if showvotes off, ignore.
$maxcomments 	= "3"; 		// max comments to show before displaying more
$maxlinks 		= "10";   		// max links to show before displaying more
$maxfaq 		= "4";		// maximum faq questions to display before the more.
$displaybanners	= "1";		// 0 = off, 1 = on and top right of page

/* PAGE VARIABLES */  // FEEL FREE TO CHANGE THESE AS YOU WISH
$infoText = "Simply rate each picture by clicking on the themometer and we'll advance you to the next available entry. If you would like to submit your own photo, click on the signup and we'll do the rest.  <p>If you are interested in this script please contact me through <b><A HREF=http://dh.provgn.com/members/stevecat.1/>Minecraft</A></b>.<br>I hope that you enjoy.";

$FeedbackinfoText = "We are always interested in hearing what you have to say.  If you feel the need to let us know how we are doing, drop us a note by filling in the form below.  Good or bad, lets hear it... You may also use this if you would like to report someone or something that you find offencive.";

$InboxinfoText = "";  // Not used yet

$CommentinfoText = "";  // Not used yet

$PrivateinfoText = "";  // Not used yet

$JoininfoText = "";  // Not used yet

$AboutinfoText = "We are a small company, that Freelances a lot of work as well as developing our own scripts.  We own many domains, and are very active in the Internet world.  If there is anything that we can do for you please let us know what it is you want...";

$FaqinfoText = "Here is a collection of a few of the more common questions and answers, if you think that I <br>have missed any, please feel free to let me know and I will make the changes.";

$UploadinfoText = "";  // Not used yet

$aftervote = "";  // Not used yet

$LoginText = "If you are a member, please enter your user ID and Password below.<br>  If you are not a member, you may have been directed here because you have tried<br> to access an area that is limited to members only.<br>  In this case, go register, it is FREE and FUN for all...";

$novotesleft = "<br><br>Sorry, you have reached your max votes for today";

$accessdenied = "<br><br>Get out!<br><br><a href=login.php>Login</a>";

/* SITE VARIABLES*/
$slogan 	= "Picscoin Picture Rating Script";
$mailadmin 	= "stevecat@provgn.com";
$sitetitle 	= "Pics";
$sitename 	= "Pics";
$siteurl 	= "http://pix.bitwall.ca/";
$mailname 	= "Pics Mail";


// You MAY NOT remove this!
$version = "V 2.1.0";
$footer = "<center><b>Powered by:</b> <a href=http://dh.provgn.com/members/stevecat.1/ target=_blank><font color=white><u>stevecat</u></a></font> $version</center>";

?>
