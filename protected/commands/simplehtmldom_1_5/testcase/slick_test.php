<?php

// $Rev: 133 $
error_reporting(E_ALL);
include_once '../simple_html_dom.php';

$start = microtime();
list($bu, $bs) = explode(' ', $start);
$html = file_get_html('slickspeed.htm');
list($eu, $es) = explode(' ', microtime());
echo sprintf('parse (%.1f)', ((float) $eu + (float) $es - (float) $bu - (float) $bs) * 1000) . '<br><br>';

assert(1 == count($html->find('#title')));
assert(51 == count($html->find('div')));
assert(51 == count($html->find('div[class]')));
assert(43 == count($html->find('div.example')));
assert(43 == count($html->find('div[class=example]')));
assert(14 == count($html->find('.note')));

assert(43 == count($html->find('div[class^=exa]')));
assert(43 == count($html->find('div[class$=mple]')));
assert(50 == count($html->find('div[class*=e]')));
assert(51 == count($html->find('div[class!=made_up]')));

assert(324 == count($html->find('p')));

echo 'All pass!<br>';
