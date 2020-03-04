<?php

// $Rev: 180 $
// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once '../simple_html_dom.php';
$html = new simple_html_dom();

// -----------------------------------------------------------------------------
// DOM tree test
$html->load('');
$e = $html->root;
assert(null == $e->first_child());
assert(null == $e->last_child());
assert(null == $e->next_sibling());
assert(null == $e->prev_sibling());
// -----------------------------------------------
$str = '<div id="div1"></div>';
$html->load($str);

$e = $html->root;
assert('div1' == $e->first_child()->id);
assert('div1' == $e->last_child()->id);
assert(null == $e->next_sibling());
assert(null == $e->prev_sibling());
assert('' == $e->plaintext);
assert('<div id="div1"></div>' == $e->innertext);
assert($e->outertext == $str);
// -----------------------------------------------
$str = <<<HTML
<div id="div1">
    <div id="div10"></div>
    <div id="div11"></div>
    <div id="div12"></div>
</div>
HTML;
$html->load($str);
assert($html == $str);

$e = $html->find('div#div1', 0);
assert(true == isset($e->id));
assert(false == isset($e->_not_exist));
assert('div10' == $e->first_child()->id);
assert('div12' == $e->last_child()->id);
assert(null == $e->next_sibling());
assert(null == $e->prev_sibling());
// -----------------------------------------------
$str = <<<HTML
<div id="div0">
    <div id="div00"></div>
</div>
<div id="div1">
    <div id="div10"></div>
    <div id="div11"></div>
    <div id="div12"></div>
</div>
<div id="div2"></div>
HTML;
$html->load($str);
assert($html == $str);

$e = $html->find('div#div1', 0);
assert('div10' == $e->first_child()->id);
assert('div12' == $e->last_child()->id);
assert('div2' == $e->next_sibling()->id);
assert('div0' == $e->prev_sibling()->id);

$e = $html->find('div#div2', 0);
assert(null == $e->first_child());
assert(null == $e->last_child());

$e = $html->find('div#div0 div#div00', 0);
assert(null == $e->first_child());
assert(null == $e->last_child());
assert(null == $e->next_sibling());
assert(null == $e->prev_sibling());
// -----------------------------------------------
$str = <<<HTML
<div id="div0">
    <div id="div00"></div>
</div>
<div id="div1">
    <div id="div10"></div>
    <div id="div11">
        <div id="div110"></div>
        <div id="div111">
            <div id="div1110"></div>
            <div id="div1111"></div>
            <div id="div1112"></div>
        </div>
        <div id="div112"></div>
    </div>
    <div id="div12"></div>
</div>
<div id="div2"></div>
HTML;
$html->load($str);
assert($html == $str);

assert('div1' == $html->find('#div1', 0)->id);
assert('div10' == $html->find('#div1', 0)->children(0)->id);
assert('div111' == $html->find('#div1', 0)->children(1)->children(1)->id);
assert('div1112' == $html->find('#div1', 0)->children(1)->children(1)->children(2)->id);

// -----------------------------------------------------------------------------
// no value attr test
$str = <<<HTML
<form name="form1" method="post" action="">
    <input type="checkbox" name="checkbox0" checked value="checkbox0">aaa<br>
    <input type="checkbox" name="checkbox1" value="checkbox1">bbb<br>
    <input type="checkbox" name="checkbox2" value="checkbox2" checked>ccc<br>
</form>
HTML;
$html->load($str);
assert($html == $str);

$counter = 0;
foreach ($html->find('input[type=checkbox]') as $checkbox) {
    if (isset($checkbox->checked)) {
        assert($checkbox->value == "checkbox$counter");
        $counter += 2;
    }
}

$counter = 0;
foreach ($html->find('input[type=checkbox]') as $checkbox) {
    if ($checkbox->checked) {
        assert($checkbox->value == "checkbox$counter");
        $counter += 2;
    }
}

$es = $html->find('input[type=checkbox]');
$es[1]->checked = true;
assert('<input type="checkbox" name="checkbox1" value="checkbox1" checked>' == $es[1]->outertext);
$es[0]->checked = false;
assert('<input type="checkbox" name="checkbox0" value="checkbox0">' == $es[0]);
$es[0]->checked = true;
assert('<input type="checkbox" name="checkbox0" checked value="checkbox0">' == $es[0]->outertext);

// -----------------------------------------------------------------------------
// remove attr test
$str = <<<HTML
<input type="checkbox" name="checkbox0">
<input type = "checkbox" name = 'checkbox1' value = "checkbox1">
HTML;

$html->load($str);
assert($html == $str);
$e = $html->find('[name=checkbox0]', 0);
$e->name = null;
assert('<input type="checkbox">' == $e);
$e->type = null;
assert('<input>' == $e);

// -----------------------------------------------
$html->load($str);
assert($html == $str);
$e = $html->find('[name=checkbox0]', 0);
$e->name = null;
assert('<input type="checkbox">' == $e);
$e->type = null;
assert('<input>' == $e);

// -----------------------------------------------
$html->load($str);
assert($html == $str);
$e = $html->find('[name=checkbox1]', 0);
$e->value = null;
assert("<input type = \"checkbox\" name = 'checkbox1'>" == $e);
$e->type = null;
assert("<input name = 'checkbox1'>" == $e);
$e->name = null;
assert('<input>' == $e);

$html->load($str);
assert($html == $str);
$e = $html->find('[name=checkbox1]', 0);
$e->type = null;
assert("<input name = 'checkbox1' value = \"checkbox1\">" == $e);
$e->name = null;
assert('<input value = "checkbox1">' == $e);
$e->value = null;
assert('<input>' == $e);

// -----------------------------------------------------------------------------
// remove no value attr test
$str = <<<HTML
<input type="checkbox" checked name='checkbox0'>
<input type="checkbox" name='checkbox1' checked>
HTML;
$html->load($str);
assert($html == $str);
$e = $html->find('[name=checkbox1]', 0);
$e->type = null;
assert("<input name='checkbox1' checked>" == $e);
$e->name = null;
assert('<input checked>' == $e);
$e->checked = null;
assert('<input>' == $e);

// -----------------------------------------------
$html->load($str);
assert($html == $str);
$e = $html->find('[name=checkbox0]', 0);
$e->type = null;
assert("<input checked name='checkbox0'>" == $e);
$e->name = null;
assert('<input checked>' == $e);
$e->checked = null;
assert('<input>' == $e);

$html->load($str);
assert($html == $str);
$e = $html->find('[name=checkbox0]', 0);
$e->checked = null;
assert("<input type=\"checkbox\" name='checkbox0'>" == $e);
$e->name = null;
assert('<input type="checkbox">' == $e);
$e->type = null;
assert('<input>' == $e);

// -----------------------------------------------------------------------------
// extract text
$str = <<<HTML
<b>okok</b>
HTML;
$html->load($str);
assert($html == $str);
assert('okok' == $html->plaintext);

$str = <<<HTML
<div><b>okok</b></div>
HTML;
$html->load($str);
assert($html == $str);
assert('okok' == $html->plaintext);

$str = <<<HTML
<div><b>okok</b>
HTML;
$html->load($str);
assert($html == $str);
assert('okok' == $html->plaintext);

$str = <<<HTML
<b>okok</b></div>
HTML;
$html->load($str);
assert($html == $str);
assert('okok</div>' == $html->plaintext);

// -----------------------------------------------------------------------------
// old fashion camel naming conventions test
$str = <<<HTML
<input type="checkbox" id="checkbox" name="checkbox" value="checkbox" checked>
<input type="checkbox" id="checkbox1" name="checkbox1" value="checkbox1">
<input type="checkbox" id="checkbox2" name="checkbox2" value="checkbox2" checked>
HTML;
$html->load($str);
assert($html == $str);

assert(true == $html->getElementByTagName('input')->hasAttribute('checked'));
assert(false == $html->getElementsByTagName('input', 1)->hasAttribute('checked'));
assert(false == $html->getElementsByTagName('input', 1)->hasAttribute('not_exist'));

assert($html->find('input', 0)->value == $html->getElementByTagName('input')->getAttribute('value'));
assert($html->find('input', 1)->value == $html->getElementsByTagName('input', 1)->getAttribute('value'));

assert($html->find('#checkbox1', 0)->value == $html->getElementById('checkbox1')->getAttribute('value'));
assert($html->find('#checkbox2', 0)->value == $html->getElementsById('checkbox2', 0)->getAttribute('value'));

$e = $html->find('[name=checkbox]', 0);
assert('checkbox' == $e->getAttribute('value'));
assert(true == $e->getAttribute('checked'));
assert('' == $e->getAttribute('not_exist'));

$e->setAttribute('value', 'okok');
assert('<input type="checkbox" id="checkbox" name="checkbox" value="okok" checked>' == $e);

$e->setAttribute('checked', false);
assert('<input type="checkbox" id="checkbox" name="checkbox" value="okok">' == $e);

$e->setAttribute('checked', true);
assert('<input type="checkbox" id="checkbox" name="checkbox" value="okok" checked>' == $e);

$e->removeAttribute('value');
assert('<input type="checkbox" id="checkbox" name="checkbox" checked>' == $e);

$e->removeAttribute('checked');
assert('<input type="checkbox" id="checkbox" name="checkbox">' == $e);

// -----------------------------------------------
$str = <<<HTML
<div id="div1">
    <div id="div10"></div>
    <div id="div11"></div>
    <div id="div12"></div>
</div>
HTML;
$html->load($str);
assert($html == $str);

$e = $html->find('div#div1', 0);
assert('div10' == $e->firstChild()->getAttribute('id'));
assert('div12' == $e->lastChild()->getAttribute('id'));
assert(null == $e->nextSibling());
assert(null == $e->previousSibling());

// -----------------------------------------------
$str = <<<HTML
<div id="div0">
    <div id="div00"></div>
</div>
<div id="div1">
    <div id="div10"></div>
    <div id="div11">
        <div id="div110"></div>
        <div id="div111">
            <div id="div1110"></div>
            <div id="div1111"></div>
            <div id="div1112"></div>
        </div>
        <div id="div112"></div>
    </div>
    <div id="div12"></div>
</div>
<div id="div2"></div>
HTML;
$html->load($str);
assert($html == $str);

assert(true == $html->getElementById('div1')->hasAttribute('id'));
assert(false == $html->getElementById('div1')->hasAttribute('not_exist'));

assert('div1' == $html->getElementById('div1')->getAttribute('id'));
assert('div10' == $html->getElementById('div1')->childNodes(0)->getAttribute('id'));
assert('div111' == $html->getElementById('div1')->childNodes(1)->childNodes(1)->getAttribute('id'));
assert('div1112' == $html->getElementById('div1')->childNodes(1)->childNodes(1)->childNodes(2)->getAttribute('id'));

assert('div11' == $html->getElementsById('div1', 0)->childNodes(1)->id);
assert('div111' == $html->getElementsById('div1', 0)->childNodes(1)->childNodes(1)->getAttribute('id'));
assert('div1111' == $html->getElementsById('div1', 0)->childNodes(1)->childNodes(1)->childNodes(1)->getAttribute('id'));

// -----------------------------------------------
$str = <<<HTML
<ul class="menublock">
    </li>
        <ul>
            <li>
                <a href="http://www.cyberciti.biz/tips/pollsarchive">Polls Archive</a>
            </li>
        </ul>
    </li>
</ul>
HTML;
$html->load($str);

$ul = $html->find('ul', 0);
assert('ul' === $ul->first_child()->tag);

// -----------------------------------------------
$str = <<<HTML
<ul>
    <li>Item 1 
        <ul>
            <li>Sub Item 1 </li>
            <li>Sub Item 2 </li>
        </ul>
    </li>
    <li>Item 2 </li>
</ul>
HTML;

$html->load($str);
assert($html == $str);

$ul = $html->find('ul', 0);
assert('li' === $ul->first_child()->tag);
assert('li' === $ul->first_child()->next_sibling()->tag);
// -----------------------------------------------------------------------------
// tear down
$html->clear();
unset($html);
