<?php
if(!defined('IN_PHPBB'))
{
	exit;
}

$leap_year = date("L",mktime(0,0,0,1,1,$year));

if (isset($special_day[$month][$day]))
{
	$this->template->assign_block_vars('day.cdh', array(
		'HDAY'			=> '<span class="hday" style="color:#'.$sd_color[$month][$day].'">'.$special_day[$month][$day].'</span></div>
							<span class="shday" style="color:#'.$sd_color[$month][$day].'">'.$special_day[$month][$day].'<br></span>',
		));
}
else
{
	$this->template->assign_block_vars('day.cdh', array(
		'HDAY'			=> '</div>',
		));
}
if (!$leap_year && $month == 2 && $day == 28)
{
$sql = 'SELECT *
	FROM ' . CALENDAR_EVENT_LIST_TABLE . '
		WHERE date_from = "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" 
	OR
		date_from <= "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" AND 
			date_to >= "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" 
	OR
		date_from = "0000-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" OR 
			date_from <= "0000-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" AND 
				date_to >= "0000-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" 
	OR
		date_from LIKE "%-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'"  AND 
			anniversary = "1" 
	OR
		date_from LIKE "%-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day+1, 2, 0, STR_PAD_LEFT).'"  AND 
			anniversary = "1" 
	ORDER by id';
}
else
{
$sql = 'SELECT *
	FROM ' . CALENDAR_EVENT_LIST_TABLE . '
		WHERE date_from = "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" 
	OR 
		date_from <= "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" AND 
			date_to >= "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" 
	OR
		date_from = "0000-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" OR 
			date_from <= "0000-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" AND 
				date_to >= "0000-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" 
	OR
		date_from LIKE "%-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'"  AND 
			anniversary = "1" 
	ORDER by id';
}
$event_result = $db->sql_query($sql);
while($event_row = $db->sql_fetchrow($event_result))
{
	$show = true;
	$age = $event_row['appointment'];
	
	if ($event_row['anniversary'])
	{
		$from		= $event_row['date_from'];
		$r			= explode('-',$from);
		$from_year	= (int)$r[0];
		if ($year >= $from_year)
		{
			if ($year > $from_year)
			{
				$age .= ' ('.($year - $from_year).')';
			}
		}
		else
		{
			$show = false;
		}
	}
	$subject = $event_row['description'];
	if ($subject == '') $subject = $age;
	if ($show)
	{
		if ($event_row['big'] == 1)
		{
			$age = '<b>'.$age.'</b>';
		}
		$this->template->assign_block_vars('day.cdh', array(
			'LINK'			=> $event_row['link'],
			'EVENT_NAME' 	=> $age,
			'COLOR' 		=> $event_row['color'],
			'SUBJECT'		=> $subject,
			));
	}
}
$sql = 'SELECT *
	FROM ' . CALENDAR_TABLE . '
		WHERE date_from = "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" OR 
			date_from <= "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" AND 
				date_to >= "' . $year.'-'.str_pad($month, 2, 0, STR_PAD_LEFT).'-'.str_pad($day, 2, 0, STR_PAD_LEFT).'" 
	ORDER by event_id';
$event_result = $db->sql_query($sql);
while($event_row = $db->sql_fetchrow($event_result))
{
	$event_id 	= $event_row['event_id'];
	$event_name = $event_row['event_name'];
	$post_id 	= $event_row['post_id'];
	$link		= '';
	$sql = 'SELECT *
		FROM ' . POSTS_TABLE . '
		WHERE post_id = "' . $post_id . '"';
	$result = $db->sql_query($sql);
	while($row = $db->sql_fetchrow($result))
	{
		if ($row['post_visibility'] == 1)
		{
			$user_id = $user->data['user_id'];
			$auth_array = $this->auth->acl_raw_data($user_id, 'f_read', $row['forum_id']);
			if (isset($auth_array[$user_id][$row['forum_id']]['f_read']) && $auth_array[$user_id][$row['forum_id']]['f_read'])
			{
				$link 	= $phpbb_root_path . 'viewtopic.php?f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id'] . '#p' . $row['post_id'];
				$subject = $row['post_subject'];
			}
		}
	}
	if ($link)
	{
		$sql = 'SELECT *
			FROM ' . CALENDAR_EVENT_TABLE . '
			WHERE id = "' . $event_id . '"';
		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result))
		{
			$event 	= $row['event'];
			$color 	= $row['color'];
			if ($row['big'] == 1)
			{
				$event_name = '<b>'.$event_name.'</b>';
			}
		}
		$this->template->assign_block_vars('day.cdh', array(
			'LINK'			=> append_sid($link),
			'EVENT' 		=> $event,
			'EVENT_NAME' 	=> $event_name,
			'COLOR' 		=> $color,
			'SUBJECT'		=> $subject,
			));
	}
}

if ($this->config['birthday_on_calendar'] == 1)
{
	if (!$leap_year && $month == 2 && $day == 28)
	{
		$sql = 'SELECT *
			FROM ' . USERS_TABLE . '
				WHERE user_birthday LIKE "'.str_pad($day, 2, ' ', STR_PAD_LEFT).'-'.str_pad($month, 2, ' ', STR_PAD_LEFT).'-%" 
					OR user_birthday LIKE "'.str_pad($day+1, 2, ' ', STR_PAD_LEFT).'-'.str_pad($month, 2, ' ', STR_PAD_LEFT).'-%" 
						ORDER by user_birthday';
	}
	else
	{
		$sql = 'SELECT *
			FROM ' . USERS_TABLE . '
				WHERE user_birthday LIKE "'.str_pad($day, 2, ' ', STR_PAD_LEFT).'-'.str_pad($month, 2, ' ', STR_PAD_LEFT).'-%" 
					ORDER by user_birthday';
	}
	$result = $db->sql_query($sql);
	while($row = $db->sql_fetchrow($result))
	{
		$user_name	= $row['username'];
		$subject = $user->lang['BIRTHDAY'].' '.$user_name;
		$age = explode ('-',$row['user_birthday']);
		if ( checkdate($age[1], $age[0], $age[2]) )
		{
			$user_age = $year - $age[2];
		}
		else
		{
			$user_age = -1;
		}
		if ($user_age >= 0)
		{
			if($user_age == 0)
			{
				$user_age = '';
			}
			else
			{
				$subject = $user_age.'. '.$subject;
				$user_age = ' ('.$user_age.')';
			}
			$this->template->assign_block_vars('day.cdh', array(
				'LINK'			=> append_sid($phpbb_root_path . 'memberlist.php?mode=viewprofile&u='.$row['user_id']),
				'EVENT' 		=> '',
				'EVENT_NAME' 	=> $user_name.$user_age,
				'COLOR' 		=> $row['user_colour'],
				'SUBJECT'		=> $subject,
			));
		}
	}
}