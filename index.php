<h1>What should I play?</h1>
<h2>Enter your Steam custom URL for a suggestion</h2>

<form method ="GET" action ="index.php">
    <input type="text" name="SteamVanityUrlInput" value ="
	<?php 
		if (isset($_GET['SteamVanityUrlInput'])) { 
			echo $_GET['SteamVanityUrlInput'];
	}
	?>
	"/>
	<br />
    <input type="submit" value="Submit" /><br />
</form>

<?php 
	if (isset($_GET['SteamVanityUrlInput'])!= true) {
          echo "You'll need to have set up your custom profile URL for this to work. You can do that on your Steam profile settings page.
			<br /> A custom URL looks like this: http://steamcommunity.com/id/&lt;YOURCUSTOMID&gt;/. 
			<br />You'll want to enter the \"&lt;YOURCUSTOMID&gt;\" part into the form above.";
      }
?>

<?php
 $SteamAPIKey = '25600D2915E348EDAFBAA4290F06BDA5';
#$SteamID = '76561197969190710';
if (isset($_GET['SteamVanityUrlInput'])) {
    $vanityurl = $_GET['SteamVanityUrlInput'];
    $SteamUserInfoUrl = "http://steamcommunity.com/id/{$vanityurl}/?xml=1";
    $SteamUserInfoVar = simplexml_load_file($SteamUserInfoUrl);
    if ($SteamUserInfoVar != false) {
        $SteamID = $SteamUserInfoVar->steamID64;
        $SteamGameList = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key={$SteamAPIKey}&steamid={$SteamID}&format=xml&include_appinfo=1";
        $SteamGameListXML = simplexml_load_file($SteamGameList);
        $GameArray= $SteamGameListXML->games->message;
        $MaxNumber = (int)$SteamGameListXML->game_count;
        $randnum = rand(0, $MaxNumber);
        echo "You should play <a href =\"http://store.steampowered.com/app/{$GameArray[$randnum]->appid}\">{$GameArray[$randnum]->name}</a>.<br/> Submit again for another suggestion.<br/><br/>";
        echo "Number of Games: <br/>";
        echo $SteamGameListXML->game_count.'<br/>';
        echo "<h3><u>All Your Games</u></h3>";
        foreach ($GameArray as $GameEntry) {
            echo "<a href =\"http://store.steampowered.com/app/{$GameEntry->appid}\">{$GameEntry->name}</a><br/>";
        }
    } else {
        echo "You should try again, because there doesn't seem to be any user with that vanity url.<br/>";
    }
}
?>