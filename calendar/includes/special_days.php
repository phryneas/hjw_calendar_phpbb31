<?php
if(!defined('IN_PHPBB'))
{
	exit;
}
$sql = 'SELECT *
	FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
	ORDER by id';
$result = $db->sql_query($sql);
while($row = $db->sql_fetchrow($result))
{
	if ($row['show_on'])
	{
		if (!$row['date'] && $row['name'] <> 'Advent' && $row['name'] <> 'Buß- und Bettag')
		{
			$sp=easter_days($year)+21+(int)$row['eastern'];
			if ($row['big'] == 1)
			{
				$row['name'] = '<b>'.$row['name'].'</b>';
			}
			$special_day[date('n',mktime(0,0,0,3,$sp,$year))][date('j',mktime(0,0,0,3,$sp,$year))] = $row['name'];
			$sd_color[date('n',mktime(0,0,0,3,$sp,$year))][date('j',mktime(0,0,0,3,$sp,$year))] = $row['color'];
		}
		if ($row['big'] == 1)
		{
			$row['name'] = '<b>'.$row['name'].'</b>';
		}
		if ($row['date'])
		{
			$sp_date = explode('.',$row['date']);
			$special_day[(int)$sp_date[1]][(int)$sp_date[0]] = $row['name'];
			$sd_color[(int)$sp_date[1]][(int)$sp_date[0]] = $row['color'];
		}
		if ($row['name'] == 'Advent')
		{
			$advent = 4;
			$w = date("N", mktime(0, 0, 0, 12,25, $year));
			for ($i=0;$i<4;$i++)
			{
			$special_day[date('n',mktime(0,0,0,12,25-$w-7*$i,$year))][date('j',mktime(0,0,0,12,25-$w-7*$i,$year))]=$advent.'.Advent';
			$sd_color[date('n',mktime(0,0,0,12,25-$w-7*$i,$year))][date('j',mktime(0,0,0,12,25-$w-7*$i,$year))] = $row['color'];
			$advent--;
			}
		}
		if ($row['name'] == 'Buß- und Bettag')
		{
			$w = date("N", mktime(0, 0, 0, 12,25, $year));
			$special_day[date('n',mktime(0,0,0,12,25-$w-32,$year))][date('j',mktime(0,0,0,12,25-$w-32,$year))] = $row['name'];
			$sd_color[date('n',mktime(0,0,0,12,25-$w-32,$year))][date('j',mktime(0,0,0,12,25-$w-32,$year))] = $row['color'];
		}
	}
}