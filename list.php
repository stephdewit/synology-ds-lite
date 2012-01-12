<?
header('Content-type: text/html; charset=UTF-8');

session_start();

if (!$_SESSION['id'])
{
	header('Location: index.htm');
	exit();
}

$id = $_SESSION['id'];

require_once('config.inc.php');

if (isset($_GET['testdata']))
{
	$received = file_get_contents('testdata.json');
}
else
{
	$path = '/download/downloadman.cgi?action=getall';
	$ch = curl_init("$protocol://$host:$port$path");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIE, "id=$id");

	$received = curl_exec($ch);
	curl_close($ch);
}

$received || die('HTTP failure');

$result = json_decode($received);

$result || die('JSON failure');

$result->success || die("Can't get list");
?>

<html>
	<body>
		<h1>List</h1>
		<table>
			<tr>
				<th>File</th>
				<th>Size</th>
				<th>Status</th>
				<th>Progress</th>
				<th>DL speed</th>
				<th>ETA</th>
			</tr>
			<?
			require_once('formatting.inc.php');

			foreach ($result->items as $item)
			{
			?>
			<tr>
				<td><?= $item->filename ?></td>
				<td><?= trim($item->total_size) ?></td>
				<td><?= $item->status ?></td>
				<td><?= trim($item->progress) ?></td>
				<td><?= $item->current_rate ?></td>
				<td><?= format_time($item->timeleft) ?></td>
			</tr>
			<?
			}
			?>
		</table>
	</body>
</html>
