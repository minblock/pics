<? include("config.inc"); 
echo "
<html><head>
<style type=\"text/css\">


td {
background-color: transparent;
}
</style>
<title>$sitename</title></head>
<BODY BACKGROUND=\"$backgroundimage\" TEXT=\"$text\" LINK=\"$link\" ALINK=\"$alink\" VLINK=\"$vlink\">
";
include("$headerfile");
?>







<?
echo "
<table width=100% border=0 cellspacing=0 cellpadding=0>
<td>
<br><br><br><br>
</td>
</table>

<table width=100% border=0 cellspacing=0 cellpadding=0>
<td align=center width=100%><form action=\"isearch.php?q=\" method=\"get\">
<input type=\"text\" size=\"40\" name=\"q\" value=\"$q1\"title=\"Enter Search String\">
<input type=\"submit\" value=\"Search\">
<br>
<SELECT size=1 name='imagetype' value='$imagetype'>
<OPTION selected value=''>any filetype
<OPTION value='$jpg'>jpg
<OPTION value='$gif'>gif
<OPTION value='$png'>png
</SELECT>
<SELECT size=1 name='imgsz' value='$imgsz'>
<OPTION selected value=''>any size
<OPTION value='$icon'>small
<OPTION value='$medium'>medium
<OPTION value='$xxlarge'>large
</SELECT>
<SELECT size=1 name='imgc' value='$imgc'>
<OPTION selected value=''>any colors
<OPTION value='$mono'>black & white
<OPTION value='$gray'>grayscale
<OPTION value='$color'>full color
</SELECT><p>
</form>
</td></table>";
?>

<? include("$footerfile"); ?>
