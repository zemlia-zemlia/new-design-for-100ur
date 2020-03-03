<?php

// $Rev: 130 $
// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once '../../simple_html_dom_reader.php';
$dom = new simple_html_dom();

// -----------------------------------------------------------------------------
// attribute test
$str = <<<HTML
<div onclick="bar('aa')">foo</div>
HTML;
$dom->load($str);
assert($dom->find('div', 0) == $str);
// -----------------------------------------------
$str = <<<HTML
<div onclick='bar("aa")'>foo</div>
HTML;
$dom->load($str);
assert($dom->find('div', 0) == $str);

// -----------------------------------------------------------------------------
// innertext test
$str = <<<HTML
<html><head></head><body><br><span>foo</span></body></html>
HTML;
$dom->load($str);
assert($dom == $str);
// -----------------------------------------------
$str = <<<HTML
<html><head></head><body><br><span>bar</span></body></html>
HTML;
$dom->find('span', 0)->innertext = 'bar';
assert($dom == $str);
// -----------------------------------------------
$str = <<<HTML
<html><head>ok</head><body><br><span>bar</span></body></html>
HTML;
$dom->find('head', 0)->innertext = 'ok';
assert($dom == $str);

// -----------------------------------------------------------------------------
// outertext test
$str = <<<HTML
<table>
<tr><th>Head1</th><th>Head2</th><th>Head3</th></tr>
<tr><td>1</td><td>2</td><td>3</td></tr>
</table>
HTML;
$dom->load($str);
assert('<tr><th>Head1</th><th>Head2</th><th>Head3</th></tr>' == $dom->find('tr', 0)->outertext);
assert('<tr><td>1</td><td>2</td><td>3</td></tr>' == $dom->find('tr', 1)->outertext);
// -----------------------------------------------
$str = <<<HTML
<table><tr><th>Head1</th><th>Head2</th><th>Head3</th><tr><td>1</td><td>2</td><td>3</td></table>
HTML;
$dom->load($str);
assert('<tr><th>Head1</th><th>Head2</th><th>Head3</th></tr>' == $dom->find('tr', 0)->outertext);
assert('<tr><td>1</td><td>2</td><td>3</td></tr>' == $dom->find('tr', 1)->outertext);

// -----------------------------------------------
$str = <<<HTML
<ul><li><b>li11</b></li><li><b>li12</b></li></ul><ul><li><b>li21</b></li><li><b>li22</b></li></ul>
HTML;
$dom->load($str);
assert('<ul><li><b>li11</b></li><li><b>li12</b></li></ul>' == $dom->find('ul', 0)->outertext);
assert('<ul><li><b>li21</b></li><li><b>li22</b></li></ul>' == $dom->find('ul', 1)->outertext);

// -----------------------------------------------
$str = <<<HTML
<ul><li><b>li11</b></li><li><b>li12</b></li><ul><li><b>li21</b></li><li><b>li22</b></li>
HTML;
//$dom->load($str);
//assert($dom->find('ul', 0)->outertext=='<ul><li><b>li11</b></li><li><b>li12</b></li></ul>');
//assert($dom->find('ul', 1)->outertext=='<ul><li><b>li21</b></li><li><b>li22</b></li></ul>');

// -----------------------------------------------
$str = <<<HTML
<ul><li><b>li11</b><li><b>li12</b></li><ul><li><b>li21</b></li><li><b>li22</b>
HTML;
//$dom->load($str);
//assert($dom->find('ul', 0)->outertext=='<ul><li><b>li11</b><li><b>li12</b></li>');
//assert($dom->find('ul', 1)->outertext=='<ul><li><b>li21</b></li><li><b>li22</b>');

// -----------------------------------------------
$str = <<<HTML
<table>
<tr><th>Head1</th><th>Head2</th><th>Head3</th></tr>
<tr><td>1</td><td>2</td><td>3</td></tr>
</table>
HTML;
$dom->load($str);
assert('<tr><th>Head1</th><th>Head2</th><th>Head3</th></tr>' == $dom->find('tr', 0)->outertext);
assert('<tr><td>1</td><td>2</td><td>3</td></tr>' == $dom->find('tr', 1)->outertext);

// -----------------------------------------------------------------------------
// replacement test
$str = <<<HTML
<div class="class1" id="id2" ><div class="class2">ok</div></div>
HTML;
$dom->load($str);
$es = $dom->find('div');
assert(2 == count($es));
assert('<div class="class2">ok</div>' == $es[0]->innertext);
assert('<div class="class1" id="id2"><div class="class2">ok</div></div>' == $es[0]->outertext);

// test isset
$es[0]->class = 'class_test';
assert(true === isset($es[0]->class));
assert(false === isset($es[0]->okok));

// test replacement
$es[0]->class = 'class_test';
assert('<div class="class_test" id="id2"><div class="class2">ok</div></div>' == $es[0]->outertext);

// test replacement
//$es[0]->tag = 'span';
//assert($es[0]->outertext=='<span class="class_test" id="id2"><div class="class2">ok</div></span>');

// test unset (no more support...)
//$dom = str_get_dom($str);
//$es = $dom->find('div');
//unset($es[0]->class);
//assert($es[0]->outertext=='<div id="id2" ><div class="class2">ok</div></div>');

//$dom->load($str);
//$es = $dom->find('div');
//unset($es[0]->attr['class']);
//assert($es[0]->outertext=='<div id="id2"><div class="class2">ok</div></div>');

// -----------------------------------------------
$str = <<<HTML
<select name=something><options>blah</options><options>blah2</options></select>
HTML;
$dom->load($str);
$e = $dom->find('select[name=something]', 0);
$e->innertext = '';
assert('<select name="something"></select>' == $e->outertext);

// -----------------------------------------------------------------------------
// nested replacement test
$str = <<<HTML
<div class="class0" id="id0"><div class="class1">ok</div></div>
HTML;
$dom->load($str);
$es = $dom->find('div');
assert(2 == count($es));
assert('<div class="class1">ok</div>' == $es[0]->innertext);
assert('<div class="class0" id="id0"><div class="class1">ok</div></div>' == $es[0]->outertext);
assert('ok' == $es[1]->innertext);
assert('<div class="class1">ok</div>' == $es[1]->outertext);

// test replacement
$es[1]->innertext = 'okok';
assert('<div class="class1">okok</div>' == $es[1]->outertext);
assert('<div class="class0" id="id0"><div class="class1">okok</div></div>' == $es[0]->outertext);
//assert($dom=='<div class="class0" id="id0"><div class="class1">okok</div></div>');

$es[1]->class = 'class_test';
assert('<div class="class_test">okok</div>' == $es[1]->outertext);
assert('<div class="class0" id="id0"><div class="class_test">okok</div></div>' == $es[0]->outertext);
//assert($dom=='<div class="class0" id="id0"><div class="class_test">okok</div></div>');

$es[0]->class = 'class_test';
assert('<div class="class_test" id="id0"><div class="class_test">okok</div></div>' == $es[0]->outertext);
//assert($dom=='<div class="class_test" id="id0"><div class="class_test">okok</div></div>');

$es[0]->innertext = 'okokok';
assert('<div class="class_test" id="id0">okokok</div>' == $es[0]->outertext);
//assert($dom=='<div class="class_test" id="id0">okokok</div>');

// -----------------------------------------------------------------------------
// <p> test
$str = <<<HTML
<div class="class0"><p>ok0<a href="#">link0</a></p><div class="class1"><p>ok1<a href="#">link1</a></p></div><div class="class2"></div><p>ok2<a href="#">link2</a></p></div>
HTML;
$dom->load($str);
$es = $dom->find('p');
assert('ok0<a href="#">link0</a>' == $es[0]->innertext);
assert('ok1<a href="#">link1</a>' == $es[1]->innertext);
assert('ok2<a href="#">link2</a>' == $es[2]->innertext);
assert('ok0link0' == $dom->find('p', 0)->plaintext);
assert('ok1link1' == $dom->find('p', 1)->plaintext);
assert('ok2link2' == $dom->find('p', 2)->plaintext);

$count = 0;
foreach ($dom->find('p') as $p) {
    $a = $p->find('a');
    assert($a[0]->innertext == 'link' . $count);
    ++$count;
}

$es = $dom->find('p a');
assert('link0' == $es[0]->innertext);
assert('link1' == $es[1]->innertext);
assert('link2' == $es[2]->innertext);
assert('link0' == $dom->find('p a', 0)->plaintext);
assert('link1' == $dom->find('p a', 1)->plaintext);
assert('link2' == $dom->find('p a', 2)->plaintext);

// -----------------------------------------------------------------------------
// <embed> test
$str = <<<HTML
<EMBED SRC="../graphics/sounds/1812over.mid" HEIGHT="60" WIDTH="144"></EMBED>
HTML;
$dom->load($str);
$e = $dom->find('embed', 0);
assert('../graphics/sounds/1812over.mid' == $e->src);
assert('60' == $e->height);
assert('144' == $e->width);
assert($e == strtolower($str));

// -----------------------------------------------------------------------------
// <pre> test
$str = <<<HTML
<div class="class0" id="id0" >
    <pre>
        <input type=submit name="btnG" value="go" onclick='goto("url0")'>
    </pre>
</div>
HTML;
$dom->load($str);
//assert(count($dom->find('input'))==0);

// -----------------------------------------------------------------------------
// <code> test
$str = <<<HTML
<div class="class0" id="id0" >
    <CODE>
        <input type=submit name="btnG" value="go" onclick='goto("url0")'>
    </CODE>
</div>
HTML;
$dom->load($str);
assert(1 == count($dom->find('code')));
//assert(count($dom->find('input'))==0);

// -----------------------------------------------------------------------------
// tear down
$dom->clear();
unset($dom);
