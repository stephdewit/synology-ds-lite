<?php
if (isset($_SESSION['id']))
{
	session_destroy();
}

if (empty($_POST['username']) || empty($_POST['password']))
{
	header('Location: index.htm');
	exit();
}

require_once('config.inc.php');

$fields = 'action=login';
$fields = $fields . '&username=' . $_POST['username'];
$fields = $fields . '&passwd=' . $_POST['password'];

$cookieFile = tempnam(sys_get_temp_dir(), 'cURL-cookie-');
$ch = curl_init("$protocol://$host:$port/webman/login.cgi");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

$received = curl_exec($ch);
curl_close($ch);

$received || die('HTTP failure');

$result = json_decode($received);

$result || die('JSON failure');

if (is_file($cookieFile))
{
	$cookie = file_get_contents($cookieFile);
	unlink($cookieFile);
}

if ($result->result == 'success' && $result->success)
{
	!empty($cookie) || die('Cookie failure');
	
	if (preg_match("/$host.+id\s(?<id>\S+)$/", $cookie, $matches) == 1)
	{
		session_start();
		$_SESSION['id'] = $matches['id'];
		header('Location: list.php');
	}
	else
	{
		die("Can't find ID in $cookie");
	}
}
else
{
	header('Location: index.htm?loginfailed');
}
?>
