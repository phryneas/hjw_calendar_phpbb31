<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2014 calendar
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'								=> 'load_language_on_setup',
			'core.posting_modify_template_vars'   			=> 'calendar',
			'core.page_header'     							=> 'calendar_on_header',
			'core.viewonline_overwrite_location'			=> 'viewonline_page',
			'core.submit_post_end'							=> 'send_data_to_table',
			'core.viewtopic_assign_template_vars_before'	=> 'modify_participants_list',
			'core.viewtopic_modify_post_row'				=> 'display_participants_list',
		);
	}

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\config\config */
	protected $config;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbb\template			$template	Template object
	* @param \phpbb\auth\auth           $auth
	*/
	public function __construct( \phpbb\controller\helper $helper, \phpbb\auth\auth $auth, \phpbb\template\template $template,
									\phpbb\config\config $config)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->auth = $auth;
	}

	public function viewonline_page($event)
	{
			global $user, $phpbb_path_helper, $phpEx;

			switch ($event['on_page'][1])
			{
				case 'app':
				if (strrpos($event['row']['session_page'], '/calendar'))
				{
					$event['location'] = $user->lang('VIEWING_CALENDAR');
					$event['location_url'] = $this->helper->route('hjw_calendar_controller');
				}
				break;
			}
	}	

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'hjw/calendar',
			'lang_set' => 'calendar',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function display_participants_list($event)
	{ 
		global $phpbb_root_path, $template, $db, $phpEx, $user, $auth, $request;

		$this->request = $request;
		$this->user = $user;

		$post_id = $event['row']['post_id'];
		$forum_id = $event['row']['forum_id'];
		$topic_id = $event['row']['topic_id'];

		$date_format  = $user->data['user_dateformat'];

		$number['yes']	= 0;
		$number['no']	= 0;
		$number['mb']	= 0;

		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
		include_once($this->root_path . 'includes/constants.' . $phpEx);
		
		$p_id = $event['cp_row'];
		$sql = 'SELECT *
			FROM ' . CALENDAR_TABLE . '
			WHERE post_id = "' . $post_id.'"';
				
		$result = $db->sql_query($sql);
		$event_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if ($event_row)
		{
			$e_id	=	$event_row['event_id'];
			$e_n	=	$event_row['event_name'];
			$f		=	explode('-',$event_row['date_from']);
			$t		=	explode('-',$event_row['date_to']);
			$cal_date = $f[2]. '.' . $f[1] . '.' . $f[0];
			if ($t[2] > 0)
			{
				$cal_date .= ' - ' . $t[2]. '.' . $t[1] . '.' . $t[0];
			}
			$link = $phpbb_root_path . 'calendar/?month=' . $f[1] . '&year=' . $f[0];
			
			$sql = 'SELECT *
				FROM ' . CALENDAR_EVENT_TABLE . '
				WHERE id = "' . $event_row['event_id'].'"';
			
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if ($row['participants'])
			{
				$sql = 'SELECT *
					FROM ' . CALENDAR_FORUMS_TABLE . '
					WHERE forum_id = "' . $forum_id.'"';
				$result = $db->sql_query($sql);
				$forum_row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
				if ($forum_row)
				{
					if ($forum_row['allowed'] == 1)
					{
						$p_id['row'] = array(
							'ENTRY'				=>	$user->lang('CALENDAR_ENTRY') . ': ' . $cal_date,
							'LINK'				=>	$link,
							'PARTICIPANTS_ID'	=>	true,
							'U_PARTICIPANTS'	=>	append_sid($phpbb_root_path . 'viewtopic.php?f='.$forum_id.'&amp;t='.$topic_id.'#p'.$post_id),
						);
						$sql = 'SELECT *
							FROM ' . CALENDAR_PARTICIPANTS_TABLE . '
							WHERE post_id = "' . $post_id.'"';
						$result = $db->sql_query($sql);
						while($part_row = $db->sql_fetchrow($result))
						{
							$sql = 'SELECT user_colour, username
								FROM ' . USERS_TABLE . '
								WHERE user_id = "' . $part_row['user_id'].'"';
							$user_result = $db->sql_query($sql);
							while($user_row = $db->sql_fetchrow($user_result))
							{

								$number[''.$part_row['participants'].''] += (int)$part_row['number'];
								$r		= explode('-',$part_row['date'].'-0-0');
								$p_date = $user->create_datetime()
									->setDate($r[0], $r[1], $r[2])
									->setTime($r[3],$r[4], 0)
									->format($date_format, true);

								$p_id['row']['LIST'][] = array(
									'PARTICIPANTS_USER'		=> $user_row['username'],
									'PARTICIPANTS_COLOUR'	=> $user_row['user_colour'],
									'PARTICIPANTS_NUMBER'	=> $part_row['number'],
									'PARTICIPANTS_PART'		=> $user->lang['CALENDAR_'.strtoupper($part_row['participants']).''],
									'PARTICIPANTS_COMMENTS'	=> $part_row['comments'],
									'PARTICIPANTS_DATE'		=> $p_date,
								);
							}
							$db->sql_freeresult($user_result);
						}
						$p_id['row']['0'] = array(
							'PARTICIPANTS_COUNT'	=> $number['yes'] . '&nbsp;/&nbsp;' . $number['mb'] . '&nbsp;/&nbsp;' . $number['no'],
						);
					}
				}
			}
			else
			{
				$p_id['row'] = array(
					'ENTRY'				=>	$user->lang('CALENDAR_ENTRY') . ': ' . $cal_date,
					'LINK'				=>	$link,
				);
			}
			$event['cp_row'] = $p_id;
		}
	}

	public function calendar_on_header()
	{ 
		global $phpbb_root_path, $phpbb_path_helper, $template, $db, $auth ,$phpEx, $user, $config, $request;
		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
		$this->php_ex = $phpEx;
		$this->config = $config;
		$this->request = $request;

		include_once($this->root_path . 'includes/constants.' . $phpEx);
		$calendar_link	=	$this->helper->route('hjw_calendar_controller');
		if (strpos($calendar_link, 'app.' . $this->php_ex . '/calendar') === false)
		{
		//	$calendar_link 	= str_replace('/calendar', '/app.' . $this->php_ex . '/calendar', $calendar_link);
		}
		$this->template->assign_vars(array(
				'U_CALENDAR'				=> $calendar_link,	
				'S_WEEK_ON_INDEX'			=> $this->config['week_on_index'],
				'S_BIRTHDAY_ON_CALENDAR'	=> $this->config['birthday_on_calendar'],
				'S_CALENDAR_FOR_GUESTS'		=> $this->config['calendar_for_guests'],
			));	
		$day = date("j"); 
		$month = date("n"); 
		$year = date("Y"); 

		include($this->root_path . 'includes/special_days.' . $phpEx);
			
		$month_name = array(
			1 => $user->lang['datetime']['January'],
			2 => $user->lang['datetime']['February'],
			3 => $user->lang['datetime']['March'],
			4 => $user->lang['datetime']['April'],
			5 => $user->lang['datetime']['May'],
			6 => $user->lang['datetime']['June'],
			7 => $user->lang['datetime']['July'],
			8 => $user->lang['datetime']['August'],
			9 => $user->lang['datetime']['September'],
		   10 => $user->lang['datetime']['October'],
		   11 => $user->lang['datetime']['November'],
		   12 => $user->lang['datetime']['December'],
		);

		$weekday = array(
			1 => $user->lang['datetime']['Monday'],
			2 => $user->lang['datetime']['Tuesday'],
			3 => $user->lang['datetime']['Wednesday'],
			4 => $user->lang['datetime']['Thursday'],
			5 => $user->lang['datetime']['Friday'],
			6 => $user->lang['datetime']['Saturday'],
			7 => $user->lang['datetime']['Sunday'],
		);
		$wday = array(
			1 => $user->lang['datetime']['Mon'],
			2 => $user->lang['datetime']['Tue'],
			3 => $user->lang['datetime']['Wed'],
			4 => $user->lang['datetime']['Thu'],
			5 => $user->lang['datetime']['Fri'],
			6 => $user->lang['datetime']['Sat'],
			7 => $user->lang['datetime']['Sun'],
		);
		$c_days = ($this->config['number_of_weeks']*7)-1;
		for ($y=0;$y<=$c_days;$y++)
		{
			$i=$y;
			while ($i > 6)
			{
				$i -= 7;
			}
			$d=date("N")+$y;
			if ($y<7)
			{
				$dd=$d;
				if ($dd>7)
				{
					$dd -= 7;
				}
				$this->template->assign_block_vars('weekday' ,array(
					'DAY'	=> $weekday[$dd],
					'SDAY'	=> $wday[$dd],
				));
			}
			while ($d > 7)
			{
				$d=$d-7;
			}
			$bg = 'bg1';
			if (is_int(($d+1)/7))
			{
				$bg = 'sat';
			}
			if (is_int($d/7))
			{
				$bg = 'sun';
			}
			$td = '';
			if ($i == 0)
			{
				$td = '<tr>';
			}
			$td .= '<td class="'.$bg.'" title="'. $day.'.'.$month_name[$month].'">';

			$tde = '</td>';
			if ($i == 6)
			{
				$tde = '</td></tr>';
			}
			$this->template->assign_block_vars('day', array(
				'INDEX'	=> true,
				'DATE'	=> $day.'.',
				'TD'	=> $td,
				'TDE'	=> $tde,
			));

			include($this->root_path . 'includes/calendar_event.' . $phpEx);

			$day++;
			if ($day > date("t", mktime(0, 0, 0, $month, 1, $year)))
			{
				$day = $day-date("t", mktime(0, 0, 0, $month, 1, $year));
				$month++;
				if ($month > 12)
				{
					$month = $month-12;
					$year = $year+1;
				}
			}
		}
	}

	public function modify_participants_list()
	{
		global $phpbb_root_path, $phpbb_path_helper, $template, $db, $auth ,$phpEx, $user, $config, $request;
		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
		$this->php_ex = $phpEx;
		$this->request = $request;
		$user_id  = $user->data['user_id'];
		include_once($this->root_path . 'includes/constants.' . $phpEx);
		if ($request->variable('part', ''))
		{
			if ($user_id)
			{
				$sql_ary = array(
					'POST_ID'		=> $request->variable('participants_id', '', true),
					'USER_ID'		=> $user_id,
					'NUMBER'		=> (int)($request->variable('group', '', true)),
					'PARTICIPANTS'	=> utf8_normalize_nfc($request->variable('part', '', true)),
					'COMMENTS'		=> utf8_normalize_nfc($request->variable('comments', '', true)),
					'DATE'			=> date("Y-n-j-H-i"),
				);
				$sql='SELECT * from ' . CALENDAR_PARTICIPANTS_TABLE . "
					WHERE post_id = '" . $sql_ary['POST_ID'] ."' and user_id = '" . $user_id."'"; 
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				if ($row)
				{
					$sql = 'UPDATE ' . CALENDAR_PARTICIPANTS_TABLE . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . "
						WHERE post_id = '" . $sql_ary['POST_ID'] ."' and user_id = '" . $user_id."'"; 
				}
				else
				{
					$sql = 'INSERT INTO ' . CALENDAR_PARTICIPANTS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);	
				}
				$result = $db->sql_query($sql);
			}
		}
	}

	public function calendar($event)
	{ 
		global $phpbb_root_path, $phpbb_extension_manager, $phpbb_path_helper, $template, $db, $phpEx, $user, $request;
		$this->request = $request;

		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
		include($this->root_path . 'includes/constants.' . $phpEx);

		$post_id = $event['post_id'];
		$forum_id = $event['forum_id'];
		
		$preview	= (isset($_POST['preview'])) ? true : false;
		
		$sql = 'SELECT *
			FROM ' . CALENDAR_FORUMS_TABLE . '
			WHERE forum_id = "' . $forum_id.'"';
		$result = $db->sql_query($sql);
		$forum_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if ($forum_row)
		{
			if ($forum_row['allowed'] == 1)
			{
				$this->template->assign_vars( array(
					'CALENDAR_ALLOWED'			=> true,
				));	
			
				$month_name = array(
					1 => $user->lang['datetime']['January'],
					2 => $user->lang['datetime']['February'],
					3 => $user->lang['datetime']['March'],
					4 => $user->lang['datetime']['April'],
					5 => $user->lang['datetime']['May'],
					6 => $user->lang['datetime']['June'],
					7 => $user->lang['datetime']['July'],
					8 => $user->lang['datetime']['August'],
					9 => $user->lang['datetime']['September'],
				   10 => $user->lang['datetime']['October'],
				   11 => $user->lang['datetime']['November'],
				   12 => $user->lang['datetime']['December'],
				);
		
				$event_id 	= '';
				$event_name	= '';
				$from 		= '';
				$r			= '';
				$from_year	= '';
				$from_month = '';
				$from_day	= '';
				$to 		= '';
				$r			= '';
				$to_year	= '';
				$to_month 	= '';
				$to_day		= '';

				if ($post_id)
				{
					$sql = 'SELECT *
						FROM ' . CALENDAR_TABLE . '
							WHERE post_id = ' . $post_id;
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					if($row)
					{
						$present=true;
						$event_id 	= $row['event_id'];
						$event_name	= $row['event_name'];
						$from 		= $row['date_from'];
						$r			= explode('-',$from);
						$from_year	= $r[0];
						$from_month = $r[1];
						$from_day	= $r[2];
						$to 		= $row['date_to'];
						$r			= explode('-',$to);
						$to_year	= $r[0];
						$to_month 	= $r[1];
						$to_day		= $r[2];
					}
				}
				
				$event_id	= $request->variable('event', $event_id);
				$event_name	= $request->variable('event_name', $event_name);
				$from_day	= str_pad($request->variable('from_day',	$from_day),		2 ,'0', STR_PAD_LEFT);
				$from_month	= str_pad($request->variable('from_month',	$from_month),	2 ,'0', STR_PAD_LEFT);
				$from_year	= str_pad($request->variable('from_year',	$from_year),	4 ,'0', STR_PAD_LEFT);
				$to_day		= str_pad($request->variable('to_day',		$to_year),		2 ,'0', STR_PAD_LEFT);
				$to_month	= str_pad($request->variable('to_month',	$to_month),		2 ,'0', STR_PAD_LEFT);
				$to_year	= str_pad($request->variable('to_year',		$to_year),		4 ,'0', STR_PAD_LEFT);
				$from		= $from_year.'-'.$from_month.'-'.$from_day;
				$to			= $to_year.'-'.$to_month.'-'.$to_day;
				
				$this->template->assign_vars(array(
					'EVENT_NAME' => $event_name,
				));	
		
				$this->template->assign_block_vars('from_day', array(
					'SELECT' =>'<option value=" ">'.$user->lang['DAY'].'</option>',
				));	
				
				for ($i=1;$i<=31;$i++)
				{
					$s='';if ($i == $from_day) $s=' selected="selected"';  
					$this->template->assign_block_vars('from_day', array(
						'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
					));	
				}
		
				$this->template->assign_block_vars('to_day', array(
					'SELECT' =>'<option value=" ">'.$user->lang['DAY'].'</option>',
				));	

				for ($i=1;$i<=31;$i++)
				{
					$s='';if ($i == $to_day) $s=' selected="selected"';  
					$this->template->assign_block_vars('to_day', array(
						'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
					));	
				}

				$this->template->assign_block_vars('from_month', array(
					'SELECT' =>'<option value=" ">'.$user->lang['MONTH'].'</option>',
				));	

				for ($i=1;$i<=12;$i++)
				{
					$s='';if ($i == $from_month) $s=' selected="selected"';  
					$this->template->assign_block_vars('from_month', array(
						'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
					));	
				}

				$this->template->assign_block_vars('to_month', array(
					'SELECT' =>'<option value=" ">'.$user->lang['MONTH'].'</option>',
				));	

				for ($i=1;$i<=12;$i++)
				{
					$s='';if ($i == $to_month) $s=' selected="selected"';  
					$this->template->assign_block_vars('to_month', array(
						'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
					));	
				}

				$date = getdate();
				$year=$date['year']*1;
				if ($from_year > 0) $year = $from_year;
				$this->template->assign_block_vars('from_year', array(
					'SELECT' =>'<option value=" ">'.$user->lang['YEAR'].'</option>',
				));	

				for ($i=$year;$i<$year+10;$i++)
				{
					$s='';if ($i == $from_year) $s=' selected="selected"';  
					$this->template->assign_block_vars('from_year', array(
						'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
					));	
				}
		
				$this->template->assign_block_vars('to_year', array(
					'SELECT' =>'<option value=" ">'.$user->lang['YEAR'].'</option>',
				));	

				for ($i=$year;$i<$year+10;$i++)
				{
					$s='';if ($i == $to_year) $s=' selected="selected"';  
					$this->template->assign_block_vars('to_year', array(
						'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
					));	
				}

				$this->template->assign_block_vars('eventselect', array(
					'SELECT' =>'<option value=" "> </option>',
				));	
		
				$sql = 'SELECT *
					FROM ' . CALENDAR_EVENT_TABLE;
				$result = $db->sql_query($sql);
				while($row = $db->sql_fetchrow($result))
				{
					$s='';if ($row['id']*1 == $event_id*1) $s=' selected="selected"';  
					$this->template->assign_block_vars('eventselect', array(
						'SELECT' =>'<option'.$s.' value="'.$row['id'].'">'.$row['event'].'</option>',
					));
				}
			}
		}
	}

	public function send_data_to_table($event)
	{
		global $phpbb_root_path, $phpbb_extension_manager, $phpbb_path_helper, $template, $db, $phpEx, $user, $request;
		$this->request = $request;
		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
		include($this->root_path . 'includes/constants.' . $phpEx);
		$post_id = $event['data']['post_id'];

		$present = false;
		$e_id	=	'';
		$e_n	=	'';
		$f		=	'';
		$t		=	'';
		$sql = 'SELECT *
			FROM ' . CALENDAR_TABLE . '
				WHERE post_id = ' . $post_id;
		$result = $db->sql_query($sql);
		if($row = $db->sql_fetchrow($result))
		{
			$present=true;
			$e_id	=	$row['event_id'];
			$e_n	=	$row['event_name'];
			$f		=	explode('-',$row['date_from']);
			$t		=	explode('-',$row['date_to']);
		}
		$event_id	= $request->variable('event', $e_id);
		$event_name	= utf8_normalize_nfc($request->variable('event_name', $e_n, true));
		$from_day	= str_pad($request->variable('from_day',	$f[2]),	2 ,'0', STR_PAD_LEFT);
		$from_month	= str_pad($request->variable('from_month',	$f[1]),	2 ,'0', STR_PAD_LEFT);
		$from_year	= str_pad($request->variable('from_year',	$f[0]),	4 ,'0', STR_PAD_LEFT);
		$to_day		= str_pad($request->variable('to_day',		$t[2]),	2 ,'0', STR_PAD_LEFT);
		$to_month	= str_pad($request->variable('to_month',	$t[1]),	2 ,'0', STR_PAD_LEFT);
		$to_year	= str_pad($request->variable('to_year',		$t[0]),	4 ,'0', STR_PAD_LEFT);
		$from		= $from_year.'-'.$from_month.'-'.$from_day;
		$to			= $to_year.'-'.$to_month.'-'.$to_day;

		$sql_ary = array(
			'POST_ID'			=> $post_id,
			'EVENT_ID'			=> $event_id,
			'EVENT_NAME'		=> $event_name,
			'DATE_FROM'			=> $from,
			'DATE_TO'			=> $to,
		);
			
		if ($present & $event_id == '')
			{
			$sql = 'DELETE FROM ' . CALENDAR_TABLE . " 
				WHERE post_id = '" . $post_id."'"; 
			}

		if ($event_id > 0)
		{
			if ($present)
			{
				$sql = 'UPDATE ' . CALENDAR_TABLE . '
					SET ' . $db->sql_build_array('UPDATE', $sql_ary) . "
					WHERE post_id = '" . $post_id."'";
			}
			else
			{
				$sql = 'INSERT INTO ' . CALENDAR_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);		
			}
		}
		$result = $db->sql_query($sql);
	}
}
