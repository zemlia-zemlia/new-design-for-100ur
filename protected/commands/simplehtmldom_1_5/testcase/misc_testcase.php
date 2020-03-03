<?php

// $Rev: 133 $
// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once '../simple_html_dom.php';
$dom = new simple_html_dom();

// -----------------------------------------------------------------------------
// test problem of last emelemt not found
$str = <<<HTML
<img class="class0" id="id0" src="src0">
<img class="class1" id="id1" src="src1">
<img class="class2" id="id2" src="src2">
HTML;

$dom->load($str);
$es = $dom->find('img');
assert(3 == count($es));
assert('src0' == $es[0]->src);
assert('src1' == $es[1]->src);
assert('src2' == $es[2]->src);
assert('' == $es[0]->innertext);
assert('' == $es[1]->innertext);
assert('' == $es[2]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);
assert('<img class="class1" id="id1" src="src1">' == $es[1]->outertext);
assert('<img class="class2" id="id2" src="src2">' == $es[2]->outertext);
assert('src0' == $dom->find('img', 0)->src);
assert('src1' == $dom->find('img', 1)->src);
assert('src2' == $dom->find('img', 2)->src);
assert(null === $dom->find('img', 3));
assert(null === $dom->find('img', 99));
assert($dom->save() == $str);

// -----------------------------------------------------------------------------
// test error tag
$str = <<<HTML
<img class="class0" id="id0" src="src0"><p>p1</p>
<img class="class1" id="id1" src="src1"><p>
<img class="class2" id="id2" src="src2"></a></div>
HTML;

$dom = str_get_html($str);
$es = $dom->find('img');
assert(3 == count($es));
assert('src0' == $es[0]->src);
assert('src1' == $es[1]->src);
assert('src2' == $es[2]->src);

$es = $dom->find('p');
assert('p1' == $es[0]->innertext);
assert($dom == $str);

// -----------------------------------------------------------------------------
// tear down
$dom->clear();
unset($dom);
