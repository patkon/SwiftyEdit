<?php

/**
 * SwiftyEdit backend
 * switch file for section pages
 *
 * variables
 * @var string $sub request
 *
 * global variables
 * @var object $db_content medoo database object
 * @var array $icon icons set in acp/core/icons.php
 * @var array $lang language
 * @var string $languagePack
 * @var string $hidden_csrf_token
 * @var array $se_labels
 */

//prohibit unauthorized access
require 'core/access.php';

switch ($sub) {

    case "edit-pages":
    case "new":
	case "new-pages":
    case "edit":
		$subinc = "pages.edit";
		break;

	case "pages-index":
		$subinc = "pages.index";
		break;

	case "snippets":
		$subinc = "pages.snippets";
		break;

	case "shortcodes":
		$subinc = "pages.shortcodes";
		break;

	case "rss":
		$subinc = "pages.edit_rss";
		break;

    case "pages-list":
	default:
		$subinc = "pages.list";
		break;

}


if($_SESSION['acp_pages'] != "allowed" AND $subinc == "pages.edit" AND $sub == "new"){
	$subinc = "no_access";
}


/* filter pages by keywords $kw_filter */

/* expand filter */
if(isset($_POST['pages_text_filter'])) {
	$_SESSION['pages_text_filter'] = $_SESSION['pages_text_filter'] . ' ' . clean_filename($_POST['pages_text_filter']);
}

/* remove keyword from filter list */
if(isset($_REQUEST['rm_keyword'])) {
	$all_filter = explode(" ", $_SESSION['pages_text_filter']);
    $_SESSION['pages_text_filter'] = '';
	foreach($all_filter as $f) {
		if($_REQUEST['rm_keyword'] == "$f") { continue; }
		if($f == "") { continue; }
		$_SESSION['pages_text_filter'] .= "$f ";
	}
}

if(isset($_SESSION['pages_text_filter']) AND $_SESSION['pages_text_filter'] != "") {
	unset($all_filter);
	$all_filter = explode(" ", $_SESSION['pages_text_filter']);
	
	foreach($all_filter as $f) {
		if($_REQUEST['rm_keyword'] == "$f") { continue; }
		if($f == "") { continue; }
		$btn_remove_keyword .= '<a class="btn btn-sm btn-default" href="acp.php?tn=pages&sub='.$sub.'&rm_keyword='.$f.'">'.$icon['x'].' '.$f.'</a> ';
	}
}

$set_keyword_filter = substr("$set_keyword_filter", 0, -4); // cut the last ' AND'



if(isset($_POST['filter_type'])) {

    $sent_type_filter = clean_filename($_POST['filter_type']);

    if(str_contains($_SESSION['checked_type_string'],"$sent_type_filter")) {
        $type_filter = explode(" ", $_SESSION['checked_type_string']);
        if(($key = array_search($sent_type_filter, $type_filter)) !== false) {
            unset($type_filter[$key]);
        }
        $_SESSION['checked_type_string'] = implode(" ", $type_filter);
    } else {
        $_SESSION['checked_type_string'] = $_SESSION['checked_type_string'] . ' ' . $sent_type_filter;
    }

}

if(isset($_SESSION['checked_type_string']) AND $_SESSION['checked_type_string'] != "") {
    $type_filter = explode(" ", $_SESSION['checked_type_string']);
    $type_filter = array_unique($type_filter);
    $_SESSION['checked_type_string'] = implode(" ", $type_filter);
}


include $subinc.'.php';