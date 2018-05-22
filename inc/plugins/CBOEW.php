<?php
/**
 * Plugin Name: Capitalize Beginning of Each Word
 * Description: Converts the first character of each word in a thread or post title to uppercase.
 * Author: Brian. ( https://community.mybb.com/user-115119.html )
 * Version: 1.1
 * File: CBOEW.php
**/
 
if(!defined("IN_MYBB"))
{
    	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("datahandler_post_insert_thread", "CBOEW_newthreads");
$plugins->add_hook("datahandler_post_update_thread", "CBOEW_editthreads");

function CBOEW_info()
{
	return array(
		"name"			=> "Capitalize Beginning of Each Word",
		"description"	=> "Converts the first character of each word in a thread or post title to uppercase.",
		"website"		=> "https://community.mybb.com/user-115119.html",
		"author"		=> "Brian.",
		"authorsite"	=> "https://community.mybb.com/user-115119.html",
		"version"		=> "1.1",
		"compatibility" => "16*,18*"
	);
}

function CBOEW_activate()
{
	global $db;
	$CBOEW_settingsgroup = array(
		"gid"    => "NULL",
		"name"  => "CBOEW_settingsgroup",
		"title"      => "Capitalize Beginning of Each Word Settings",
		"description"    => "These options allow you to set the plugin to capitalize the beginning of each word in thread titles.",
		"disporder"    => "1",
		"isdefault"  => "no",
	);

	$db->insert_query("settinggroups", $CBOEW_settingsgroup);
	$gid = $db->insert_id();
	$CBOEW_capitalthreads = array(
		"sid"            => "NULL",
		"name"        => "CBOEW_capitalthreads",
		"title"            => "Capitalize the beginning of each word in thread title\'s",
		"description"    => "If you would like to capitalize the beginning of each word in a thread\'s title, select yes below.",
		"optionscode"    => "yesno",
		"value"        => "1",
		"disporder"        => "1",
		"gid"            => intval($gid),
	);
	
	$db->insert_query("settings", $CBOEW_capitalthreads);
  	rebuild_settings();
}

function CBOEW_newthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['CBOEW_capitalthreads'] == 1)
		{
			$datahandler->thread_insert_data['subject'] = ucwords($datahandler->thread_insert_data['subject']);
		}
}

function CBOEW_editthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['CBOEW_capitalthreads'] == 1 && $datahandler->thread_update_data['subject'])
		{
			$datahandler->thread_update_data['subject'] = ucwords($datahandler->thread_update_data['subject']);
		}
}

function CBOEW_deactivate()
{
	global $db;
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('CBOEW_capitalposts', 'CBOEW_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('CBOEW_capitalthreads', 'CBOEW_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='CBOEW_settingsgroup'");
		rebuildsettings();
}
?>
