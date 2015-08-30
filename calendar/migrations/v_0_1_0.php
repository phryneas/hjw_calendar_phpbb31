<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2014 calendar
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace hjw\calendar\migrations;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_version']) && version_compare($this->config['calendar_version'], '0.1.0', '>=');
	}

	static public function depends_on()
	{
			return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'calendar_event'	=> array(
					'COLUMNS'				=> array(
						'id'				=> array('UINT', null, 'auto_increment'),
						'event'				=> array('VCHAR:255', ''),
						'color'				=> array('VCHAR:16', ''),
						'participants'		=> array('INT:1', 0),
					),					
					'PRIMARY_KEY'	=> 'id',
				),
				$this->table_prefix . 'calendar'	=> array(
					'COLUMNS'				=> array(
						'post_id'			=> array('UINT', null, ''),
						'event_id'			=> array('UINT', null, ''),
						'event_name'		=> array('VCHAR:255', ''),
						'date_from'			=> array('VCHAR:10', ''),
						'date_to'			=> array('VCHAR:10', ''),
					),					
					'PRIMARY_KEY'	=> 'post_id',
				),
				$this->table_prefix . 'calendar_participants'	=> array(
					'COLUMNS'				=> array(
						'post_id'			=> array('UINT', null, ''),
						'user_id'			=> array('UINT', null, ''),
						'number'			=> array('UINT', null, ''),
						'participants'		=> array('VCHAR:255', ''),
						'comments'			=> array('VCHAR:255', ''),
						'date'				=> array('VCHAR:10', ''),
					),					
				),
				$this->table_prefix . 'calendar_forums'	=> array(
					'COLUMNS'				=> array(
						'forum_id'			=> array('UINT', null, ''),
						'allowed'			=> array('INT:1', 0),
					),					
					'PRIMARY_KEY'	=> 'forum_id',
				),
			
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'calendar_event',
				$this->table_prefix . 'calendar',
				$this->table_prefix . 'calendar_participants',
				$this->table_prefix . 'calendar_forums',
			),
		);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('calendar_version', '0.1.0')),
		);
	}
}