<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2014 calendar
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// Common
$lang = array_merge($lang, array(
	'ACP_BIRTHDAY_ON_CALENDAR'				=> 'Show birthdays in the calendar? ',
	'ACP_CALENDAR_ALLOWED_0'				=> 'not allowed',
	'ACP_CALENDAR_ALLOWED_1'				=> 'allowed',
	'ACP_CALENDAR_ANNIVERSARY'				=> 'Anniversary',
	'ACP_CALENDAR_APPOINTMENT_CREATE'		=> 'Create Appointment',
	'ACP_CALENDAR_APPOINTMENT_DESCRIPTION'	=> 'Description',
	'ACP_CALENDAR_APPOINTMENT_LINK'			=> 'Link',
    'ACP_CALENDAR_APPOINTMENT_LIST'			=> 'Appointment-List',
    'ACP_CALENDAR_APPOINTMENT_LIST_TEXT'	=> '<br />The created dates will be displayed in the specified color on the calendar.<br /> 
												<br />If the date is specified with the year, the appointment is displayed only in the 
												year in question. Unless you make a checkmark for anniversary. 
												Then the Date will be displayed each year with the number of past years.<br />
												<br />If you specify without year the date is shown every year.<br /><br />',
	'ACP_CALENDAR_APPOINTMENT_NAME'			=> 'Appointment-Name',
	'ACP_CALENDAR_BIG'						=> 'Highlighted',
	'ACP_CALENDAR_CHANGE'					=> 'change',
	'ACP_CALENDAR_COLOR'					=> 'Color',
	'ACP_CALENDAR_DATE'						=> 'Datum (D.M.)',
	'ACP_CALENDAR_DATE_FROM'				=> 'From (D.M.YYYY)',
	'ACP_CALENDAR_DATE_TO'					=> 'To (D.M.YYYY)',
	'ACP_CALENDAR_DISPLAYOPTIONS'			=> 'Display Options',
	'ACP_CALENDAR_EASTER_DAYS'				=> 'The Days until resp. after Easter',
	'ACP_CALENDAR_ENTRIES'					=> 'Calendar Entries',
	'ACP_CALENDAR_EVENT'					=> 'Event Type',
	'ACP_CALENDAR_EVENT_CONFIG'				=> 'Config',
	'ACP_CALENDAR_EVENT_CREATE'				=> 'Create Event Type',
	'ACP_CALENDAR_EVENT_SETTINGS'			=> 'Setting Events',
	'ACP_CALENDAR_EVENT_SETTINGS_TEXT'		=> '<br />The corresponding types associated events will in the appropriate colors
												and in the order shown here displayed on the calendar.<br /><br />',
	'ACP_CALENDAR_EVENTS'					=> 'Events',
	'ACP_CALENDAR_FOR_GUESTS'				=> 'Calendar view for guests? ',
	'ACP_CALENDAR_FORUM_SETTINGS'			=> 'Settings Forums',
	'ACP_CALENDAR_FORUM_SETTINGS_TEXT'		=> '<br />Only in the green colored Forums can appointments be created.<br /><br />',
	'ACP_CALENDAR_SETTINGS'					=> 'Settings',
	'ACP_CALENDAR_INSTRUCTIONS'				=> 'Instructions',
	'ACP_CALENDAR_INSTRUCTIONS_TEXT_0'		=> '<br /><br />There are 3 ways to place the weekly review:<br /><br />',
	'ACP_CALENDAR_INSTRUCTIONS_TEXT_1'		=> 'There are two ways to create calendar entries.<br />
												If in the appropriate forum the calendar entry is allowed and at least one event 
												type was created, there are over the posting-box input fields in which the 
												corresponding entries can be made. The name appears in the calendar and should not 
												be too long. The Post subject appears when you point the cursor in the calendar on 
												the name. There you can provide additional information. A click on an event on the 
												calendar calls the descriptive topic. The input of the to-date is optional for multi-day events.<br />
												If the event type was created with participant-list, is to see this after sending 
												the contribution in the viewtopic.php. Participants lists will guests and bots not displayed.
												<br /><br />Only calendar dates from forums are displayed, in which the viewer has 
												permission to read.<br /><br />
												The second way to create calendar entries, is the event list here in the ACP.
												There you can also create appointments with link to other pages.<br /><br />',
	'ACP_CALENDAR_NAME'						=> 'Name',
	'ACP_CALENDAR_NUMBER_OF_WEEKS'			=> 'Number of weeks',
	'ACP_CALENDAR_SHOW'						=> 'View Day?',
	'ACP_CALENDAR_SPECIAL_DAYS'				=> 'Special Days',
	'ACP_CALENDAR_SPECIAL_DAY_CREATE'		=> 'Create Day',
	'ACP_CALENDAR_SPECIAL_DAYS_TEXT'		=> '<br />Moving holidays are based on the Easter Sunday.<br />
												The days until Easter will entered negative.<br /><br />',
	'ACP_CALENDAR_PARTICIPANT'				=> 'Creating a List of Participants',
	'ACP_CALENDAR_0'						=> 'No',
	'ACP_CALENDAR_1'						=> 'Yes',
	'ACP_WEEKBLOCK_TEMPLATE_0'				=> 'No Display',
	'ACP_WEEKBLOCK_TEMPLATE_1'				=> 'Before Header',
	'ACP_WEEKBLOCK_TEMPLATE_2'				=> 'Before Navigation',
	'ACP_WEEKBLOCK_TEMPLATE_3'				=> 'Before Footer',
	
	// ACP Modul
	'ACP_CALENDAR_EVENT_CONFIG'				=> 'Config',
	'ACP_CALENDAR_EVENT_LIST'				=> 'Date-List',
	'ACP_CALENDAR_FORUMS_CONFIG'			=> 'Forums-Config',
	'ACP_CALENDAR_RESET' 					=> 'Reset',
	'ACP_CALENDAR_SEND' 					=> 'Submit',
	'ACP_CALENDAR_SPECIAL_DAY'				=> 'Special Day',
	'ACP_CALENDAR_TITLE'					=> 'Calendar',
	'ACP_CALENDAR_TO_DISPLAY'				=> 'show',
	'ACP_CALENDAR_VERSION'					=> ' Version ',

	// ACP Log
	'ACP_CALENDAR_UPDATED' 					=> 'Deine Konfiguration wurde erfolgreich aktualisiert',
	'LOG_CALENDAR_UPDATED'					=> '<strong>calendar aktualisiert</strong>',
));