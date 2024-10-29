<?php

/**
 * 
 * Plugin Name: Anti Hacking Tools
 * Plugin URI: http://ticket.ihsana.com/
 * Description: Easy way in protect your blog from hacking tools, ircbot (botnet) and fake browser. To get started just to Click the <strong>Activate</strong> link to the left of this description, Click Tab <strong>Anti Hacking Tools</strong>, then <strong>Checklist</strong> all for best protection and click <strong>Save Changes</strong>. 
 *   
 * Version: 1.0.2
 * Author: JasmanXcrew (ihsana.com)
 * Author URI: http://ihsana.com/
 * 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Too difficult to protect the site from botnets. googlebot, ybot, crawler bot is a botnet. 
 * RSS, feedback tool uses the same technique with a botnet. irc bot, bot scanner or a hacking tool that is also botnet. 
 * but has a different purpose. IRC bot, Bot scanner or a hacking tool is very disturbing, and its presence does not benefit us.
 * from here the idea of making this Plugins.
 * 
 * if the medium and high risk tool will implement the web status refused. 
 * and if a low risk web status can only be read to apply, can not upload or comment.
 * Database on the plugin is taken from an existing tool.
 * 
 * I hope this tool is useful for security and do not interfere with your SEO.
 * 
 * Thank to:
 * - Rizky Ariestiyansyah (Testing)
 * - Forum ExploreCrew
 * - Forum DevilzC0de
 * - And You
 * 
 **/

error_reporting(0);

	define('ANTI_HAXTOOL_EXEC',true);
	define('ANTI_HAXTOOL_VERSION','1.0.2');
	define('ANTI_HAXTOOL_LINK',plugins_url().'/anti-hacking-tools/');
	define('ANTI_HAXTOOL_ADMIN_LINK',get_bloginfo('siteurl').'/wp-admin/plugins.php?page=anti_haxtool');

	function _anti_haxtool_visitor_notice($str)
	{

		$_SERVER["HTTP_USER_AGENT"] = $_SERVER["HTTP_USER_AGENT"].'(googlebot) ';

		$_SERVER['HTTP_CACHE_CONTROL'] = 'no-cache';
		$_SERVER['HTTP_PRAGMA'] == 'no-cache';

		$visitor_notice = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Error: Browser Not Compatible</title>
<style type="text/css">
#box { margin-top: 50px; margin-left: auto; margin-right: auto; padding: 15px; border: 1px solid #E0E2E0; border-radius:5px 5px 5px; width: 500px ; margin-bottom: 50px;}
p,li,a {font-size: 12px; }
li,a {color: #737171; text-decoration: none;}
ul{list-style: square;}
hr {color: #E0E2E0;}
</style>
</head>
<body>
<div id="box"><h2>Error: Browser Not Compatible</h3>
<hr /><p>Sorry, we are not cool enough to be able to support the browser you are using. Try using one of the browsers below:</p>
<ul>
	<li><a href="http://www.getfirefox.com" target="_blank">Mozilla Firefox</a></li>
	<li><a href="http://www.google.com/chrome/" target="_blank">Google Chrome</a></li>
	<li><a href="http://www.apple.com/safari" target="_blank">Safari</a></li>
	<li><a href="http://windows.microsoft.com/en-us/internet-explorer/products/ie/home" target="_blank">Microsoft Internet Explorer</a></li>
</ul><p>Hash Error : '.$str.'.<br/>If false detection please contact <a href="http://facebook.com/jasman.z">Coderz Anti Hacking Tools.</a></p>
</div>
</body>
</html>';
		log_attack($str);
		return $visitor_notice;
}

require_once dirname(__file__).'/anti_haxtool.inc.php';

function _anti_haxtool_page_signature()
	{
		global $wpdb;
		if(is_admin()) echo update_whitelist();
		echo '
    <style type="text/css" scope>
    <!--
    #dashboard_anti_haxtool_htaccess, #anti-haxtool-table,.notice-anti_haxtool { width: 90%;}
    #dashboard_anti_haxtool_htaccess .hndle,#anti-haxtool-table .hndle {padding:10px}
    .notice-anti_haxtool {background-color:#D9ECFF;border:1px solid #339966;color:#008000;padding:5px}
    -->
    </style>
    ';

		echo '   
    <div id="icon-plugins" class="icon32"></div>
    <h2 class="nav-tab-wrapper">';
		if(isset($_GET['tab']) && ($_GET['tab'] == 'report'))
		{
			echo '<a href="'.ANTI_HAXTOOL_ADMIN_LINK.'&tab=manage" class="nav-tab">Manage Anti Hacking Tool</a>';
			echo '<a href="'.ANTI_HAXTOOL_ADMIN_LINK.'&tab=report" class="nav-tab nav-tab-active">Report</a>';
		}
		else
		{
			echo '<a href="'.ANTI_HAXTOOL_ADMIN_LINK.'&tab=manage" class="nav-tab nav-tab-active">Manage Anti Hacking Tool</a>';
			echo '<a href="'.ANTI_HAXTOOL_ADMIN_LINK.'&tab=report" class="nav-tab ">Report</a>';
		}
		echo '</h2>';

		if(isset($_GET['tab']) && ($_GET['tab'] == 'report'))
		{
			echo "<h3>REPORT</h3>";
			$signature_spam = $wpdb->get_results("SELECT anti_haxtool_attacker FROM `".$wpdb->base_prefix."anti_haxtool` WHERE `anti_haxtool_signature` LIKE 'C3A4F849BE3640756A7F2F53C491EAE0'");
			$count_attackers = $wpdb->get_results("SELECT anti_haxtool_name, anti_haxtool_risk, anti_haxtool_signature,anti_haxtool_attacker,anti_haxtool_status FROM `".$wpdb->base_prefix."anti_haxtool` WHERE `anti_haxtool_type` NOT LIKE 'READ-ONLY' AND `anti_haxtool_attacker` != 0 ORDER BY `anti_haxtool_attacker` DESC");

			echo '
    <div class="meta-box-sortables ui-sortable">
    <div style="display: block;" class="postbox" id="dashboard_anti_haxtool_htaccess">
        <h3 class="hndle" ><span>Attacker Report</span></h3>
        <div>
        <ul class="inside">
            <li>Crawler/Spammer : <strong style="color:blue;">'.htmlentities($signature_spam[0]->anti_haxtool_attacker).'</strong> (GOOD BOT)</li>';

			foreach($count_attackers as $count_attacker)
			{
				echo '<li>'.$count_attacker->anti_haxtool_name.' : <strong style="color:blue;">'.htmlentities($count_attacker->anti_haxtool_attacker).'</strong></li>';

			}
           
			echo '<ul></div>
    </div>
    </div>
    ';
			echo "<p class='notice-anti_haxtool'><strong>Read Only</strong> mode for spammer and Crawler, Post Comment and Upload not allowed. 
    <br/>Don't worry! Googlebot, ybot, bingbot, alexa bot, AhrefsBot, Ezooms, ia_archiver and etc <strong>NO NEED COMMENT YOUR BLOG</strong></p><br/>";

		}
		else
		{
    
   $signature_list = $wpdb->get_results("SELECT anti_haxtool_ID,anti_haxtool_signature_id, anti_haxtool_name, anti_haxtool_risk, anti_haxtool_signature,anti_haxtool_attacker,anti_haxtool_status FROM `".$wpdb->base_prefix."anti_haxtool` WHERE `anti_haxtool_type` NOT LIKE 'READ-ONLY' ORDER BY `anti_haxtool_attacker` DESC");
			
    
    if(is_apache_modules("mod_rewrite")!=true){
        $mod_rewrite_notice = '<div class="update-nag"><strong>mod_rewrite.c</strong> must available!</div>';
    }else{
        $mod_rewrite_notice = '<div class="notice-anti_haxtool">Checklist all for best security</div>';
    }
    
    echo '
    <div class="meta-box-sortables ui-sortable">
    <div style="display: block;" class="postbox" id="dashboard_anti_haxtool_htaccess">
        <h3 class="hndle" ><span>Protect Plugins and Themes</span></h3>
        <div class="inside">
            '.$mod_rewrite_notice.'
            <p>Protect Plugins and Themes using <strong>.htaccess</strong></p>
            <form method="post" action="'.ANTI_HAXTOOL_ADMIN_LINK.'&protect=plugins">
                <ul>
                	'.current_htaccess().'               
                </ul>
            <input type="submit" class="button-primary" value="Save Changes" name="post_anti_haxtool"/>
            </form>
        </div>
        
    </div>
    </div>
    ';
    

echo '
<div>
<div style="display: block;" class="postbox" id="anti-haxtool-table">
<h3 class="hndle" ><span>Protect Wordpress</span></h3>
<div class="inside">

<div class="notice-anti_haxtool">Protect your blog from Third Party Scanning.</div>  
<ul>
<li><img src="'.ANTI_HAXTOOL_LINK.'/images/on.gif" width="12" height="12" /> : Status ON </li>
<li></strong><img src="'.ANTI_HAXTOOL_LINK.'/images/off.gif" width="12" height="12" /> : Status OFF</li>
</ul>     

<table class="wp-list-table widefat">
<thead>
<tr>
	<th><strong>Tools</strong></th>
	<th><strong>Signature</strong></th>
	<th><strong>Risk</strong></th>
    <th><strong>Attacker</strong></th>
</tr>
</thead>
<tfoot>
<tr>
	<th><strong>Tools</strong></th>
	<th><strong>Signature</strong></th>
	<th><strong>Risk</strong></th>
    <th><strong>Attacker</strong></th>
</tr>
</tfoot>

<tbody id="the-list">
';
			foreach($signature_list as $signature)
			{
				if(htmlentities($signature->anti_haxtool_attacker) > 0)
				{
					$anti_haxtool_color = "red";
				}
				else
				{
					$anti_haxtool_color = "blue";
				}

				if(htmlentities($signature->anti_haxtool_status) == 1)
				{
					$anti_haxtool_status = '<a href="'.ANTI_HAXTOOL_ADMIN_LINK.'&id='.htmlentities($signature->anti_haxtool_ID).'&val=0" title="Status Enable, Click for disable"><img src="'.ANTI_HAXTOOL_LINK.'/images/on.gif" width="12" height="12" /></a>';
				}
				else
				{
					$anti_haxtool_status = '<a href="'.ANTI_HAXTOOL_ADMIN_LINK.'&id='.htmlentities($signature->anti_haxtool_ID).'&val=1" title="Status Disable, Click for Enable"><img src="'.ANTI_HAXTOOL_LINK.'/images/off.gif" width="12" height="12" /></a>';
				}

				echo '<tr>';
				echo '<td>'.$anti_haxtool_status.' '.htmlentities($signature->anti_haxtool_name).'</td>';
				echo '<td><code title="'."ID: ".htmlentities($signature->anti_haxtool_signature_id).'" >'.htmlentities(show_hash($signature->anti_haxtool_signature)).'</code></td>';
				echo '<td>'.htmlentities($signature->anti_haxtool_risk).'</td>';
				echo '<td style="color:'.$anti_haxtool_color.';">'.htmlentities($signature->anti_haxtool_attacker).'</td>';
				echo '</tr>';
			}

			echo protect_plugins();

			echo '</tbody>
    </table>

    </div>
     </div>
    </div>
    ';


		}
	}
    
	function log_attack($hash)
	{
		global $wpdb;
		$query = "UPDATE `".$wpdb->base_prefix."anti_haxtool` SET `anti_haxtool_attacker` = anti_haxtool_attacker+1 WHERE `anti_haxtool_signature` = '".$hash."' ;";
		$update = $wpdb->query($query);
		return $update;
	}

	function _anti_hax_tool_menu()
	{
		add_plugins_page('Anti Hacking Tools Signature List','Anti Hacking Tools','read','anti_haxtool','_anti_haxtool_page_signature');

	}
    
	function _anti_haxtool_push_mail($email)
	{
		$subject = 'Anti Hacking Tool';
		mail($email,$subject,"ANTI HAXTOOL keep track of suspicious activity in your blog.");
		return true;
	}
    
	function file_mysql_query()
	{
		$file_db = dirname(__file__)."/anti_haxtool.db.php";
		if(file_exists($file_db))
		{
			$open_file_db = fopen($file_db,"r");
			$str_file_db = explode("\r\n",fread($open_file_db,filesize($file_db)));
			fclose($open_file_db);
		}
		$file_db_arr = json_decode($str_file_db[1],1);
		$conv_mysql_query_value = null;
		for($t = 0; $t < count($file_db_arr); $t++)
		{
			if($t < count($file_db_arr) - 1)
			{
				$conv_mysql_query_value .= "( '".$file_db_arr[$t]['anti_haxtool_signature_id']."', '".$file_db_arr[$t]['anti_haxtool_name']."', '".$file_db_arr[$t]['anti_haxtool_signature']."','".$file_db_arr[$t]['anti_haxtool_type']."', '".$file_db_arr[$t]['anti_haxtool_risk']."', '".$file_db_arr[$t]['anti_haxtool_status']."'),\r\n";
			}
			else
			{
				$conv_mysql_query_value .= "( '".$file_db_arr[$t]['anti_haxtool_signature_id']."', '".$file_db_arr[$t]['anti_haxtool_name']."', '".$file_db_arr[$t]['anti_haxtool_signature']."','".$file_db_arr[$t]['anti_haxtool_type']."', '".$file_db_arr[$t]['anti_haxtool_risk']."', '".$file_db_arr[$t]['anti_haxtool_status']."')\r\n";
			}
		}
		$conv_mysql_query = "INSERT INTO `wp_anti_haxtool` (`anti_haxtool_signature_id`, `anti_haxtool_name`, `anti_haxtool_signature`, `anti_haxtool_type`, `anti_haxtool_risk`,`anti_haxtool_status`) VALUES \r\n".$conv_mysql_query_value;
		return $conv_mysql_query;
	}

	function _anti_haxtool_activate()
	{
		global $wpdb;
		if(is_admin()) $query_create_table = "CREATE TABLE IF NOT EXISTS `".$wpdb->base_prefix."anti_haxtool` (
              `anti_haxtool_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `anti_haxtool_signature_id` text NOT NULL,
              `anti_haxtool_name` text NOT NULL,
              `anti_haxtool_signature` text NOT NULL,
              `anti_haxtool_type` text NOT NULL,
              `anti_haxtool_risk` text NOT NULL,
              `anti_haxtool_attacker` DECIMAL NOT NULL DEFAULT '0',
              `anti_haxtool_status` BOOLEAN NOT NULL DEFAULT '1',
              PRIMARY KEY (`anti_haxtool_ID`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$create_table = $wpdb->query($query_create_table);
		$create_signature_row = $wpdb->query(file_mysql_query());
		return $create_table;
	}

	function _anti_haxtool_deactivate()
	{
		global $wpdb;
		if(is_admin()) $query_drop_table = "DROP TABLE `".$wpdb->base_prefix."anti_haxtool`";
		$drop_table = $wpdb->query($query_drop_table);
		return $drop_table;
	}

	if(is_admin())
	{
		add_action('admin_menu','_anti_hax_tool_menu');
	}

	register_activation_hook(__file__,'_anti_haxtool_activate');
	register_deactivation_hook(__file__,'_anti_haxtool_deactivate');
?>