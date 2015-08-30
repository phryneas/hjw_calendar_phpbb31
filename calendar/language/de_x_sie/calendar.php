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

// Bot settings

$lang = array_merge($lang, array(
	'BIRTHDAY'					=> 'Geburtstag',
	'CALENDAR'					=> 'Kalender',
	'CALENDAR_ASK'				=> 'Werden Sie an dieser Veranstaltung teilnehmen?',
	'CALENDAR_COMMENTS'			=> 'Bemerkungen',
	'CALENDAR_DATE'				=> 'Eingetragen am',
	'CALENDAR_ENTER'			=> 'Eintragen',
	'CALENDAR_ENTRY'			=> 'Kalender-Eintrag',
	'CALENDAR_EVENT'			=> 'Art',
	'CALENDAR_EVENT_NAME'		=> 'Name',
	'CALENDAR_FROM'				=> 'vom',
	'CALENDAR_GROUP'			=> 'Gesamtzahl (1 wenn Sie alleine kommen)',
	'CALENDAR_MB'				=> 'Eventuell',
	'CALENDAR_NO'				=> 'Nein',
	'CALENDAR_NO_ITEMS'			=> 'Bisher keine Anmeldungen',
	'CALENDAR_NUMBER'			=> 'Anzahl',
	'CALENDAR_PART'				=> 'Teilnahme',
	'CALENDAR_RESET'			=> 'Zurücksetzen',
	'CALENDAR_SEND'				=> 'Absenden',
	'CALENDAR_SETTINGS'			=> 'Kalender-Einstellungen',
	'CALENDAR_TITLE'			=> 'Kalender',
	'CALENDAR_TO'				=> 'bis',
	'CALENDAR_USERS'			=> 'Teilnehmer',
	'CALENDAR_YES'				=> 'Ja',
	'PARTICIPANTS_LIST'			=> 'Teilnehmerliste',
	'VIEWING_CALENDAR'			=> 'Betrachtet den Kalender',
));