<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2014 calendar
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\controller;

/**
* @ignore
*/

class main
{
	var $u_action;
	
	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;
	
	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	*/

	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->auth = $auth;
	}

	public function display()
	{
		global $db, $user, $cache, $request, $template, $auth, $config, $phpbb_root_path, $phpbb_container, $phpEx;

		$this->root_path = $phpbb_root_path . 'ext/hjw/calendar/';
		$this->db = $db;
		$this->user = $user;
		$this->cache = $cache;
		$this->template = $template;
		$this->config = $config;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ex = $phpEx;

		$this->template->assign_vars(array(
			'S_IN_CALENDAR'				=> true,
		));

		include($this->root_path . 'includes/constants.' . $phpEx);

		$this->user->add_lang_ext('hjw/calendar', 'calendar');

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
				
		$this->template->assign_vars( array(
			'MONDAY' 	=> $user->lang['datetime']['Monday'],
			'TUESDAY' 	=> $user->lang['datetime']['Tuesday'],
			'WEDNESDAY' => $user->lang['datetime']['Wednesday'],
			'THURSDAY' 	=> $user->lang['datetime']['Thursday'],
			'FRIDAY' 	=> $user->lang['datetime']['Friday'],
			'SATURDAY' 	=> $user->lang['datetime']['Saturday'],
			'SUNDAY' 	=> $user->lang['datetime']['Sunday'],
			'MON' 		=> $user->lang['datetime']['Mon'],
			'TUE' 		=> $user->lang['datetime']['Tue'],
			'WED' 		=> $user->lang['datetime']['Wed'],
			'THU' 		=> $user->lang['datetime']['Thu'],
			'FRI' 		=> $user->lang['datetime']['Fri'],
			'SAT' 		=> $user->lang['datetime']['Sat'],
			'SUN' 		=> $user->lang['datetime']['Sun'],
		));	
			
		$today = date("Y-n-j");

		$month = date("n"); 
		$year = date("Y"); 

		if ($request->variable('month', ''))
		{
			$month	=	(int)$request->variable('month', '');
		}
		if ($request->variable('year', ''))
		{
			$year	=	(int)$request->variable('year', '');
		}
		$submit	= (isset($_POST['newmonth'])) ? true : false;
		if ($submit)
		{
			$month	=	$request->variable('newmonth', '');
		}
		$submit	= (isset($_POST['newyear'])) ? true : false;
		if ($submit)
		{
			$year	=	$request->variable('newyear', '');
		}

		include($this->root_path . 'includes/special_days.' . $phpEx);
		
		$previous_year = $year;
		$previous_month = $month-1;
		if ($previous_month == 0) 
		{
			$previous_month = 12;
			$previous_year--;
		}
		$next_year = $year;
		$next_month = $month+1;
		if ($next_month == 13)
		{
			$next_month = 1;
			$next_year++;
		}
		for ($i=1;$i<=12;$i++)
			{
				$s='';if ($i == $month) $s=' selected="selected"';  
				$template->assign_block_vars('month', array(
					'SELECT' =>'<option'.$s.' value="'.$i.'">'.$month_name[$i].'</option>',
				));	
			}
			
			for ($i=$year-2;$i<$year+8;$i++)
			{
				$s='';if ($i == $year) $s=' selected="selected"';  
				$template->assign_block_vars('year', array(
					'SELECT' =>'<option'.$s.' value="'.$i.'">'.$i.'</option>',
				));	
			}

		$wd = date("N", mktime(0, 0, 0, $month, 1, $year));		
		$ml = date("t", mktime(0, 0, 0, $month, 1, $year));		
		$a=$wd-1;

		$end = $ml+$a;
		if ($end/7 > (intval($end/7)))
		{
			$end=(intval($end/7)+1)*7;
		}
		for ($i=1;$i<=$end;$i++)
		{
			$day=$i-$a;
			$noday='';
			if (!($day>0 and $day<=$ml))
			{
				$noday='noday';
			}
			$today_f='';
			if ($today == $year.'-'.$month.'-'.$day)
			{
				$today_f = 'today';
			}
			$bg = 'bg1';
			if (is_int(($i+1)/7))
			{
				$bg = 'sat';
			}
			$tde = '</td>';
			if (is_int($i/7))
			{
				$bg = 'sun';
				$tde = '</td></tr>';
			}

			$td = '';
			if (is_int(($i-1)/7))
			{
				$td = '<tr>';
			}
			$td .= '<td class="'.$bg.' '.$today_f.' '.$noday.'">';

			if ($day>0 and $day<=$ml)
			{
				$template->assign_block_vars('day', array(
					'DATE'	=> $day,
					'TD'	=> $td,
					'TDE'	=> $tde,
				));
				include($this->root_path . 'includes/calendar_event.' . $phpEx);
				
			}
			else
			{
				$this->template->assign_block_vars('day', array(
					'DATE'	=> '', 
					'TD'	=> $td,
					'TDE'	=> $tde,
				));
				$this->template->assign_block_vars('day.cdh', array(
					'HDAY'			=> '</div>',
				));
			}
		}	

		$this->template->assign_vars(array(
			'S_CALENDAR'	=> true,
			'PREVIOUS'		=> append_sid('?month='.$previous_month.'&amp;year='.$previous_year),
			'NEXT'			=> append_sid('?month='.$next_month.'&amp;year='.$next_year),
			'U_ACTION'		=> $this->u_action,
			));

		return $this->helper->render('calendar_body.html', $this->user->lang['CALENDAR_TITLE']);
		return $this->helper->render('posting_options_after.html', $this->user->lang['CALENDAR_TITLE']);
	}
}
