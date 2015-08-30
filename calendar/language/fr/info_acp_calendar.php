<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2015 calendar
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
	'ACP_BIRTHDAY_ON_CALENDAR'				=> 'Show birthdays in the calendar ',
	'ACP_CALENDAR_ALLOWED_0'				=> 'non autorisé',
	'ACP_CALENDAR_ALLOWED_1'				=> 'autorisé',
	'ACP_CALENDAR_ANNIVERSARY'				=> 'Anniversaire',
	'ACP_CALENDAR_APPOINTMENT_CREATE'		=> 'Créer rendez-vous',
	'ACP_CALENDAR_APPOINTMENT_DESCRIPTION'	=> 'Description',
	'ACP_CALENDAR_APPOINTMENT_LINK'			=> 'Lien',
    'ACP_CALENDAR_APPOINTMENT_LIST'			=> 'Liste rendez-vous',
    'ACP_CALENDAR_APPOINTMENT_LIST_TEXT'	=> '<br />Les dates créées seront affichées dans la couleur spécifiée sur le calendrier.<br /> 
												<br />Si la date est spécifiée avec l\'année, le rendez-vous ne s\'affiche que 
												l\'année en question. Sauf si vous cochez anniversaire.
												Puis la date sera affichée chaque année avec le nombre des années passées.<br />
												<br />Si vous spécifiez sans année, la date est affichée chaque année.<br /><br />',
	'ACP_CALENDAR_APPOINTMENT_NAME'			=> 'Nom-Rendez-vous',
	'ACP_CALENDAR_BIG'						=> 'Highlighted',
	'ACP_CALENDAR_CHANGE'					=> 'changement',
	'ACP_CALENDAR_COLOR'					=> 'Couleur',
	'ACP_CALENDAR_COLOR_B'					=> 'Couleur de fond',
	'ACP_CALENDAR_DATE'						=> 'Données (J.M.)',
	'ACP_CALENDAR_DATE_FROM'				=> 'De (J.M.AAAA)',
	'ACP_CALENDAR_DATE_TO'					=> 'à (J.M.AAAA)',
	'ACP_CALENDAR_DISPLAYOPTIONS'			=> 'Options d\'affichage',
	'ACP_CALENDAR_EASTER_DAYS'				=> 'les jours jusqu\'à Pâques',
	'ACP_CALENDAR_ENTRIES'					=> 'Entrées de Calendrier',
	'ACP_CALENDAR_EVENT'					=> 'Type d\'événement',
	'ACP_CALENDAR_EVENT_CONFIG'				=> 'Configuration',
	'ACP_CALENDAR_EVENT_CREATE'				=> 'Créer un type d\'événement',
	'ACP_CALENDAR_EVENT_SETTINGS'			=> 'Réglage d\'événements',
	'ACP_CALENDAR_EVENT_SETTINGS_TEXT'		=> '<br />Les types correspondants associés aux événements seront dans les couleurs appropriées
												et dans l\'ordre indiqué ici affiché sur le calendrier.<br /><br />',
	'ACP_CALENDAR_EVENTS'					=> 'événements ',
	'ACP_CALENDAR_FOR_GUESTS'				=> 'Vue de l\'Agenda pour les invités? ',
	'ACP_CALENDAR_FORUM_SETTINGS'			=> 'Configuration des Forums',
	'ACP_CALENDAR_FORUM_SETTINGS_TEXT'		=> '<br />Les rendez vous peuvent seulement être créés dans les forums de couleur verte.<br /><br />',
	'ACP_CALENDAR_SETTINGS'					=> 'Settings',
	'ACP_CALENDAR_INSTRUCTIONS'				=> 'Instructions',
	'ACP_CALENDAR_INSTRUCTIONS_TEXT_0'		=> 'Affichage of de la semaine',
	'ACP_CALENDAR_INSTRUCTIONS_TEXT_1'		=> 'Il y a deux façons de créer des entrées dans le calendrier.<br />
												Si dans le forum approprié l\'ajout de rendez-vous dans le calendrier est autorisée et au moins un type d\'événement 
												a été créé, il n\'y a plus les boites de saisie d\'entrée dans laquelle les événements 
												correspondants peuvent être créés. Le nom apparaît dans le calendrier et ne doit pas 
												être trop long. Le sujet du message apparaît lorsque vous pointez le curseur dans le calendrier sur 
												le nom. Là, vous pouvez fournir des informations supplémentaires. Un clic sur un événement appelle 
												la description du sujet. L\'entrée de la date de fin est facultative pour les événements de plusieurs jours.<br />
												Si le type d\'événement a été créé avec une liste de participants, on peut le voir le voir après l\'envoi de 
												la contribution dans le viewtopic.php. La liste des participants invités et les bots ne seront pas affichées.
												<br /><br />Seuls les dates du calendrier des forums sont affichées, dans lequel le spectateur a
												l\'autorisation de lire.<br /><br />
												La deuxième façon de créer des entrées de calendrier est la liste des événements ici dans le PCA.
												Vous pouvez également créer des rendez-vous avec les liens vers d\'autres pages.<br /><br />',
	'ACP_CALENDAR_NAME'						=> 'Nom',
	'ACP_CALENDAR_NUMBER_OF_WEEKS'			=> 'Nombre de semaines',
	'ACP_CALENDAR_SHOW'						=> 'Voir Jour?',
	'ACP_CALENDAR_SPECIAL_DAYS'				=> 'Jours Spéciaux',
	'ACP_CALENDAR_SPECIAL_DAY_CREATE'		=> 'Créer Jour',
	'ACP_CALENDAR_SPECIAL_DAYS_TEXT'		=> '<br />Les jours fériés mobiles sont fondées sur le dimanche de Pâques.<br />
												Les jours jusqu\'à Pâques seront entrés négatifs.<br /><br />',
	'ACP_CALENDAR_PARTICIPANT'				=> 'Création d\'une liste des participants',
	'ACP_CALENDAR_0'						=> 'Non',
	'ACP_CALENDAR_1'						=> 'Oui',
	'ACP_WEEKBLOCK_TEMPLATE_0'				=> 'Pas d\'affichage',
	'ACP_WEEKBLOCK_TEMPLATE_1'				=> 'Avant en-tête',
	'ACP_WEEKBLOCK_TEMPLATE_2'				=> 'Aprés Navigation',
	'ACP_WEEKBLOCK_TEMPLATE_3'				=> 'Aprés pied de page',
	
	// ACP Modul
	'ACP_CALENDAR_EVENT_CONFIG'				=> 'Configuration',
	'ACP_CALENDAR_EVENT_LIST'				=> 'Liste dates',
	'ACP_CALENDAR_FORUMS_CONFIG'			=> 'Configuration des forums',
	'ACP_CALENDAR_RESET' 					=> 'Réinitialiser',
	'ACP_CALENDAR_SEND' 					=> 'Envoyer',
	'ACP_CALENDAR_SPECIAL_DAY'				=> 'Jour Special',
	'ACP_CALENDAR_TITLE'					=> 'Calendrier',
	'ACP_CALENDAR_TO_DISPLAY'				=> 'Afficher',
	'ACP_CALENDAR_VERSION'					=> ' Version ',

	// ACP Log
	'ACP_CALENDAR_UPDATED' 					=> 'Votre configuration a été mise à jour avec succès',
	'LOG_CALENDAR_UPDATED'					=> '<strong>calendrier mis à jour</strong>',
));