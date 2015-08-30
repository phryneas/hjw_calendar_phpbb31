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
	'BIRTHDAY'					=> 'Anniversaire',
	'CALENDAR'					=> 'Calendrier',
	'CALENDAR_ASK'				=> 'Voulez-vous assister à cet événement?',
	'CALENDAR_COMMENTS'			=> 'Commentaires',
	'CALENDAR_DATE'				=> 'Inscrit le',
	'CALENDAR_ENTER'			=> 'Entrer',
	'CALENDAR_ENTRY'			=> 'Entrée de calendrier',
	'CALENDAR_EVENT'			=> 'Type',
	'CALENDAR_EVENT_NAME'		=> 'Nom',
	'CALENDAR_FROM'				=> 'de',
	'CALENDAR_GROUP'			=> 'Nombre total (1 si vous venez seul)',
	'CALENDAR_MB'				=> 'Peut-être',
	'CALENDAR_NO'				=> 'Non',
	'CALENDAR_NO_ITEMS'			=> 'Jusqu\'à présent aucune inscription',
	'CALENDAR_NUMBER'			=> 'Nombre',
	'CALENDAR_PART'				=> 'Participe',
	'CALENDAR_RESET'			=> 'Reset',
	'CALENDAR_SEND'				=> 'Envoyer',
	'CALENDAR_SETTINGS'			=> 'Paramètres Calendrier',
	'CALENDAR_TITLE'			=> 'Calendrier',
	'CALENDAR_TO'				=> 'à',
	'CALENDAR_USERS'			=> 'Nom',
	'CALENDAR_YES'				=> 'Oui',
	'PARTICIPANTS_LIST'			=> 'Liste des participants',
	'VIEWING_CALENDAR'			=> 'Voir le calendrier',
));