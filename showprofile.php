<?PHP
/*
#
#	$Id: SHOWPROFILE.PHP
#	Version 2.1.0
#
*/
include "header.php";

		$sql = "SELECT * FROM users WHERE userid='$name'";
		$sql_result = mysql_query($sql);
	while ($row = mysql_fetch_array($sql_result)) { 
	 $proid = $row["id"];
	 $proname = $row["userid"];
	 $proemail = $row["email"];
	 $prosex = $row["sex"];
	 $prohobbies = $row["hobbies"];
	 $promusic = $row["music"];
	 $prochat = $row["chat"];
	 $proupdated = $row["updated"];
	 $provis = $row["emailvisible"];
	 $yearborn = $row["year"];
	 $country = $row["country"];
	 $state = $row["state"];
	 $quote = $row["quote"];
}
if ($country == "") {
$country = "na";
}

		$sql = "SELECT * FROM picuploads WHERE approved='1' and userid='$name'";
		$sql_result = mysql_query($sql);
	while ($row = mysql_fetch_array($sql_result)) { 
	$picid = $row["id"];
	$photo = $row["name"];
	$points = $row["points"];
	$votes = $row["votes"];
	}

	if ($points == "") {
	$rate = 0;
	} else {
$rate = round($points / $votes,3);
}
		 if($provis == "1") {
			 $canmail = "<a href=mailto:$proemail>$proemail</a>";	
		 } else {
			 $canmail = "Email Address Hidden";	
		 }
$thisyear = date("Y");
if ($yearborn !="") {
$howold = $thisyear-$yearborn;
} else {
$howold = "Not Shown";
}
		 
echo "<br><br>";
?>

<table border="0" cellspacing="0" width="100%" cellpadding="0">
	<tr>
		<td width="100%" bgcolor="#111111">
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<td width="100%" bgcolor="#cc99cc" class="title"><b>.: <? echo $proname; ?> :.</b>
		</td>
	</tr>
	<tr>
		<td width="100%" bgcolor="#cccccc">
		<?PHP
		 if($photo == "") {
			 $photo = "nophoto.gif";	
		 } else {
			 // DO NOTHING	
		 }
	?>
	<CENTER><IMG src=uploads/<? echo $photo ?> width=200>
	<br><IMG src="../images/email.gif">&nbsp;&nbsp;<A href="private.php?touser= <? echo $proname; ?>"><B>Send private message.</B></A>
<br><img src="images/post.gif" alt="Post a comment on this picture" border="0">&nbsp;&nbsp;<a href="comment.php?pid=<? echo $picid ?>"><b>Submit Comment on Photo</b></a>
	</CENTER>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>

<table border="0" cellspacing="0" width="100%" cellpadding="0">
	<tr>
		<td width="100%" bgcolor="#111111">
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<td bgcolor="#cc99cc" class="title"><b>.: Profile :.</b>
		</td>
	</tr>
	<tr>
		<td bgcolor="#cccccc">
            <TABLE border=0 Width=100%>
                <TR>
                <TD Width=33% valign="top">
		<B>Name:</B> <? echo $proname; ?>
          	<br><B>Sex:</B> <? echo $prosex; ?>
          	<br><B>Age:</B> <? echo $howold; ?>
          	<br><B>Hobbies:</B> <? echo $prohobbies; ?>
			</TD>
                	<TD Width=33% valign="top">
		 <B>Music:</B> <? echo $music; ?>
          	 <br><B>Chat:</B> <? echo $prochat; ?>
		 <br><b>State/Prov:</b>	<? echo $state; ?>
		 <br><b>Country:</b> <? echo "<img src=flags/$country.gif>"; ?>
             	</TD>
			<TD Width=33% valign="top">
		    <B>Rating:</B> <FONT Color="red"><? echo $rate; ?></FONT></B>
		    <br><B>Times Rated:</b> <? echo $votes ?>
		    <br><B>Points:</b> <? echo $points ?>
		    </td>
		   </TR>
		</TABLE>
		<B>Email:</B> <? echo $canmail ?>
          	<br><B>Quote:</B> <I><? echo $quote ?></I>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>

<table border="0" cellspacing="0" width="100%" cellpadding="0">
	<tr>
		<td width="100%" bgcolor="#111111">
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<td width="100%" bgcolor="#cc99cc" class="title"><B>.: COMMENTS :.</B>
		</td>
	</tr>
	<tr>
		<td width="100%" bgcolor="#cccccc">
			<?PHP
			echo getUcomments($picid);
			?>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>
<?PHP
include "footer.php";
?>