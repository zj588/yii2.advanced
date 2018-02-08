<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: uc.php 36358 2017-01-20 02:05:50Z nemohou $
 */

error_reporting(0);

define('UC_CLIENT_VERSION', '1.6.0');
define('UC_CLIENT_RELEASE', '20170101');

define('API_DELETEUSER', 1);
define('API_RENAMEUSER', 1);
define('API_GETTAG', 1);
define('API_SYNLOGIN', 1);
define('API_SYNLOGOUT', 1);
define('API_UPDATEPW', 1);
define('API_UPDATEBADWORDS', 1);
define('API_UPDATEHOSTS', 1);
define('API_UPDATEAPPS', 1);
define('API_UPDATECLIENT', 1);
define('API_UPDATECREDIT', 1);
define('API_GETCREDIT', 1);
define('API_GETCREDITSETTINGS', 1);
define('API_UPDATECREDITSETTINGS', 1);
define('API_ADDFEED', 1);
define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '1');

define('IN_API', true);
define('CURSCRIPT', 'api');

define('DISCUZ_ROOT', '../');


if(!defined('IN_UC')) {
//	require_once '../source/class/class_core.php';
//
//	$discuz = C::app();
//	$discuz->init();

    require DISCUZ_ROOT.'./config.inc.php';

    $get = $post = array();

    $code = @$_GET['code'];
    parse_str(_authcode($code, 'DECODE', UC_KEY), $get);

    if(time() - $get['time'] > 3600) {
        exit('Authracation has expiried');
    }
    if(empty($get)) {
        exit('Invalid Request');
    }

    include_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';
    $post = xml_unserialize(file_get_contents('php://input'));

    if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings', 'addfeed'))) {
        $uc_note = new uc_note();
        echo call_user_func(array($uc_note, $get['action']), $get, $post);
        exit();
    } else {
        exit(API_RETURN_FAILED);
    }
} else {
    exit;
}

class uc_note {

    var $dbconfig = '';
    var $db = '';
    var $tablepre = '';
    var $appdir = '';

    function _serialize($arr, $htmlon = 0) {
        if(!function_exists('xml_serialize')) {
            include_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';
        }
        return xml_serialize($arr, $htmlon);
    }

    function _construct() {
    }

    function test($get, $post) {
        return API_RETURN_SUCCEED;
    }

//	function deleteuser($get, $post) {
//		global $_G;
//		if(!API_DELETEUSER) {
//			return API_RETURN_FORBIDDEN;
//		}
//		$uids = str_replace("'", '', stripslashes($get['ids']));
//		$ids = array();
//		$ids = array_keys(C::t('common_member')->fetch_all($uids));
//		require_once DISCUZ_ROOT.'./source/function/function_delete.php';
//		$ids && deletemember($ids);
//
//		return API_RETURN_SUCCEED;
//	}
//
//	function renameuser($get, $post) {
//		global $_G;
//
//		if(!API_RENAMEUSER) {
//			return API_RETURN_FORBIDDEN;
//		}
//
//
//
//		$tables = array(
//			'common_block' => array('id' => 'uid', 'name' => 'username'),
//			'common_invite' => array('id' => 'fuid', 'name' => 'fusername'),
//			'common_member_verify_info' => array('id' => 'uid', 'name' => 'username'),
//			'common_mytask' => array('id' => 'uid', 'name' => 'username'),
//			'common_report' => array('id' => 'uid', 'name' => 'username'),
//
//			'forum_thread' => array('id' => 'authorid', 'name' => 'author'),
//			'forum_activityapply' => array('id' => 'uid', 'name' => 'username'),
//			'forum_groupuser' => array('id' => 'uid', 'name' => 'username'),
//			'forum_pollvoter' => array('id' => 'uid', 'name' => 'username'),
//			'forum_post' => array('id' => 'authorid', 'name' => 'author'),
//			'forum_postcomment' => array('id' => 'authorid', 'name' => 'author'),
//			'forum_ratelog' => array('id' => 'uid', 'name' => 'username'),
//
//			'home_album' => array('id' => 'uid', 'name' => 'username'),
//			'home_blog' => array('id' => 'uid', 'name' => 'username'),
//			'home_clickuser' => array('id' => 'uid', 'name' => 'username'),
//			'home_docomment' => array('id' => 'uid', 'name' => 'username'),
//			'home_doing' => array('id' => 'uid', 'name' => 'username'),
//			'home_feed' => array('id' => 'uid', 'name' => 'username'),
//			'home_feed_app' => array('id' => 'uid', 'name' => 'username'),
//			'home_friend' => array('id' => 'fuid', 'name' => 'fusername'),
//			'home_friend_request' => array('id' => 'fuid', 'name' => 'fusername'),
//			'home_notification' => array('id' => 'authorid', 'name' => 'author'),
//			'home_pic' => array('id' => 'uid', 'name' => 'username'),
//			'home_poke' => array('id' => 'fromuid', 'name' => 'fromusername'),
//			'home_share' => array('id' => 'uid', 'name' => 'username'),
//			'home_show' => array('id' => 'uid', 'name' => 'username'),
//			'home_specialuser' => array('id' => 'uid', 'name' => 'username'),
//			'home_visitor' => array('id' => 'vuid', 'name' => 'vusername'),
//
//			'portal_article_title' => array('id' => 'uid', 'name' => 'username'),
//			'portal_comment' => array('id' => 'uid', 'name' => 'username'),
//			'portal_topic' => array('id' => 'uid', 'name' => 'username'),
//			'portal_topic_pic' => array('id' => 'uid', 'name' => 'username'),
//		);
//
//		if(!C::t('common_member')->update($get['uid'], array('username' => $get[newusername])) && isset($_G['setting']['membersplit'])){
//			C::t('common_member_archive')->update($get['uid'], array('username' => $get[newusername]));
//		}
//
//		loadcache("posttableids");
//		if($_G['cache']['posttableids']) {
//			foreach($_G['cache']['posttableids'] AS $tableid) {
//				$tables[getposttable($tableid)] = array('id' => 'authorid', 'name' => 'author');
//			}
//		}
//
//		foreach($tables as $table => $conf) {
//			DB::query("UPDATE ".DB::table($table)." SET `$conf[name]`='$get[newusername]' WHERE `$conf[id]`='$get[uid]'");
//		}
//		return API_RETURN_SUCCEED;
//	}
//
//	function gettag($get, $post) {
//		global $_G;
//		if(!API_GETTAG) {
//			return API_RETURN_FORBIDDEN;
//		}
//		return $this->_serialize(array($get['id'], array()), 1);
//	}

    function synlogin($get, $post) {
        $uid = $get['uid'];
        $username = $get['username'];
        if(!API_SYNLOGIN) {
            return API_RETURN_FORBIDDEN;
        }

        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        _setcookie('bocai_auth', _authcode($uid."\t".$username, 'ENCODE'));
    }

    function synlogout($get, $post) {
        if(!API_SYNLOGOUT) {
            return API_RETURN_FORBIDDEN;
        }

        //note ͬ���ǳ� API �ӿ�
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        _setcookie('bocai_auth', '', -86400 * 365);
    }


}

function _setcookie($var, $value, $life = 0, $prefix = 1) {
    global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
    setcookie(($prefix ? $cookiepre : '').$var, $value,
        $life ? $timestamp + $life : 0, $cookiepath,
        $cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;

    $key = md5($key ? $key : UC_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }

}

function _stripslashes($string)
{
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = _stripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}