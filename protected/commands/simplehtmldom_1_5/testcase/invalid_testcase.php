<?php

// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once '../simple_html_dom.php';
$dom = new simple_html_dom();

// -----------------------------------------------------------------------------
//self-closing tags test
$str = <<<HTML
<hr>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id = 'foo';
assert('<hr id="foo">' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr/>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id = 'foo';
assert('<hr id="foo"/>' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr />
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id = 'foo';
assert('<hr id="foo" />' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id = 'foo';
$e->class = 'bar';
assert('<hr id="foo" class="bar">' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr/>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id = 'foo';
$e->class = 'bar';
assert('<hr id="foo" class="bar"/>' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr />
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id = 'foo';
$e->class = 'bar';
assert('<hr id="foo" class="bar" />' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk=ll>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert('<hr id="foo" kk=ll class="bar">' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk="ll"/>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert('<hr id="foo" kk="ll" class="bar"/>' == $e->outertext);
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk=ll />
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert('<hr id="foo" kk=ll class="bar" />' == $e->outertext);

// -----------------------------------------------
$str = <<<HTML
<div><nobr></div>
HTML;
$dom->load($str);
$e = $dom->find('nobr', 0);
assert('<nobr>' == $e->outertext);

// -----------------------------------------------------------------------------
// optional closing tags test
$str = <<<HTML
<body>
</b><.b></a>
</body>
HTML;
$dom = str_get_html($str);
assert($dom->find('body', 0)->outertext == $str);

// -----------------------------------------------
$str = <<<HTML
<html>
    <body>
        <a>foo</a>
        <a>foo2</a>
HTML;
$dom = str_get_html($str);
assert($dom == $str);
assert('foo2' == $dom->find('html body a', 1)->innertext);

// -----------------------------------------------
$str = <<<HTML
HTML;
$dom = str_get_html($str);
assert($dom == $str);
assert(null === $dom->find('html a', 1));
//assert($dom->find('html a', 1)->innertext=='foo2');

// -----------------------------------------------
$str = <<<HTML
<body>
<div>
</body>
HTML;
$dom = str_get_html($str);
assert($dom == $str);
assert($dom->find('body', 0)->outertext == $str);

// -----------------------------------------------
$str = <<<HTML
<body>
<div> </a> </div>
</body>
HTML;
$dom = str_get_html($str);

assert($dom->find('body', 0)->outertext == $str);

// -----------------------------------------------
$str = <<<HTML
<table>
    <tr>
        <td><b>aa</b>
    <tr>
        <td><b>bb</b>
</table>
HTML;
$dom = str_get_html($str);

assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<table>
<tr><td>1<td>2<td>3
</table>
HTML;
$dom = str_get_html($str);
assert(3 == count($dom->find('td')));
assert('1' == $dom->find('td', 0)->innertext);
assert('<td>1' == $dom->find('td', 0)->outertext);
assert('2' == $dom->find('td', 1)->innertext);
assert('<td>2' == $dom->find('td', 1)->outertext);
assert("3\r\n" == $dom->find('td', 2)->innertext);
assert("<td>3\r\n" == $dom->find('td', 2)->outertext);

// -----------------------------------------------
$str = <<<HTML
<table>
<tr>
    <td><b>1</b></td>
    <td><b>2</b></td>
    <td><b>3</b></td>
</table>
HTML;
$dom = str_get_html($str);
assert(3 == count($dom->find('tr td')));

// -----------------------------------------------
$str = <<<HTML
<table>
<tr><td><b>11</b></td><td><b>12</b></td><td><b>13</b></td>
<tr><td><b>21</b></td><td><b>32</b></td><td><b>43</b></td>
</table>
HTML;
$dom = str_get_html($str);
assert(2 == count($dom->find('tr')));
assert(6 == count($dom->find('tr td')));
assert("<tr><td><b>21</b></td><td><b>32</b></td><td><b>43</b></td>\r\n" == $dom->find('tr', 1)->outertext);
assert("<td><b>21</b></td><td><b>32</b></td><td><b>43</b></td>\r\n" == $dom->find('tr', 1)->innertext);
assert("213243\r\n" == $dom->find('tr', 1)->plaintext);

// -----------------------------------------------
$str = <<<HTML
<p>1
<p>2</p>
<p>3
HTML;
$dom = str_get_html($str);
assert(3 == count($dom->find('p')));
assert("1\r\n" == $dom->find('p', 0)->innertext);
assert("<p>1\r\n" == $dom->find('p', 0)->outertext);
assert('2' == $dom->find('p', 1)->innertext);
assert('<p>2</p>' == $dom->find('p', 1)->outertext);
assert('3' == $dom->find('p', 2)->innertext);
assert('<p>3' == $dom->find('p', 2)->outertext);

// -----------------------------------------------
$str = <<<HTML
<nobr>1
<nobr>2</nobr>
<nobr>3
HTML;
$dom = str_get_html($str);
assert(3 == count($dom->find('nobr')));
assert("1\r\n" == $dom->find('nobr', 0)->innertext);
assert("<nobr>1\r\n" == $dom->find('nobr', 0)->outertext);
assert('2' == $dom->find('nobr', 1)->innertext);
assert('<nobr>2</nobr>' == $dom->find('nobr', 1)->outertext);
assert('3' == $dom->find('nobr', 2)->innertext);
assert('<nobr>3' == $dom->find('nobr', 2)->outertext);

// -----------------------------------------------
$str = <<<HTML
<dl><dt>1<dd>2<dt>3<dd>4</dl>
HTML;
$dom = str_get_html($str);
assert(2 == count($dom->find('dt')));
assert(2 == count($dom->find('dd')));
assert('1' == $dom->find('dt', 0)->innertext);
assert('<dt>1' == $dom->find('dt', 0)->outertext);
assert('3' == $dom->find('dt', 1)->innertext);
assert('<dt>3' == $dom->find('dt', 1)->outertext);
assert('2' == $dom->find('dd', 0)->innertext);
assert('<dd>2' == $dom->find('dd', 0)->outertext);
assert('4' == $dom->find('dd', 1)->innertext);
assert('<dd>4' == $dom->find('dd', 1)->outertext);

// -----------------------------------------------
$str = <<<HTML
<dl id="dl1"><dt>11<dd>12<dt>13<dd>14</dl>
<dl id="dl2"><dt>21<dd>22<dt>23<dd>24</dl>
HTML;
$dom = str_get_html($str);
assert(2 == count($dom->find('#dl1 dt')));
assert(2 == count($dom->find('#dl2  dd')));
assert('<dt>11<dd>12<dt>13<dd>14' == $dom->find('dl', 0)->innertext);
assert('<dt>21<dd>22<dt>23<dd>24' == $dom->find('dl', 1)->innertext);

// -----------------------------------------------
$str = <<<HTML
<ul id="ul1"><li><b>1</b><li><b>2</b></ul>
<ul id="ul2"><li><b>3</b><li><b>4</b></ul>
HTML;
$dom = str_get_html($str);
assert(2 == count($dom->find('ul[id=ul1] li')));

// -----------------------------------------------------------------------------
// invalid test
$str = <<<HTML
<div>
    <div class="class0" id="id0" >
    <img class="class0" id="id0" src="src0">
    </img>
    <img class="class0" id="id0" src="src0">
    </div>
</div>
HTML;
$dom->load($str);
assert(2 == count($dom->find('img')));
assert(2 == count($dom->find('img')));
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<div>
    <div class="class0" id="id0" >
    <span></span>
    </span>
    <span></span>
    </div>
</div>
HTML;

$dom->load($str);
assert(2 == count($dom->find('span')));
assert(2 == count($dom->find('div')));
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<div>
    <div class="class0" id="id0" >
    <span></span>
    <span>
    <span></span>
    </div>
</div>
HTML;
$dom->load($str);
assert(3 == count($dom->find('span')));
assert(2 == count($dom->find('div')));
assert($dom == $str);

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
$dom->load($str);
assert(2 == count($dom->find('ul')));
assert(1 == count($dom->find('ul ul')));
assert(1 == count($dom->find('li')));
assert(1 == count($dom->find('a')));
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<td>
    <div>
        </span>
    </div>
</td>
HTML;
$dom->load($str);
assert(1 == count($dom->find('td')));
assert(1 == count($dom->find('div')));
assert(1 == count($dom->find('td div')));
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<td>
    <div>
        </b>
    </div>
</td>
HTML;
$dom->load($str);
assert(1 == count($dom->find('td')));
assert(1 == count($dom->find('div')));
assert(1 == count($dom->find('td div')));
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<td>
    <div></div>
    </div>
</td>
HTML;
$dom->load($str);
assert(1 == count($dom->find('td')));
assert(1 == count($dom->find('div')));
assert(1 == count($dom->find('td div')));
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<html>
    <body>
        <table>
            <tr>
                foo</span>
                <span>bar</span>
                </span>important
            </tr>
        </table>
    </bod>
</html>
HTML;
$dom->load($str);
assert(1 === count($dom->find('table span')));
assert('bar' === $dom->find('table span', 0)->innertext);
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<td>
    <div>
        <font>
            <b>foo</b>
    </div>
</td>
HTML;
$dom->load($str);
assert(1 == count($dom->find('td div font b')));
assert($dom == $str);

// -----------------------------------------------
$str = <<<HTML
<span style="okokok">
... then slow into 287 
    <i> 
        <b> 
            <font color="#0000CC">(hanover0...more volume between 202 & 53 
            <i> 
                <b> 
                    <font color="#0000CC">(parsippany)</font> 
                </b>
            </i>
            ...then sluggish in spots out to dover chester road 
            <i> 
                <b> 
                    <font color="#0000CC">(randolph)</font> 
                </b> 
            </i>..then traffic light delays out to route 46 
            <i> 
                <b> 
                    <font color="#0000CC">(roxbury)</font> 
                </b> 
            </i>/eb slow into 202 
            <i> 
                <b> 
                    <font color="#0000CC">(morris plains)</font> 
                </b> 
            </i> & again into 287 
            <i> 
                <b> 
                    <font color="#0000CC">(hanover)</font>
                </b> 
            </i> 
</span>. 
<td class="d N4 c">52</td> 
HTML;
$dom->load($str);
assert(0 == count($dom->find('span td')));
assert($dom == $str);

// -----------------------------------------------------------------------------
// invalid '<'
// -----------------------------------------------
$str = <<<HTML
<td><b>test :</b>1 gram but <5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but <5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but <5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but<5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but<5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but<5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but< 5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but< 5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but< 5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but < 5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but < 5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but < 5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5< grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5< grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5< grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 < grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 < grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 < grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 <grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 <grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 <grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);
// -----------------------------------------------
$str = <<<HTML
<td><b>test :</b>1 gram but 5< grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5< grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5< grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but5< grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but5< grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but5< grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 <grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 <grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 <grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5<grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5<grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5<grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 <grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 <grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 <grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

// -----------------------------------------------------------------------------
// invalid '>'
// -----------------------------------------------
$str = <<<HTML
<td><b>test :</b>1 gram but >5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but >5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but >5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but>5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but>5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but>5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but> 5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but> 5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but> 5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but > 5 grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but > 5 grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but > 5 grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5> grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5> grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5> grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 > grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 > grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 > grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 >grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 >grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 >grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);
// -----------------------------------------------
$str = <<<HTML
<td><b>test :</b>1 gram but 5> grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5> grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5> grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but5> grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but5> grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but5> grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 >grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 >grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 >grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5>grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5>grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5>grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

$str = <<<HTML
<td><b>test :</b>1 gram but 5 >grams</td>
HTML;
$dom->load($str);
assert('<b>test :</b>1 gram but 5 >grams' === $dom->find('td', 0)->innertext);
assert('test :1 gram but 5 >grams' === $dom->find('td', 0)->plaintext);
assert($dom == $str);

// -----------------------------------------------------------------------------
// BAD HTML test
$str = <<<HTML
<strong class="see <a href="http://www.oeb.harvard.edu/faculty/girguis/">http://www.oeb.harvard.edu/faculty/girguis/</a>">.</strong></p> 
HTML;
$dom->load($str);
// -----------------------------------------------
$str = <<<HTML
<a href="http://www.oeb.harvard.edu/faculty/girguis\">http://www.oeb.harvard.edu/faculty/girguis/</a>">
HTML;
$dom->load($str);
// -----------------------------------------------
$str = <<<HTML
<strong class="''""";;''""";;\"\''''\"""''''""''>""'''"'" '
HTML;
$dom->load($str);
// -----------------------------------------------------------------------------
// tear down
$dom->clear();
unset($dom);
