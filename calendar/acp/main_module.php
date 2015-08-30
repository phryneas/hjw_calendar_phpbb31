<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2015 calendar
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\acp;


class main_module
{
	var $u_action;

	function main()
	{
		global $db, $user, $cache, $request, $template; 
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpbb_container, $phpEx;

		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';

		include($this->root_path . 'includes/constants.' . $phpEx);

		$this->db = $db;
		$this->user = $user;
		$this->cache = $cache;
		$this->template = $template;
		$this->config = $config;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $phpbb_admin_path;
		$this->php_ex = $phpEx;

		$user->add_lang('acp/common');
		$this->page_title = $user->lang('ACP_CALENDAR_TITLE');

		$form_key = 'acp_calendar';
		add_form_key($form_key);

		$mode = $request->variable('mode', 'instructions');	

		switch($mode)
		{
			case 'instructions':
				$this->tpl_name = 'acp_calendar_instructions';
			break;
			
			case 'displayoptions':

				$config->set('week_on_index', $request->variable('week_on_index', $this->config['week_on_index']));
				for ($i=0;$i<=3;$i++)
				{
					$s=''; 
					if (($this->config['week_on_index']) == $i) $s =' selected="selected"'; 
					$template->assign_block_vars('weekblock', array(
						'SELECT' =>'<option'.$s.' value="'.$i.'">'.$user->lang('ACP_WEEKBLOCK_TEMPLATE_'.$i.'').'</option>',
					));	
				}

				$config->set('number_of_weeks', $request->variable('number_of_weeks', $this->config['number_of_weeks']));
				if ($this->config['number_of_weeks'] == 0)
				{
					$config->set('number_of_weeks', 1);
				}
				$template->assign_vars(array(
					'NUMBER_OF_WEEKS' => $this->config['number_of_weeks'],
				));	

				$config->set('birthday_on_calendar', $request->variable('birthday_on_calendar', $this->config['birthday_on_calendar']));
				$boc1 = '';
				if (($this->config['birthday_on_calendar']) == 1) $boc1 =' checked="checked"';
				$boc0 = '';
				if (($this->config['birthday_on_calendar']) == 0) $boc0 =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'BOC1'			=> $boc1,
					'BOC0'			=> $boc0,
				));

				$config->set('calendar_for_guests', $request->variable('calendar_for_guests', $this->config['calendar_for_guests']));
				$cfg1 = '';
				if (($this->config['calendar_for_guests']) == 1) $cfg1 =' checked="checked"';
				$cfg0 = '';
				if (($this->config['calendar_for_guests']) == 0) $cfg0 =' checked="checked"';
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'CFG1'			=> $cfg1,
					'CFG0'			=> $cfg0,
				));

				$config->set('calendar_only_first_post', $request->variable('calendar_only_first_post', $this->config['calendar_only_first_post']));
				if (($this->config['calendar_only_first_post']) == 1) 
				{
					$cofp0 ='';
					$cofp1 =' checked="checked"';
				}
				else
				{
					$cofp0 =' checked="checked"';
					$cofp1 ='';
				}
				$this->template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
					'COFP1'			=> $cofp1,
					'COFP0'			=> $cofp0,
				));

				$this->tpl_name = 'acp_calendar_displayoptions';
			break;

			case 'settings':
				$this->tpl_name = 'acp_calendar_event_settings';
				$action	= $request->variable('action', '');
				$id 	= $request->variable('id', 0);
				switch ($action)
				{
					case 'add':
						$this->template->assign_vars(array(
							'ID' 					=> '',
							'EVENT' 				=> '',
							'COLOR' 				=> '',
							'PARTICIPANTS'			=> 0,
							'BIG'					=> 0,
							'BCOLOR' 				=> '',
							'U_MODIFY'				=> $this->u_action . '&amp;action=create&amp;id=' . $id,
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'edit':
						$sql = 'SELECT *
							FROM ' . CALENDAR_EVENT_TABLE . '
							WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						$this->template->assign_vars(array(
							'ID' 					=> $row['id'],
							'EVENT' 				=> $row['event'],
							'COLOR' 				=> $row['color'],
							'PARTICIPANTS'			=> $row['participants'],
							'BIG'					=> $row['big'],
							'BCOLOR' 				=> $row['bcolor'],
							'U_MODIFY'				=> $this->u_action . '&amp;action=modify&amp;id=' . $row['id'],
							'S_EDIT_CALENDAR_EVENT'	=> true,
							));
					break;

					case 'delete':
						$sql = 'DELETE
							FROM ' . CALENDAR_EVENT_TABLE . '
							WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
					break;

					case 'modify':
						$sql_ary = array(
							'EVENT'				=> utf8_normalize_nfc($request->variable('event', '', true)),
							'COLOR'				=> $request->variable('color', ''),
							'PARTICIPANTS'		=> $request->variable('participants', 0),
							'BIG'				=> $request->variable('big', 0),
							'BCOLOR'			=> $request->variable('bcolor', ''),
						);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE id = ' . (int) $id;
						$db->sql_query($sql);
					break;
			
					case 'create':
						$sql_ary = array(
							'EVENT'				=> utf8_normalize_nfc($request->variable('event', '', true)),
							'COLOR'				=> $request->variable('color', ''),
							'PARTICIPANTS'		=> $request->variable('participants', 0),
							'BIG'				=> $request->variable('big', 0),
							'BCOLOR'			=> $request->variable('bcolor', ''),
						);
						$sql = 'INSERT INTO ' . CALENDAR_EVENT_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
						$db->sql_query($sql);
					break;
			
				}

				$sql = 'SELECT *
					FROM ' . CALENDAR_EVENT_TABLE . '
					ORDER by id';
				$result = $db->sql_query($sql);
				while($row = $db->sql_fetchrow($result))
				{
					if ($row['big'] == 1)
					{
						$b = '<b>';
						$nb= '</b>';
					}
					else
					{
						$b = '';
						$nb= '';
					}
					$this->template->assign_block_vars('calendar_events', array(
						'ID' 			=> $row['id'],
						'EVENT' 		=> $b.$row['event'].$nb,
						'COLOR' 		=> $row['color'],
						'PARTICIPANTS'	=> $b.$user->lang['ACP_CALENDAR_'.$row['participants'].''].$nb,
						'BCOLOR'		=> $row['bcolor'],
						'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['id'],
						'U_DELETE'		=> $this->u_action . '&amp;action=delete&amp;id=' . $row['id'],
					));	
				}
				$this->template->assign_vars(array(
					'U_ACTION'				=> $this->u_action . '&amp;action=add',
					'S_CALENDAR_VERSION'	=> $user->lang['ACP_CALENDAR_TITLE'] . $user->lang['ACP_CALENDAR_VERSION'] . $config['calendar_version'],
				));
			break;

			case 'forums_settings':
				$this->tpl_name = 'acp_calendar_forums_settings';
				$action	= $request->variable('action', '');
				$this->template->assign_vars(array(
					'U_CALENDAR_FORUM'		=> $this->u_action . '&amp;action=forum',
				));
		
				if ($action == 'forum')
				{
					$forum_id = (int)$request->variable('forum', '0');
					$sql = 'SELECT *
						FROM ' . CALENDAR_FORUMS_TABLE . '
						WHERE forum_id = "' . $forum_id.'"';
				
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					$row['allowed'] ++;
					if ($row['allowed'] > 1) $row['allowed'] = 0 ;

					$sql_ary = array(
						'ALLOWED' 		=> $row['allowed'],
					);
				
					$sql = 'UPDATE
						' . CALENDAR_FORUMS_TABLE . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE forum_id = "' . $forum_id.'"';
					$db->sql_query($sql);

				}
				$forum_list = make_forum_select(false, false, true, true, true, false, true);

				foreach ($forum_list as $list_row)
				{
			
					$sql = 'SELECT *
						FROM ' . CALENDAR_FORUMS_TABLE . '
						WHERE forum_id = "' . $list_row['forum_id'].'"';
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					if (!$row)
					{
						$sql_ary = array(
							'FORUM_ID' 				=> $list_row['forum_id'],
							'ALLOWED' 				=> 0,
						);
						$sql = 'INSERT INTO ' . CALENDAR_FORUMS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
						$db->sql_query($sql);
						$color = 'red';
						$text = $user->lang['ACP_CALENDAR_ALLOWED_0'].'  &gt; '.$user->lang['ACP_CALENDAR_CHANGE'];
					}
					else
					{
						$color = 'green';
						if ($row['allowed'] == 0) 
						{
							$color = 'red';
						}
						$text = $user->lang['ACP_CALENDAR_ALLOWED_'.$row['allowed'].''].'  > '.$user->lang['ACP_CALENDAR_CHANGE'];
					}
					if ($list_row['forum_type'] == 0)	$fcolor="#BBBBBB";
					if ($list_row['forum_type'] == 1)	$fcolor=$color;
				
					$this->template->assign_block_vars('forum',array(
						'FORUM'	=> $list_row['padding'].$list_row['forum_name'],
						'COLOR'	=> $fcolor,
						'ID'	=> $this->u_action . '&amp;action=forum&amp;forum='.$list_row['forum_id'],
						'TEXT'	=> $text,
						'TYPE'	=> $list_row['forum_type'],
					));
					
				}

			break;
	
			case 'event_list':
				$this->tpl_name = 'acp_calendar_event_list';

				$action		= $request->variable('action', '');
				$id 		= $request->variable('id', 0);
				$from 		= $request->variable('date_from', '00.00.0000').'.00.00.00';
				$r			= explode('.',$from);
				$from_year	= str_pad($r[2], 4 ,'0', STR_PAD_LEFT);
				$from_month = str_pad($r[1], 2 ,'0', STR_PAD_LEFT);
				$from_day	= str_pad($r[0], 2 ,'0', STR_PAD_LEFT);
				$to 		= $request->variable('date_to', '00.00.0000').'.00.00.00';
				$r			= explode('.',$to);
				$to_year	= str_pad($r[2], 4 ,'0', STR_PAD_LEFT);
				$to_month 	= str_pad($r[1], 2 ,'0', STR_PAD_LEFT);
				$to_day		= str_pad($r[0], 2 ,'0', STR_PAD_LEFT);

				switch ($action)
				{
					case 'add':
						$this->template->assign_vars(array(
							'ID' 					=> '',
							'APPOINTMENT' 			=> '',
							'DESCRIPTION' 			=> '',
							'LINK'					=> '',
							'ANNIVERSARY' 			=> '',
							'DATE_FROM' 			=> '',
							'DATE_TO' 				=> '',
							'COLOR'					=> '',
							'BIG'					=> 0,
							'BCOLOR'				=> '',
							'U_MODIFY'				=> $this->u_action . '&amp;action=create&amp;id=' . $id,
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'edit':
						$sql = 'SELECT *
							FROM ' . CALENDAR_EVENT_LIST_TABLE . '
							WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						$from		= $row['date_from'];	
						$r			= explode('-',$from);
						$from_year	= (int)$r[0];
						$from_month = (int)$r[1];
						$from_day	= (int)$r[2];
						$date_from  = $from_day.'.'.$from_month.'.';
						if ($from_year > 0) $date_from .= $from_year;
				
						$to 		= $row['date_to'];
						$r			= explode('-',$to);
						$to_year	= (int)$r[0];
						$to_month 	= (int)$r[1];
						$to_day		= (int)$r[2];
						$date_to	= '';
						if ($to_day)
						{
							$date_to = $to_day.'.'.$to_month.'.';
							if ($to_year > 0) $date_to .= $to_year;
						}
					
						$this->template->assign_vars(array(
							'ID' 					=> $row['id'],
							'APPOINTMENT'			=> $row['appointment'],
							'DESCRIPTION' 			=> $row['description'],
							'LINK'					=> $row['link'],
							'ANNIVERSARY' 			=> $row['anniversary'],
							'DATE_FROM' 			=> $date_from,
							'DATE_TO' 				=> $date_to,
							'COLOR'					=> $row['color'],
							'BIG'					=> $row['big'],	
							'BCOLOR'					=> $row['bcolor'],
							'U_MODIFY'				=> $this->u_action . '&amp;action=modify&amp;id=' . $row['id'],
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'delete':
						$sql = 'DELETE
							FROM ' . CALENDAR_EVENT_LIST_TABLE . '
							WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
					break;

					case 'modify':
						$sql_ary = array(
							'APPOINTMENT'			=> utf8_normalize_nfc($request->variable('appointment', '', true)),
							'DESCRIPTION' 			=> utf8_normalize_nfc($request->variable('description', '', true)),
							'LINK'					=> utf8_normalize_nfc($request->variable('link', '', true)),
							'ANNIVERSARY' 			=> $request->variable('anniversary', 0),
							'DATE_FROM' 			=> $from_year.'-'.$from_month.'-'.$from_day,
							'DATE_TO' 				=> $to_year.'-'.$to_month.'-'.$to_day,
							'COLOR'					=> $request->variable('color', ''),
							'BIG'					=> $request->variable('big', 0),
							'BCOLOR'				=> $request->variable('bcolor', ''),
						);
						$sql = 'UPDATE
							' . CALENDAR_EVENT_LIST_TABLE . '
								SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE id = ' . (int) $id;
						$db->sql_query($sql);
					break;
			
					case 'create':
						$sql_ary = array(
							'APPOINTMENT'			=> utf8_normalize_nfc($request->variable('appointment', '', true)),
							'DESCRIPTION' 			=> utf8_normalize_nfc($request->variable('description', '', true)),
							'LINK'					=> utf8_normalize_nfc($request->variable('link', '', true)),
							'ANNIVERSARY' 			=> $request->variable('anniversary', 0),
							'DATE_FROM' 			=> $from_year.'-'.$from_month.'-'.$from_day,
							'DATE_TO' 				=> $to_year.'-'.$to_month.'-'.$to_day,
							'COLOR'					=> $request->variable('color', ''),
							'BIG'					=> $request->variable('big', 0),
							'BCOLOR'				=> $request->variable('bcolor', ''),
						);
						$sql = 'INSERT INTO ' . CALENDAR_EVENT_LIST_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
						$db->sql_query($sql);
					break;
				}

				$sql = 'SELECT *
					FROM ' . CALENDAR_EVENT_LIST_TABLE . '
					ORDER by id';
				$result = $db->sql_query($sql);
				while($row = $db->sql_fetchrow($result))
				{
					$from		= $row['date_from'];	
					$r			= explode('-',$from);
					$from_year	= (int)$r[0];
					$from_month = (int)$r[1];
					$from_day	= (int)$r[2];
					$date_from  = $from_day.'.'.$from_month.'.';
					if ($from_year > 0) $date_from .= $from_year;
				
					$to 		= $row['date_to'];
					$r			= explode('-',$to);
					$to_year	= (int)$r[0];
					$to_month 	= (int)$r[1];
					$to_day		= (int)$r[2];
					$date_to	= '';
					if ($to_day)
					{
						$date_to = $to_day.'.'.$to_month.'.';
						if ($to_year > 0) $date_to .= $to_year;
					}
					if ($row['big'] == 1)
					{
						$b = '<b>';
						$nb= '</b>';
					}
					else
					{
						$b = '';
						$nb= '';
					}
					$this->template->assign_block_vars('calendar_appointment', array(
						'ID' 			=> $row['id'],
						'APPOINTMENT'	=> $b.$row['appointment'].$nb,
						'DESCRIPTION' 	=> $b.$row['description'].$nb,
						'LINK'			=> $b.$row['link'].$nb,
						'ANNIVERSARY' 	=> $b.$user->lang['ACP_CALENDAR_'.$row['anniversary'].''].$nb,
						'DATE_FROM' 	=> $b.$date_from.$nb,
						'DATE_TO' 		=> $b.$date_to.$nb,
						'COLOR'			=> $row['color'],
						'BCOLOR'		=> $row['bcolor'],
						'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['id'],
						'U_DELETE'		=> $this->u_action . '&amp;action=delete&amp;id=' . $row['id'],
					));	
				}
				$this->template->assign_vars(array(
					'U_ACTION'				=> $this->u_action . '&amp;action=add',
					'S_CALENDAR_VERSION'	=> $user->lang['ACP_CALENDAR_TITLE'] . $user->lang['ACP_CALENDAR_VERSION'] . $config['calendar_version'],
				));
			break;

			case 'special_days':
				$this->tpl_name = 'acp_calendar_special_days';

				$action		= $request->variable('action', '');
				$id 		= $request->variable('id', 0);

				switch ($action)
				{
					case 'add':
						$this->template->assign_vars(array(
							'ID' 					=> '',
							'NAME' 					=> '',
							'EASTERN'				=> '',
							'DATE' 					=> '',
							'SHOW_ON' 				=> 0,
							'COLOR'					=> '',
							'BIG'					=> 0,
							'BCOLOR'				=> '',
							'U_MODIFY'				=> $this->u_action . '&amp;action=create&amp;id=' . $id,
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));
					break;

					case 'edit':
						$sql = 'SELECT *
							FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
							WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						
						$eastern = (int)$row['eastern'];
						if ($row['date']) $eastern = '';
						if ($row['name'] == 'Advent')  $eastern = '';
						if ($row['name'] == 'Buß- und Bettag')  $eastern = '';

						$this->template->assign_vars(array(
							'ID' 					=> $row['id'],
							'NAME'					=> $row['name'],
							'EASTERN' 				=> $eastern,
							'DATE' 					=> $row['date'],
							'SHOW_ON' 				=> $row['show_on'],
							'COLOR'					=> $row['color'],
							'BIG'					=> $row['big'],
							'BCOLOR'				=> $row['bcolor'],
							'U_MODIFY'				=> $this->u_action . '&amp;action=modify&amp;id=' . $row['id'],
							'S_EDIT_CALENDAR_EVENT'	=> true,
						));

					break;
		
					case 'delete':
						$sql = 'DELETE
							FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
							WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
					break;

					case 'modify':
					$sql_ary = array(
							'NAME'			=> utf8_normalize_nfc($request->variable('name', '', true)),
							'EASTERN' 		=> $request->variable('eastern', 0),
							'DATE'			=> $request->variable('date', ''),
							'SHOW_ON' 		=> $request->variable('show_on', 0),
							'COLOR'			=> $request->variable('color', ''),
							'BIG'			=> $request->variable('big', 0),
							'BCOLOR'		=> $request->variable('bcolor', ''),
						);
						$sql = 'UPDATE
							' . CALENDAR_SPECIAL_DAYS_TABLE . '
								SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE id = ' . (int) $id;
						$db->sql_query($sql);
					break;
			
					case 'create':
						$sql_ary = array(
							'NAME'			=> utf8_normalize_nfc($request->variable('name', '', true)),
							'EASTERN' 		=> $request->variable('eastern', 0),
							'DATE'			=> $request->variable('date', ''),
							'SHOW_ON' 		=> $request->variable('show_on', 0),
							'COLOR'			=> $request->variable('color', ''),
							'BIG'			=> $request->variable('big', 0),
							'BCOLOR'		=> $request->variable('bcolor', ''),
						);
						$sql = 'INSERT INTO ' . CALENDAR_SPECIAL_DAYS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
						$db->sql_query($sql);
					break;
				}

				$sql = 'SELECT *
					FROM ' . CALENDAR_SPECIAL_DAYS_TABLE . '
					ORDER by id';
				$result = $db->sql_query($sql);
				while($row = $db->sql_fetchrow($result))
				{
					$eastern = (int)$row['eastern'];
					if ($row['date']) $eastern = '';
					if ($row['name'] == 'Advent')  $eastern = '';
					if ($row['name'] == 'Buß- und Bettag')  $eastern = '';
					if ($row['big'] == 1)
					{
						$b = '<b>';
						$nb= '</b>';
					}
					else
					{
						$b = '';
						$nb= '';
					}
					$this->template->assign_block_vars('calendar_special_day', array(
						'ID' 			=> $row['id'],
						'NAME'			=> $b.$row['name'].$nb,
						'EASTERN' 		=> $b.$eastern.$nb,
						'DATE'			=> $b.$row['date'].$nb,
						'SHOW_ON' 		=> $b.$user->lang['ACP_CALENDAR_'.$row['show_on'].''].$nb,
						'COLOR'			=> $row['color'],
						'BCOLOR'		=> $row['bcolor'],
						'U_EDIT'		=> $this->u_action . '&amp;action=edit&amp;id=' . $row['id'],
						'U_DELETE'		=> $this->u_action . '&amp;action=delete&amp;id=' . $row['id'],
					));	
				}
				$this->template->assign_vars(array(
					'U_ACTION'				=> $this->u_action . '&amp;action=add',
					'S_CALENDAR_VERSION'	=> $user->lang['ACP_CALENDAR_TITLE'] . $user->lang['ACP_CALENDAR_VERSION'] . $config['calendar_version'],
				));
			break;
		}
	}
}
