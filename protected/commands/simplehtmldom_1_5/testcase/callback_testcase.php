<?php

// $Rev: 179 $
// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once '../simple_html_dom.php';
$dom = new simple_html_dom();

// -----------------------------------------------------------------------------
// test problem of last emelemt not found
$str = <<<HTML
<img src="src0"><p>foo</p><img src="src2">
HTML;

function callback_1($e)
{
    if ('img' === $e->tag) {
        $e->outertext = '';
    }
}

$dom->load($str);
$dom->set_callback('callback_1');
assert('<p>foo</p>' == $dom);

// -----------------------------------------------
// innertext test
function callback_2($e)
{
    if ('p' === $e->tag) {
        $e->innertext = 'bar';
    }
}

$dom->load($str);
$dom->set_callback('callback_2');
assert('<img src="src0"><p>bar</p><img src="src2">' == $dom);

// -----------------------------------------------
// attributes test
function callback_3($e)
{
    if ('img' === $e->tag) {
        $e->src = 'foo';
    }
}

$dom->load($str);
$dom->set_callback('callback_3');
assert('<img src="foo"><p>foo</p><img src="foo">' == $dom);

function callback_4($e)
{
    if ('img' === $e->tag) {
        $e->id = 'foo';
    }
}

$dom->set_callback('callback_4');
assert('<img src="foo" id="foo"><p>foo</p><img src="foo" id="foo">' == $dom);

// -----------------------------------------------
// attributes test2
//$dom = str_get_dom($str);
$dom->load($str);
$dom->remove_callback();
$dom->find('img', 0)->id = 'foo';
assert('<img src="src0" id="foo"><p>foo</p><img src="src2">' == $dom);

function callback_5($e)
{
    if ('src0' === $e->src) {
        unset($e->id);
    }
}

$dom->set_callback('callback_5');
assert($dom == $str);

// -----------------------------------------------------------------------------
// tear down
$dom->clear();
unset($dom);
