<?php

function sanitize($content) {
$content = trim($content ?? '');
$content = htmlentities($content ?? '', ENT_QUOTES, "UTF-8");
return ($content);
}

function sanitize_ucfirst($content) {
$content = trim($content ?? '');
$content = ucfirst($content ?? '');
$content = htmlentities($content ?? '', ENT_QUOTES, "UTF-8");
return ($content);
}

function sanitize_strtoupper($content) {
$content = trim($content ?? '');
$content = strtoupper($content ?? '');
$content = htmlentities($content ?? '', ENT_QUOTES, "UTF-8");
return ($content);
}

function bb($text) {
$text = trim($text ?? '');
$text = htmlentities($text ?? '', ENT_QUOTES, "UTF-8");
/* [B][/B]*/ $text = preg_replace("/\[b\](.*)\[\/b\]/Usi", "<b>\\1</b>", $text);
/* [H][/H]*/ $text = preg_replace("/\[H\](.*)\[\/H\]/Usi", "<H2>\\1</H2>", $text);
/* [IMG_B][/IMG_B]*/ $text = preg_replace("/\[img_b\](.*)\[\/img_b\]/Usi", '<img src="\\1" class="responsive_b">', $text);
/* [IMG_S][/IMG_S]*/ $text = preg_replace("/\[img_s\](.*)\[\/img_s\]/Usi", '<img src="\\1" class="responsive_s">', $text);
/* [A][/A]*/ $text = preg_replace("/\[A\](.*)\[\/A\]/Usi", '<p class="p_mb">\\1</p>', $text);
/* [LIST][/LIST]*/ $text = preg_replace("/\[LIST\](.*)\[\/LIST\]/Usi", "<ul>\\1</ul>", $text);
/* [LIST_ITEM][/LIST_ITEM]*/ $text = preg_replace("/\[LIST_ITEM\](.*)\[\/LIST_ITEM\]/Usi", '<li class="li_un">\\1</li>', $text);
return ($text ?? '');
}

function autoload_classes() {
spl_autoload_register(function($class) {
$file = 'classes/'. $class. '.php';
if (file_exists($file)) {
require_once $file;
}
});
}

?>
