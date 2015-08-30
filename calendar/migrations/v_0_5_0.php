<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2014 calendar
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_5_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.5.0', '>=');
	}

	static public function depends_on()
	{
			return array('\hjw\calendar\migrations\v_0_4_3');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('calendar_version', '0.5.0')),

			array('module.remove', array(
				'acp',
				'ACP_CALENDAR_TITLE',
				array(
					'module_basename'	=> '\hjw\calendar\acp\main_module',
					'modes'				=> array('instructions','settings','forums_settings','event_list','special_days'),
				),
			)),
			array('module.add', array(
				'acp',
				'ACP_CALENDAR_TITLE',
				array(
					'module_basename'	=> '\hjw\calendar\acp\main_module',
					'modes'				=> array('instructions','displayoptions','settings','forums_settings','event_list','special_days'),
				),
			)),
		);
	}
}