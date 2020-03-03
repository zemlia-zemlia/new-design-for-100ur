<?php

// $Rev: 172 $
// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once '../simple_html_dom.php';
$dom = new simple_html_dom();

// -----------------------------------------------------------------------------
// "\t" or "\n" in tag test
$str = <<<HTML
<img 
class="class0" id="id0" src="src0">
<img
 class="class1" id="id1" src="src1">
<img class="class2" id="id2" src="src2">
HTML;
$dom->load($str);
$e = $dom->find('img');
assert(3 == count($e));

// -----------------------------------------------------------------------------
// std selector test
$str = <<<HTML
<div>
<img class="class0" id="id0" src="src0">
<img class="class1" id="id1" src="src1">
<img class="class2" id="id2" src="src2">
</div>
HTML;
$dom->load($str);

// -----------------------------------------------
// wildcard
assert(1 == count($dom->find('*')));
assert(3 == count($dom->find('div *')));
assert(0 == count($dom->find('div img *')));

assert(1 == count($dom->find(' * ')));
assert(3 == count($dom->find(' div  * ')));
assert(0 == count($dom->find(' div  img  *')));

// -----------------------------------------------
// tag
assert(3 == count($dom->find('img')));
assert(4 == count($dom->find('text')));

// -----------------------------------------------
// class
$es = $dom->find('img.class0');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

$es = $dom->find('.class0');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

// -----------------------------------------------
// index
assert('src0' == $dom->find('img', 0)->src);
assert('src1' == $dom->find('img', 1)->src);
assert('src2' == $dom->find('img', 2)->src);
assert('src0' == $dom->find('img', -3)->src);
assert('src1' == $dom->find('img', -2)->src);
assert('src2' == $dom->find('img', -1)->src);

// -----------------------------------------------
// id
$es = $dom->find('img#id1');
assert(1 == count($es));
assert('src1' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class1" id="id1" src="src1">' == $es[0]->outertext);

$es = $dom->find('#id2');
assert(1 == count($es));
assert('src2' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class2" id="id2" src="src2">' == $es[0]->outertext);

// -----------------------------------------------
// attr
$es = $dom->find('img[src="src0"]');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

$es = $dom->find('img[src=src0]');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

// -----------------------------------------------------------------------------
// wildcard
$es = $dom->find('*[src]');
assert(3 == count($es));

$es = $dom->find('*[src=*]');
assert(3 == count($es));

$es = $dom->find('*[alt=*]');
assert(0 == count($es));

$es = $dom->find('*[src="src0"]');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

$es = $dom->find('*[src=src0]');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

$es = $dom->find('[src=src0]');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

$es = $dom->find('[src="src0"]');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

$es = $dom->find('*#id1');
assert(1 == count($es));
assert('src1' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class1" id="id1" src="src1">' == $es[0]->outertext);

$es = $dom->find('*.class0');
assert(1 == count($es));
assert('src0' == $es[0]->src);
assert('' == $es[0]->innertext);
assert('<img class="class0" id="id0" src="src0">' == $es[0]->outertext);

// -----------------------------------------------------------------------------
// text
$str = <<<HTML
<b>text1</b><b>text2</b>
HTML;
$dom->load($str);
$es = $dom->find('text');
assert(2 == count($es));
assert('text1' == $es[0]->innertext);
assert('text1' == $es[0]->outertext);
assert('text1' == $es[0]->plaintext);
assert('text2' == $es[1]->innertext);
assert('text2' == $es[1]->outertext);
assert('text2' == $es[1]->plaintext);

$str = <<<HTML
<b>text1</b><b>text2</b>
HTML;
$dom->load($str);
$es = $dom->find('b text');
assert(2 == count($es));
assert('text1' == $es[0]->innertext);
assert('text1' == $es[0]->outertext);
assert('text1' == $es[0]->plaintext);
assert('text2' == $es[1]->innertext);
assert('text2' == $es[1]->outertext);
assert('text2' == $es[1]->plaintext);

// -----------------------------------------------------------------------------
// xml namespace test
$str = <<<HTML
<bw:bizy id="date">text</bw:bizy>
HTML;
$dom->load($str);
$es = $dom->find('bw:bizy');
assert(1 == count($es));
assert('date' == $es[0]->id);

// -----------------------------------------------------------------------------
// user defined tag name test
$str = <<<HTML
<div_test id="1">text</div_test>
HTML;
$dom->load($str);
$es = $dom->find('div_test');
assert(1 == count($es));
assert('1' == $es[0]->id);

// -----------------------------------------------
$str = <<<HTML
<div-test id="1">text</div-test>
HTML;
$dom->load($str);
$es = $dom->find('div-test');
assert(1 == count($es));
assert('1' == $es[0]->id);

// -----------------------------------------------
$str = <<<HTML
<div::test id="1">text</div::test>
HTML;
$dom->load($str);
$es = $dom->find('div::test');
assert(1 == count($es));
assert('1' == $es[0]->id);

// -----------------------------------------------
// find all occurrences of id="1" regardless of the tag
$str = <<<HTML
<img class="class0" id="1" src="src0">
<img class="class1" id="2" src="src1">
<div class="class2" id="1">ok</div>
HTML;
$dom->load($str);
$es = $dom->find('[id=1]');
assert(2 == count($es));
assert('img' == $es[0]->tag);
assert('div' == $es[1]->tag);

// -----------------------------------------------------------------------------
// multiple selector test
$str = <<<HTML
<div class="class0" id="id0" ><div class="class1" id="id1"><div class="class2" id="id2">ok</div><div style="st1 st2" id="id3"><span class="id4">ok</span></div></div></div>
HTML;
$dom->load($str);

$es = $dom->find('div');
assert(4 == count($es));
assert('id0' == $es[0]->id);
assert('id1' == $es[1]->id);
assert('id2' == $es[2]->id);

$es = $dom->find('div div');
assert(3 == count($es));
assert('id1' == $es[0]->id);
assert('id2' == $es[1]->id);

$es = $dom->find('div div div');
assert(2 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('.class0 .class1 .class2');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('#id0 #id1 #id2');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('div[id=id0] div[id=id1] div[id=id2]');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('div[id="id0"] div[id="id1"] div[id="id2"]');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('div[id=id0] div[id="id1"] div[id="id2"]');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('div[id="id0"] div[id=id1] div[id="id2"]');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('div[id="id0"] div[id="id1"] div[id=id2]');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find("div[id='id0'] div[id='id1'] div[id='id2']");
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('[id=id0] [id=id1] [id=id2]');
assert(1 == count($es));
assert('id2' == $es[0]->id);

$es = $dom->find('[id] [id] [id]');
assert(2 == count($es));
assert('id2' == $es[0]->id);
assert('id3' == $es[1]->id);

$es = $dom->find('[id=id0] [id=id1] [id=id3]');
assert(1 == count($es));
assert('id3' == $es[0]->id);

$es = $dom->find('[id=id0] [id=id1] [style="st1 st2"]');
assert(1 == count($es));
assert('id3' == $es[0]->id);

$es = $dom->find('[id=id0] [id=id1] [style=st1 st2]');
assert(1 == count($es));
assert('id3' == $es[0]->id);

$es = $dom->find('[id=id0] [id=id1] [style=st1 st2] span[class=id4]');
assert(1 == count($es));
assert('ok' == $es[0]->innertext);

$es = $dom->find('[id=id0] [id=id1] [style="st1 st2"] span[class="id4"]');
assert(1 == count($es));
assert('ok' == $es[0]->innertext);

// -----------------------------------------------
$str = <<<HTML
<table>
    <tr>
        <td>0</td>
        <td>1</td>
    </tr>
</table>
<table>
    <tr>
        <td>2</td>
        <td>3</td>
    </tr>
</table>
HTML;
$dom->load($str);
$es = $dom->find('table td');
assert(4 == count($es));
assert('0' == $es[0]->innertext);
assert('1' == $es[1]->innertext);
assert('2' == $es[2]->innertext);
assert('3' == $es[3]->innertext);

// -----------------------------------------------------------------------------
// multiple selector test 3
$str = <<<HTML
<table>
    <tr>
        <td>
            <table class="hello">
                <tr>
                    <td>0</td>
                    <td>1</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="hello">
    <tr>
        <td>2</td>
        <td>3</td>
    </tr>
</table>
HTML;
$dom = str_get_html($str);
$es = $dom->find('table.hello td');
assert(4 == count($es));
assert('0' == $es[0]->innertext);
assert('1' == $es[1]->innertext);
assert('2' == $es[2]->innertext);
assert('3' == $es[3]->innertext);

// -----------------------------------------------------------------------------
// nested selector test
$str = <<<HTML
<ul>
    <li>0</li>
    <li>1</li>
</ul>
<ul>
    <li>2</li>
    <li>3</li>
</ul>
HTML;
$dom = str_get_html($str);
$es = $dom->find('ul');
assert(2 == count($es));
foreach ($es as $n) {
    $li = $n->find('li');
    assert(2 == count($li));
}

$es = $dom->find('li');
assert(4 == count($es));
assert('0' == $es[0]->innertext);
assert('1' == $es[1]->innertext);
assert('2' == $es[2]->innertext);
assert('3' == $es[3]->innertext);
assert('<li>0</li>' == $es[0]->outertext);
assert('<li>1</li>' == $es[1]->outertext);
assert('<li>2</li>' == $es[2]->outertext);
assert('<li>3</li>' == $es[3]->outertext);

$counter = 0;
foreach ($dom->find('ul') as $ul) {
    foreach ($ul->find('li') as $li) {
        assert($li->innertext == "$counter");
        assert($li->outertext == "<li>$counter</li>");
        ++$counter;
    }
}

// -----------------------------------------------------------------------------
//  [attribute=value] selector
$str = <<<HTML
<input type="radio" name="newsletter" value="Hot Fuzz" />
<input type="radio" name="newsletters" value="Cold Fusion" />
<input type="radio" name="accept" value="Evil Plans" />
HTML;
$dom->load($str);

$es = $dom->find('[name=newsletter]');
assert(1 == count($es));
assert('newsletter' == $es[0]->name);
assert('Hot Fuzz' == $es[0]->value);
assert('<input type="radio" name="newsletter" value="Hot Fuzz" />' == $es[0]->outertext);

$es = $dom->find('[name="newsletter"]');
assert(1 == count($es));
assert('newsletter' == $es[0]->name);
assert('Hot Fuzz' == $es[0]->value);
assert('<input type="radio" name="newsletter" value="Hot Fuzz" />' == $es[0]->outertext);

// -----------------------------------------------------------------------------
//  [attribute!=value] selector
$str = <<<HTML
<input type="radio" name="newsletter" value="Hot Fuzz" />
<input type="radio" name="newsletter" value="Cold Fusion" />
<input type="radio" name="accept" value="Evil Plans" />
HTML;
$dom->load($str);

$es = $dom->find('[name!=newsletter]');
assert(1 == count($es));
assert('accept' == $es[0]->name);
assert('Evil Plans' == $es[0]->value);
assert('<input type="radio" name="accept" value="Evil Plans" />' == $es[0]->outertext);

$es = $dom->find('[name!="newsletter"]');
assert(1 == count($es));
assert('accept' == $es[0]->name);
assert('Evil Plans' == $es[0]->value);
assert('<input type="radio" name="accept" value="Evil Plans" />' == $es[0]->outertext);

$es = $dom->find("[name!='newsletter']");
assert(1 == count($es));
assert('accept' == $es[0]->name);
assert('Evil Plans' == $es[0]->value);
assert('<input type="radio" name="accept" value="Evil Plans" />' == $es[0]->outertext);

// -----------------------------------------------------------------------------
//  [attribute^=value] selector
$str = <<<HTML
<input name="newsletter" />
<input name="milkman" />
<input name="newsboy" />
HTML;
$dom->load($str);

$es = $dom->find('[name^=news]');
assert(2 == count($es));
assert('newsletter' == $es[0]->name);
assert('<input name="newsletter" />' == $es[0]->outertext);
assert('newsboy' == $es[1]->name);
assert('<input name="newsboy" />' == $es[1]->outertext);

$es = $dom->find('[name^="news"]');
assert(2 == count($es));
assert('newsletter' == $es[0]->name);
assert('<input name="newsletter" />' == $es[0]->outertext);
assert('newsboy' == $es[1]->name);
assert('<input name="newsboy" />' == $es[1]->outertext);

// -----------------------------------------------------------------------------
//  [attribute$=value] selector
$str = <<<HTML
<input name="newsletter" />
<input name="milkman" />
<input name="jobletter" />
HTML;
$dom->load($str);

$es = $dom->find('[name$=letter]');
assert(2 == count($es));
assert('newsletter' == $es[0]->name);
assert('<input name="newsletter" />' == $es[0]->outertext);
assert('jobletter' == $es[1]->name);
assert('<input name="jobletter" />' == $es[1]->outertext);

$es = $dom->find('[name$="letter"]');
assert(2 == count($es));
assert('newsletter' == $es[0]->name);
assert('<input name="newsletter" />' == $es[0]->outertext);
assert('jobletter' == $es[1]->name);
assert('<input name="jobletter" />' == $es[1]->outertext);

// -----------------------------------------------------------------------------
//  [attribute*=value] selector
$str = <<<HTML
<input name="man-news" />
<input name="milkman" />
<input name="letterman2" />
<input name="newmilk" />
<div class="foo hello bar"></div>
<div class="foo bar hello"></div>
<div class="hello foo bar"></div>
HTML;
$dom->load($str);

$es = $dom->find('[name*=man]');
assert(3 == count($es));
assert('man-news' == $es[0]->name);
assert('<input name="man-news" />' == $es[0]->outertext);
assert('milkman' == $es[1]->name);
assert('<input name="milkman" />' == $es[1]->outertext);
assert('letterman2' == $es[2]->name);
assert('<input name="letterman2" />' == $es[2]->outertext);

$es = $dom->find('[name*="man"]');
assert(3 == count($es));
assert('man-news' == $es[0]->name);
assert('<input name="man-news" />' == $es[0]->outertext);
assert('milkman' == $es[1]->name);
assert('<input name="milkman" />' == $es[1]->outertext);
assert('letterman2' == $es[2]->name);
assert('<input name="letterman2" />' == $es[2]->outertext);

$es = $dom->find('[class*=hello]');
assert('<div class="foo hello bar"></div>' == $es[0]->outertext);
assert('<div class="foo bar hello"></div>' == $es[1]->outertext);
assert('<div class="hello foo bar"></div>' == $es[2]->outertext);

// -----------------------------------------------------------------------------
// Testcase for '[]' names element
//  normal checkbox
$str = <<<HTML
<input type="checkbox" name="news" value="foo" />
<input type="checkbox" name="news" value="bar">
<input type="checkbox" name="news" value="baz" />
HTML;
$dom->load($str);
$es = $dom->find('[name=news]');
assert(3 == count($es));
assert('news' == $es[0]->name);
assert('foo' == $es[0]->value);
assert('news' == $es[1]->name);
assert('bar' == $es[1]->value);
assert('news' == $es[2]->name);
assert('baz' == $es[2]->value);

// -----------------------------------------------------------------------------
//  with '[]' names checkbox
$str = <<<HTML
<input type="checkbox" name="news[]" value="foo" />
<input type="checkbox" name="news[]" value="bar">
<input type="checkbox" name="news[]" value="baz" />
HTML;
$dom->load($str);
$es = $dom->find('[name=news[]]');
assert(3 == count($es));
assert('news[]' == $es[0]->name);
assert('foo' == $es[0]->value);
assert('news[]' == $es[1]->name);
assert('bar' == $es[1]->value);
assert('news[]' == $es[2]->name);
assert('baz' == $es[2]->value);

// -----------------------------------------------------------------------------
//  with '[]' names checkbox 2
$str = <<<HTML
<input type="checkbox" name="news[foo]" value="foo" />
<input type="checkbox" name="news[bar]" value="bar">
HTML;
$dom->load($str);
$es = $dom->find('[name=news[foo]]');
assert(1 == count($es));
assert('news[foo]' == $es[0]->name);
assert('foo' == $es[0]->value);

// -----------------------------------------------------------------------------
//  with '[]' names 3
$str = <<<HTML
<div name="div[]">
    <input type="checkbox" name="checkbox[]" value="foo" />
</div>
HTML;
$dom->load($str);
$es = $dom->find('div[name=div[]] input[name=checkbox[]]');
assert(1 == count($es));
assert('foo' == $es[0]->value);

// -----------------------------------------------------------------------------
// regular expression
$str = <<<HTML
<div>
<a href="image/one.png">one</a>
<a href="image/two.jpg">two</a>
<a href="/favorites/aaa">three (text)</a>
</div>
HTML;
$dom->load($str);
assert(2 == count($dom->find('a[href^="image/"]')));
assert(1 == count($dom->find('a[href*="/favorites/"]')));

$str = <<<HTML
<html>
    <body>
        <div id="news-id-123">okok</div>
    </bod>
</html>
HTML;
$dom->load($str);
assert(1 == count($dom->find('div[id*=news-id-[0-9]+]')));
assert(1 == count($dom->find('div[id*=/news-id-[0-9]+/i]')));

// -----------------------------------------------------------------------------
// multiple class test
$str = <<<HTML
<div class="hello">should verify</div>
<div class="foo hello bar">should verify</div>
<div class="foo bar hello">should verify</div>
<div class="hello foo bar">should verify</div>
<div class="helloworld">should not verify</div>
<div class="worldhello">should not verify</div>
<div class="worldhelloworld">should not verify</div>
HTML;

$dom->load($str);
$es = $dom->find('[class="hello"],[class*="hello "],[class*=" hello"]');
assert(4 == count($es));
assert('hello' == $es[0]->class);
assert('foo hello bar' == $es[1]->class);
assert('foo bar hello' == $es[2]->class);
assert('hello foo bar' == $es[3]->class);

$es = $dom->find('.hello');
assert(4 == count($es));
assert('hello' == $es[0]->class);
assert('foo hello bar' == $es[1]->class);
assert('foo bar hello' == $es[2]->class);
assert('hello foo bar' == $es[3]->class);

// -----------------------------------------------
$str = <<<HTML
<div class="aa bb"></div>
HTML;
$dom->load($str);
assert(1 == count($dom->find('[class=aa]')));
assert(1 == count($dom->find('[class=bb]')));
assert(1 == count($dom->find('[class="aa bb"]')));
assert(1 == count($dom->find('[class=aa], [class=bb]')));

// -----------------------------------------------------------------------------
// multiple selector test
$str = <<<HTML
<p>aaa</p>
<b>bbb</b>
<i>ccc</i>
HTML;
$dom->load($str);

$es = $dom->find('p,b,i');
assert(3 == count($es));
assert('p' == $es[0]->tag);
assert('b' == $es[1]->tag);
assert('i' == $es[2]->tag);

$es = $dom->find('p, b, i');
assert(3 == count($es));
assert('p' == $es[0]->tag);
assert('b' == $es[1]->tag);
assert('i' == $es[2]->tag);

$es = $dom->find('p,  b  ,   i');
assert(3 == count($es));
assert('p' == $es[0]->tag);
assert('b' == $es[1]->tag);
assert('i' == $es[2]->tag);

$es = $dom->find('p ,b ,i');
assert(3 == count($es));
assert('p' == $es[0]->tag);
assert('b' == $es[1]->tag);
assert('i' == $es[2]->tag);

$es = $dom->find('b,p,i');
assert(3 == count($es));
assert('p' == $es[0]->tag);
assert('b' == $es[1]->tag);
assert('i' == $es[2]->tag);

$es = $dom->find('i,b,p');
assert(3 == count($es));
assert('p' == $es[0]->tag);
assert('b' == $es[1]->tag);
assert('i' == $es[2]->tag);

$es = $dom->find('p,b,i,p,b');
assert(3 == count($es));
assert('p' == $es[0]->tag);
assert('b' == $es[1]->tag);
assert('i' == $es[2]->tag);

// -----------------------------------------------
$str = <<<HTML
<img title="aa" src="src">
<a href="href" title="aa"></a>
HTML;
$dom->load($str);
assert(2 == count($dom->find('a[title], img[title]')));

// -----------------------------------------------------------------------------
// elements that do NOT have the specified attribute
$str = <<<HTML
<img id="aa" src="src">
<img src="src">
HTML;
$dom->load($str);
assert(1 == count($dom->find('img[!id]')));

// -----------------------------------------------------------------------------
//js test
$str = <<<HTML
<a onMouseover="dropdownmenu(this, event, 'messagesmenu')" class="n" href="messagecenter.cfm?key=972489434">foo</a>
HTML;
$dom->load($str);
assert('foo' == $dom->find('a[onMouseover="dropdownmenu(this, event, \'messagesmenu\')"]', 0)->innertext);
assert('foo' == $dom->find("a[onMouseover=dropdownmenu(this, event, 'messagesmenu')]", 0)->innertext);

// -----------------------------------------------------------------------------
//dash test
$str = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';

$dom->load($str);
assert('text/html; charset=utf-8' === $dom->find('meta[http-equiv=content-type]', 0)->content);

// -----------------------------------------------------------------------------
// tear down
$dom->clear();
unset($dom);
