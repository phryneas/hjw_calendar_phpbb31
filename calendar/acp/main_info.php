<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2014 calendar
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hjw\calendar\acp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\hjw\calendar\acp\main_module',
			'title'		=> 'ACP_CALENDAR_TITLE',
			'version'	=> '0.5.1',
			'modes'		=> array(
				'instructions'		=> array('title' => 'ACP_CALENDAR_INSTRUCTIONS',	'auth' => 'acl_a_board', 'cat' => array('ACP_CALENDAR_TITLE')),
				'displayoptions'	=> array('title' => 'ACP_CALENDAR_DISPLAYOPTIONS',	'auth' => 'acl_a_board', 'cat' => array('ACP_CALENDAR_TITLE')),
				'settings'			=> array('title' => 'ACP_CALENDAR_EVENT_CONFIG',	'auth' => 'acl_a_board', 'cat' => array('ACP_CALENDAR_TITLE')),
				'forums_settings'	=> array('title' => 'ACP_CALENDAR_FORUMS_CONFIG',	'auth' => 'acl_a_board', 'cat' => array('ACP_CALENDAR_TITLE')),
				'event_list'		=> array('title' => 'ACP_CALENDAR_EVENT_LIST',		'auth' => 'acl_a_board', 'cat' => array('ACP_CALENDAR_TITLE')),
				'special_days'		=> array('title' => 'ACP_CALENDAR_SPECIAL_DAY',		'auth' => 'acl_a_board', 'cat' => array('ACP_CALENDAR_TITLE')),
			),
		);
	}

	function install()
	{
		global $phpbb_root_path, $phpEx, $db, $user;
		
		// Setup $auth_admin class so we can add permission options
		include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
		$auth_admin = new auth_admin();
		
		// Add permission for manage cvsdb
		$auth_admin->acl_add_option(array(
			'local'		=> array(),
			'global'	=> array('a_add_board')
		));

		$module_data = $this->module();
		
		$module_basename = substr(strstr($module_data['filename'], '_'), 1);

		$result = $db->sql_query('SELECT module_id FROM ' . MODULES_TABLE . " WHERE module_basename = '$module_basename'");
		$module_id = $db->sql_fetchfield('module_id');
		$db->sql_freeresult($result);

		$sql = 'UPDATE ' . MODULES_TABLE . " SET module_auth = 'acl_a_add_board' WHERE module_id = $module_id";
		$db->sql_query($sql);

		$config->set('add_user_version', $module_data['version'], false);

		trigger_error(sprintf($user->lang['ADD_USER_MOD_UPDATED'], $module_data['version']));
	}

}
