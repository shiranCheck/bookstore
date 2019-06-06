<?php
	require_once('../inc/api/api.php');
	require_once('../inc/api/account.php');
	require_once('../inc/api/gwapi.php');
	$oAPI=IcewarpAPI::instance('');
	$oGWAPI=new MerakGWAPI();
	$oAccount=new MerakAccount();
	define('MSG_EMPTY_NAME','Unnamed contact');
	define('MSG_SHOW',true);
	define('ACCOUNT_AUTO_CREATE',false);
	$delimitter=$oAPI->getProperty("C_OS")?'/':
	'\\';
	define('FDR_DELIMITTER',$delimitter);
	define('OLD_WM_USERPATH','webmail'.FDR_DELIMITTER.'users'.FDR_DELIMITTER);
	define('OLD_WM_CONFIGPATH','webmail'.FDR_DELIMITTER.'config'.FDR_DELIMITTER);
	define('ADDRESS_FILE','address.dat');
	define('GROUPS_FILE','groups.dat');
	define('MAILBOX_FILE','mailbox.dat');
	define('CALENDAR_CFG_FILE','calendar.cfg');
	define('DELIMITER_CHAR',':');
	define('DONE_FILE','wm_cal.convert');
	define('DONE_FILE2','wm_mail.convert');
	define('DONE_FILE3','wm_dl.convert');
	define('GW_DAY_START',8);
	define('GW_DAY_END',16);
	?>