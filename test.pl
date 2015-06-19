

$text =<<HEREDOC
<?xml version="1.0"?><?xml-stylesheet href="chrome://global/skin/" type="text/css"?><window id="yourwindow" xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">
<box><image src="img/load.gif" style="width: 80px"/></box>
</window>
HEREDOC
;

$text =~ s/</[/g;
$text =~ s/>/]/g;

print $text;