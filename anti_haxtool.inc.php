<?php

/**
 * @author JasmanXcrew (ihsana.com)
 * @copyright Ihsana IT Solution 2013
 * @license GPLv2 or later
 * @license URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 */

function is_apache_modules($module = "mod_rewrite") {
    if(function_exists("apache_get_modules")) {
        $apache_modules = implode(",",apache_get_modules());
        if(preg_match("/".$module."/i",$apache_modules)) {
            return true;
        } else {
            return false;
        }
    }
}

function protect_plugins() {
    $ret = "";
    if(isset($_POST['post_anti_haxtool'])) {
        $htaccess = "#BEGIN AntiHaxtool"."\r\n\r\n";
        if($_POST['AuthBypass'] == true) {
            $htaccess .= '#protect AuthBypass
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{HTTP:Accept-Language} ="" [NC]
RewriteRule .* http://%{SERVER_NAME}/?s=%{REQUEST_URI} [R=301,L]
</IfModule>'."\r\n\r\n";
        }
        if($_POST['Webshell'] == true) {
            $htaccess .= '#protect Webshell
<FilesMatch "(c99|r57|c0d3rz|allnet|byroe|sepatu|shell|5h3ll|sh3ll|sh311|backdoor|b4ckd00r|pHpINJ|azrail|ayyildiz|webshell|devshell|b374k)">
Order allow,deny
Deny from all
</FilesMatch>'."\r\n\r\n";
        }
        if($_POST['FileInclution'] == true) {
            $htaccess .= '
#protect FileInclution
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{QUERY_STRING} (\.php|passwd|\.inc) [NC]
RewriteRule .* http://%{SERVER_NAME}/?s=%{REQUEST_URI} [R=301,L]
</IfModule>'."\r\n\r\n";
        }
        if($_POST['XSS'] == true) {
            $htaccess .= '
#protect XSS
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC]
RewriteRule .* http://%{SERVER_NAME}/?s=%{REQUEST_URI} [R=301,L]
</IfModule>'."\r\n\r\n";
        }
        if($_POST['SQLi'] == true) {
            $htaccess .= '
#protect SQL Injection    
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{QUERY_STRING} (from\(select|union|database\(|ascii\() [NC]
RewriteRule .* http://%{SERVER_NAME}/?s=%{REQUEST_URI} [R=301,L]
</IfModule>'."\r\n\r\n";
        }
        if($_POST['NullByte'] == true) {
            $htaccess .= '
#protect NullByte    
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{QUERY_STRING} (%u002e%u002e%u2215|%252e%252e%252f|%00|%5C00|&#|&#x|%09|%0D%0A) [NC]
RewriteRule .* http://%{SERVER_NAME}/?s=%{REQUEST_URI} [R=301,L]
</IfModule>'."\r\n\r\n";
        }
        if($_POST['RCE'] == true) {
            $htaccess .= '
#protect RCE Abitrary   
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{QUERY_STRING} (eval\(|base64\_decode\(|str\_rot13\(|php\_uname\(|system\(|exec\(|passthru\(|popen\(|include|require|wget|lwp\_download|file\_get\_contents\(|fwrite\(|die\(|exit\(|6ae6ba59fcaca6cbc7ec9a75bb51295c|6c6f67696e70776e7a|36655e88aae705fa607da6bb513b3b99) [NC]
RewriteRule .* http://%{SERVER_NAME}/?s=%{REQUEST_URI} [R=301,L]
</IfModule>'."\r\n\r\n";
        }
        $htaccess .= "#END AntiHaxtool"."\r\n\r\n";

        $file_htaccess = dirname(__file__)."./../../.htaccess";
        if(file_exists($file_htaccess)) {
            $fp = fopen($file_htaccess,'r');
            $content = fread($fp,filesize($file_htaccess));
            fclose($fp);
            if(!preg_match("/AntiHaxtool/i",trim($content))) {
                $htaccess .= $content."\r\n\r\n";
                $fp_backup = fopen("backup".$file_htaccess,'w');
                fwrite($fp_backup,$content);
                fclose($fp_backup);
            }
        }

        $fp = fopen($file_htaccess,'w');
        fwrite($fp,$htaccess);
        fclose($fp);
        $ret = '<script type="text/javascript">window.location= "'.ANTI_HAXTOOL_ADMIN_LINK.'"</script>';
    }
    return $ret;
}

function current_htaccess() {
    $file = dirname(__file__)."./../../.htaccess";

    if(file_exists($file)) {
        $fp = fopen($file,'r');
        $content = fread($fp,filesize($file));
        fclose($fp);
    }

    $AuthBypass = '<li><input type="checkbox" value="true" name="AuthBypass" /> AuthBypass</li>'."\r\n";
    $Webshell = '<li><input type="checkbox" value="true" name="Webshell" /> Webshell</li>'."\r\n";
    $FileInclution = '<li><input type="checkbox" value="true" name="FileInclution" /> Local/Remote File Inclution</li>';
    $XSS = '<li><input type="checkbox" value="true" name="XSS" /> XSS Injection</li>'."\r\n";
    $SQLi = '<li><input type="checkbox" value="true" name="SQLi" /> SQL Injection</li>'."\r\n";
    $NullByte = '<li><input type="checkbox" value="true" name="NullByte" /> Directory Travarsal & Null Byte Injection</li>'."\r\n";

    $RCE = '<li><input type="checkbox" value="true" name="RCE" /> Remote Command Excute</li>'."\r\n";

    if(file_exists($file)) {
        if(preg_match("/AuthBypass/i",trim($content))) {
            $AuthBypass = '<li><input type="checkbox" checked="checked" value="true" id="AuthBypass" class="AuthBypass" name="AuthBypass" /> AuthBypass</li>'."\r\n";
        }

        if(preg_match("/Webshell/i",trim($content))) {
            $Webshell = '<li><input type="checkbox"  checked="checked" value="true" id="Webshell" class="Webshell" name="Webshell" /> Webshell</li>'."\r\n";
        }

        if(preg_match("/FileInclution/i",trim($content))) {
            $FileInclution = '<li><input type="checkbox" checked="checked" value="true" name="FileInclution" /> Local/Remote File Inclution</li>';
        }

        if(preg_match("/XSS/i",trim($content))) {
            $XSS = '<li><input type="checkbox" checked="checked" value="true" name="XSS" /> XSS Injection</li>'."\r\n";
        }
        if(preg_match("/SQL Injection/i",trim($content))) {
            $SQLi = '<li><input type="checkbox" checked="checked" value="true" name="SQLi" /> SQL Injection</li>'."\r\n";
        }

        if(preg_match("/NullByte/i",trim($content))) {
            $NullByte = '<li><input type="checkbox"   checked="checked" value="true" name="NullByte" /> Directory Travarsal & Null Byte Injection</li>'."\r\n";
        }

        if(preg_match("/RCE/i",trim($content))) {
            $RCE = '<li><input type="checkbox"  checked="checked" value="true" name="RCE" /> Remote Command Excute</li>'."\r\n";
        }

    }
    return $AuthBypass.$Webshell.$FileInclution.$XSS.$SQLi.$NullByte.$RCE;
}

function get_x_server() {
    $_HTTP_SERVER = $_SERVER;
    unset($_HTTP_SERVER["SERVER_NAME"],$_HTTP_SERVER["HTTP_HOST"],$_HTTP_SERVER['SERVER_SOFTWARE'],$_HTTP_SERVER['REQUEST_URI'],$_HTTP_SERVER['REDIRECT_MIBDIRS'],$_HTTP_SERVER['REDIRECT_MYSQL_HOME'],$_HTTP_SERVER['REDIRECT_OPENSSL_CONF'],$_HTTP_SERVER['REDIRECT_PHP_PEAR_SYSCONF_DIR'],$_HTTP_SERVER['REDIRECT_PHPRC'],$_HTTP_SERVER['REDIRECT_TMP'],$_HTTP_SERVER['REDIRECT_STATUS'],$_HTTP_SERVER['MIBDIRS'],$_HTTP_SERVER['MYSQL_HOME'],$_HTTP_SERVER['OPENSSL_CONF'],$_HTTP_SERVER['PHP_PEAR_SYSCONF_DIR'],$_HTTP_SERVER['PHPRC'],$_HTTP_SERVER['TMP'],$_HTTP_SERVER['PATH'],$_HTTP_SERVER['SystemRoot'],$_HTTP_SERVER['COMSPEC'],$_HTTP_SERVER['PATHEXT'],$_HTTP_SERVER['WINDIR'],$_HTTP_SERVER['SERVER_SIGNATURE'],$_HTTP_SERVER['SERVER_ADDR'],$_HTTP_SERVER['SERVER_PORT'],$_HTTP_SERVER['DOCUMENT_ROOT'],$_HTTP_SERVER['SERVER_ADMIN'],
        $_HTTP_SERVER['SCRIPT_FILENAME'],$_HTTP_SERVER['REMOTE_PORT'],$_HTTP_SERVER['REDIRECT_URL'],$_HTTP_SERVER['GATEWAY_INTERFACE'],$_HTTP_SERVER['SERVER_PROTOCOL'],$_HTTP_SERVER['REQUEST_METHOD'],$_HTTP_SERVER['QUERY_STRING'],$_HTTP_SERVER['SCRIPT_NAME'],$_HTTP_SERVER['PHP_SELF'],$_HTTP_SERVER['REQUEST_TIME'],$_HTTP_SERVER['HTTP_PRAGMA'],$_HTTP_SERVER['HTTP_IF_MODIFIED_SINCE'],$_HTTP_SERVER['HTTP_ACCEPT_ENCODING']);
    $_TRACKER_HTTP_SERVER = @array_keys($_HTTP_SERVER);
    $_TRACKER_HTTP_VALUE = @array_values($_HTTP_SERVER);
    for($i = 0; $i < count($_TRACKER_HTTP_SERVER); $i++) {
        if(!is_array($_TRACKER_HTTP_VALUE[$i])) {
            $_X_SERVER[strtoupper(md5($_TRACKER_HTTP_SERVER[$i]))] = strtoupper(md5($_TRACKER_HTTP_VALUE[$i]));
        }
    }
    return $_X_SERVER;
}

function strtoraw($str) {
    $ret = null;
    $str = strtoupper($str);
    for($i = 0; $i < strlen($str); $i++) {
        $ret .= dechex(ord($str[$i]));
    }
    return $ret;
}

function update_whitelist() {
    global $wpdb;
    if((isset($_GET['id'])) && (is_numeric($_GET['id']) == true)) {
        if($_GET['val'] != '1') {
            $_GET['val'] = '0';
        }
        $query = "UPDATE `".$wpdb->base_prefix."anti_haxtool` SET `anti_haxtool_status` = ".$_GET['val']." WHERE `anti_haxtool_ID` = '".$_GET['id']."' ;";
        $update = $wpdb->query($query);
        return '<script type="text/javascript">window.location= "'.ANTI_HAXTOOL_ADMIN_LINK.'"</script>';
    }
}

function show_hash($hash) {
    $ret = null;
    $len = (32 - strlen($hash));

    for($i = 0; $i < $len; $i++) {
        $ret .= "0";
    }
    return $ret.$hash;
}


$_HTTP_SERVER = get_x_server();
$_TRACKER_HTTP_SERVER = array_keys($_HTTP_SERVER);


//read only for fake browser/crawler
if(!isset($_HTTP_SERVER["C3A4F849BE3640756A7F2F53C491EAE0"])) {
    log_attack("C3A4F849BE3640756A7F2F53C491EAE0");
    unset($_POST,$_FILES);
}


//ceksum from db
$t = 0;

function get_ahi_db() {
    global $wpdb;
    $signature_list = null;
    if(!is_admin())
        $signature_list = $wpdb->get_results("SELECT `anti_haxtool_signature`, `anti_haxtool_type` FROM `".$wpdb->base_prefix."anti_haxtool`");
    return $signature_list;
}

$signature_list = get_ahi_db();

while($t < count($signature_list)) {
    $signature[$t] = strtoupper($signature_list[$t]->anti_haxtool_signature);
    $haxtool_type[$t] = strtoupper($signature_list[$t]->anti_haxtool_type);
    switch($haxtool_type[$t]) {
        case "SVR-UA":
            if(($signature[$t] == $_HTTP_SERVER["FBB136FB8C616E6AE43F65E63B7E795C"]) && (!isset($_HTTP_SERVER["C3A4F849BE3640756A7F2F53C491EAE0"]))) {
                die(_anti_haxtool_visitor_notice($signature[$t]));
                exit();
            }
            break;
        case "SVR-UNIQUE":

            $z = 0;
            while($z < count($_TRACKER_HTTP_SERVER)) {
                if($_TRACKER_HTTP_SERVER[$z] == $signature[$t]) {
                    die(_anti_haxtool_visitor_notice($signature[$t]));
                    exit();
                }
                $z++;
            }
            break;


        case "MASK-UA":
            $ua = strtoupper(strtoraw(strtoupper($_SERVER["HTTP_USER_AGENT"])));

            if((preg_match("/".$signature[$t]."/",$ua)) && (!isset($_HTTP_SERVER["C3A4F849BE3640756A7F2F53C491EAE0"]))) {
                die(_anti_haxtool_visitor_notice($signature[$t]));
                exit();
            }
            break;

        case "SVR-GET":
            $qs = strtoupper(strtoraw(strtoupper($_SERVER["QUERY_STRING"])));

            if((preg_match("/".$signature[$t]."/",$qs))) {
                die(_anti_haxtool_visitor_notice($signature[$t]));
                exit();
            }
            break;

        case "SVR-GET/POST/UA/COOKIES":
            $qs = strtoupper(strtoraw(strtoupper($_SERVER["QUERY_STRING"])));
            $ua = strtoupper(strtoraw(strtoupper($_SERVER["HTTP_USER_AGENT"])));
            $post = strtoupper(strtoraw(strtoupper(implode(',',$_POST))));
            $cookies = strtoupper(strtoraw(strtoupper($_SERVER["HTTP_COOKIE"])));

            if((preg_match("/".$signature[$t]."/",$qs.$ua.$post.$cookies))) {
                die(_anti_haxtool_visitor_notice($signature[$t]));
                exit();
            }
            break;
    }
    $t++;
}

?>