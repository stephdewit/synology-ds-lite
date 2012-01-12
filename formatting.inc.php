<?
function int_divide($a, $b)
{
	return ($a - ($a % $b)) / $b;
}

function format_time($seconds)
{
	if (!$seconds || $seconds < 0)
	{
		return '';
	}
	
	$units = array();
	$units[] = array('w', 3600 * 24 * 7);
	$units[] = array('d', 3600 * 24);
	$units[] = array('h', 3600);
	$units[] = array('m', 60);
	$units[] = array('s', 1);

	$remaining_seconds = $seconds;
	$values = array();

	foreach($units as $unit)
	{
		$quotient = int_divide($remaining_seconds, $unit[1]);
		if ($quotient > 0 || $unit[1] == 1)
		{
			$values[] = array($unit[0], $quotient);
			$remaining_seconds -= $quotient * $unit[1];
		}
	}

	$formatted_time = '';
	
	$remaining_values_to_display = 2;

	foreach($values as $value)
	{
		$formatted_time .= "$value[1] $value[0] ";
		$remaining_values_to_display--;

		if ($remaining_values_to_display == 0)
		{
			break;
		}
	}

	return trim($formatted_time);
}
?>
