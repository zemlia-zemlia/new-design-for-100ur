<?php

// $Rev: 174 $
error_reporting(E_ALL);
include_once '../simple_html_dom.php';

$dir = './html/';

$files = [
    ['name' => 'empty.htm',          'url' => ''],
    ['name' => 'smarty_1.htm',       'url' => 'guestbook.tpl'],
    ['name' => 'smarty_2.htm',       'url' => 'guestbook_form.tpl'],

    ['name' => 'google.htm',         'url' => 'http://www.google.com/'],
    ['name' => 'senate.htm',         'url' => 'http://www.senate.gov/legislative/LIS/roll_call_lists/roll_call_vote_cfm.cfm?congress=101&session=2&vote=00317'],
    ['name' => 'cyberciti.htm',      'url' => 'http://www.cyberciti.biz/tips/configure-ubuntu-grub-to-load-freebsd.html'],
    ['name' => 'myspace.htm',        'url' => 'http://www.myspace.com/'],
    ['name' => 'mootools.htm',       'url' => 'http://www.mootools.net/'],
    ['name' => 'jquery.htm',         'url' => 'http://jquery.com/'],
    ['name' => 'scriptaculo.htm',    'url' => 'http://script.aculo.us/'],
    ['name' => 'apache.htm',         'url' => 'http://www.apache.org/'],
    ['name' => 'microsoft.htm',      'url' => 'http://www.microsoft.com/'],
    ['name' => 'slashdot.htm',       'url' => 'http://www.slashdot.org/'],
    ['name' => 'ror.htm',            'url' => 'http://www.rubyonrails.org/'],
    ['name' => 'yahoo.htm',          'url' => 'http://www.yahoo.com/'],
    ['name' => 'phpbb.htm',          'url' => 'http://www.phpbb.com/'],
    ['name' => 'python.htm',         'url' => 'http://www.python.org/'],
    ['name' => 'lua.htm',            'url' => 'http://www.lua.org/'],
    ['name' => 'php.htm',            'url' => 'http://www.php.net/'],
    ['name' => 'ibm.htm',            'url' => 'http://www.ibm.com/'],
    ['name' => 'java.htm',           'url' => 'http://java.sun.com/'],
    ['name' => 'flickr.htm',         'url' => 'http://www.flickr.com/tour/upload/'],
    ['name' => 'amazon.htm',         'url' => 'http://www.amazon.com/'],
    ['name' => 'youtube.htm',        'url' => 'http://www.youtube.com/watch?v=kib05Ip6GSo&feature=bz302'],
];

echo 'memory: ' . memory_get_usage() . '<br>';
$dom = new simple_html_dom();

foreach ($files as $f) {
    // get file from url
    if ('' != $f['url']) {
        file_put_contents($dir . $f['name'], file_get_contents($f['url']));
    } else {
        file_put_contents($dir . $f['name'], '');
    }

    $start = microtime();
    $dom->load(file_get_contents($dir . $f['name']), false);
    list($eu, $es) = explode(' ', microtime());
    list($bu, $bs) = explode(' ', $start);
    echo sprintf('(%.1f)', ((float) $eu + (float) $es - (float) $bu - (float) $bs) * 1000) . '<br>';

    if (file_get_contents($dir . $f['name']) != $dom->save()) {
        echo "[<font color='red'>failed</font>] " . $f['name'] . '<br>';
        $dom->save($dir . $f['name'] . '.error');
    } else {
        echo '[success] ' . $f['name'] . '<br>';
    }

    echo 'memory: ' . memory_get_usage() . '<br>';

    flush();
    set_time_limit(0);
}

$dom->clear();
unset($dom);
echo '<br>memory: ' . memory_get_usage() . '<br>';
