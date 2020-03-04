<?php
error_reporting(E_ALL);
include_once '../simple_html_dom.php';

$html = file_get_html('google.htm');
//$html = file_get_html('youtube.htm');
//$html = file_get_html('Product.ibatis.xml');

$lang = '';
$l = $html->find('html', 0);
if (null !== $l) {
    $lang = $l->lang;
}
if ('' != $lang) {
    $lang = 'lang="' . $lang . '"';
}

$charset = $html->find('meta[http-equiv*=content-type]', 0);
$target = [];
$query = '';

if (isset($_REQUEST['query'])) {
    $query = $_REQUEST['query'];
    $target = $html->find($query);
}

function stat_dom($dom)
{
    $count_text = 0;
    $count_comm = 0;
    $count_elem = 0;
    $count_tag_end = 0;
    $count_unknown = 0;

    foreach ($dom->nodes as $n) {
        if (HDOM_TYPE_TEXT == $n->nodetype) {
            ++$count_text;
        }
        if (HDOM_TYPE_COMMENT == $n->nodetype) {
            ++$count_comm;
        }
        if (HDOM_TYPE_ELEMENT == $n->nodetype) {
            ++$count_elem;
        }
        if (HDOM_TYPE_ENDTAG == $n->nodetype) {
            ++$count_tag_end;
        }
        if (HDOM_TYPE_UNKNOWN == $n->nodetype) {
            ++$count_unknown;
        }
    }

    echo 'Total: ' . count($dom->nodes) .
        ', Text: ' . $count_text .
        ', Commnet: ' . $count_comm .
        ', Tag: ' . $count_elem .
        ', End Tag: ' . $count_tag_end .
        ', Unknown: ' . $count_unknown;
}

function dump_my_html_tree($node, $show_attr = true, $deep = 0, $last = true)
{
    $count = count($node->nodes);
    if ($count > 0) {
        if ($last) {
            echo '<li class="expandable lastExpandable"><div class="hitarea expandable-hitarea lastExpandable-hitarea"></div>&lt;<span class="tag">' . htmlspecialchars($node->tag) . '</span>';
        } else {
            echo '<li class="expandable"><div class="hitarea expandable-hitarea"></div>&lt;<span class="tag">' . htmlspecialchars($node->tag) . '</span>';
        }
    } else {
        $laststr = (false === $last) ? '' : ' class="last"';
        echo '<li' . $laststr . '>&lt;<span class="tag">' . htmlspecialchars($node->tag) . '</span>';
    }

    if ($show_attr) {
        foreach ($node->attr as $k => $v) {
            echo ' ' . htmlspecialchars($k) . '="<span class="attr">' . htmlspecialchars($node->$k) . '</span>"';
        }
    }
    echo '&gt;';

    if ('text' === $node->tag || 'comment' === $node->tag) {
        echo htmlspecialchars($node->innertext);

        return;
    }

    if ($count > 0) {
        echo "\n<ul style=\"display: none;\">\n";
    }
    $i = 0;
    foreach ($node->nodes as $c) {
        $last = (++$i == $count) ? true : false;
        dump_my_html_tree($c, $show_attr, $deep + 1, $last);
    }
    if ($count > 0) {
        echo "</ul>\n";
    }

    //if ($count>0) echo '&lt;/<span class="attr">'.htmlspecialchars($node->tag).'</span>&gt;';
    echo "</li>\n";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html <?php echo $lang; ?>>
<head>
    <?php
        if ('' != $lang) {
            echo '<meta http-equiv="content-type" content="text/html; charset=utf-8"/>';
        } elseif ($charset) {
            echo $charset;
        } else {
            echo '<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>';
        }
    ?>
	<title>Simple HTML DOM Query Test</title>
	<link rel="stylesheet" href="js/jquery.treeview.css" />
	<link rel="stylesheet" href="js/screen.css" />
	<style>
        .tag { color: blue; }
        .attr { color: #990033; }
    </style>
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script>
	<script type="text/javascript">
    $(document).ready(function(){	
        $("#html_tree").treeview({
            control:"#sidetreecontrol",
            collapsed: true,
            prerendered: true
        });
	});
    </script>
	</head>
	<body>
	<div id="main">
	<h4>Simple HTML DOM Test</h4>
    <form name="form1" method="post" action="">
        find: <input name="query" type="text" size="60" maxlength="60" value="<?php echo htmlspecialchars($query); ?>">
        <input type="submit" name="Submit" value="Go">
    </form>
    <br>
	HTML STAT (<?stat_dom($html); ?>)<br>
    <br>
	<div id="sidetreecontrol"><a href="?#">Collapse All</a> | <a href="?#">Expand All</a></div><br>
	<ul class="treeview" id="html_tree">
	    <?php
            ob_start();
            foreach ($target as $e) {
                dump_my_html_tree($e, true);
            }
            ob_end_flush();
        ?>
	</ul>
</div>
 
</body></html>