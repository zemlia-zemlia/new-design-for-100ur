<?php

include_once '../../simple_html_dom.php';

function scraping_IMDB($url)
{
    // create HTML DOM
    $html = file_get_html($url);

    // get title
    $ret['Title'] = $html->find('title', 0)->innertext;

    // get rating
    $ret['Rating'] = $html->find('div[class="general rating"] b', 0)->innertext;

    // get overview
    foreach ($html->find('div[class="info"]') as $div) {
        // skip user comments
        if ('User Comments:' == $div->find('h5', 0)->innertext) {
            return $ret;
        }

        $key = '';
        $val = '';

        foreach ($div->find('*') as $node) {
            if ('h5' == $node->tag) {
                $key = $node->plaintext;
            }

            if ('a' == $node->tag && 'more' != $node->plaintext) {
                $val .= trim(str_replace("\n", '', $node->plaintext));
            }

            if ('text' == $node->tag) {
                $val .= trim(str_replace("\n", '', $node->plaintext));
            }
        }

        $ret[$key] = $val;
    }

    // clean up memory
    $html->clear();
    unset($html);

    return $ret;
}

// -----------------------------------------------------------------------------
// test it!
$ret = scraping_IMDB('http://imdb.com/title/tt0335266/');

foreach ($ret as $k => $v) {
    echo '<strong>' . $k . ' </strong>' . $v . '<br>';
}
