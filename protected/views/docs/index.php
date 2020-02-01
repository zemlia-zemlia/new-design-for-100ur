<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Docs',
);
Yii::app()->clientScript->registerScriptFile('https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js', CClientScript::POS_END);


    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
    }


//data:{jstree: {fileType: "dir"}}
$js = <<<JS
$(function () {
    // 6 create an instance when the DOM is ready
    $('#jstree').jstree();
    // 7 bind to events triggered on the tree
    $('#jstree').on("changed.jstree", function (e, data) {
      // console.log(data.selected);
    });
    // 8 interact with the tree - either way is OK
    $('button').on('click', function () {
      $('#jstree').jstree(true).select_node('child_node_1');
      $('#jstree').jstree('select_node', 'child_node_1');
      $.jstree.reference('#jstree').select_node('child_node_1');
    });
  });
$('#jstree')
  // listen for event
  .on('changed.jstree', function (e, data) {
    var i, j, r = [];
    for(i = 0, j = data.selected.length; i < j; i++) {
      r.push(data.instance.get_node(data.selected[i]).text);
    }
    if (data.node.data.jstree.fileType == 'dir'){
        $('#addCategory').attr('href','/fileCategory/create/?cat_id=' + data.node.data.jstree.itemId);
         $('#removeCategory').attr('href','/fileCategory/delete/?id=' + data.node.data.jstree.itemId); 
         $('#addFile').attr('href','/docs/create/?cat_id=' + data.node.data.jstree.itemId).html('Загрузить в категорию <b>' + data.node.text + '</b>');
    }
    else 
         window.location = '/docs/update/?id=' + data.node.data.jstree.itemId;
    
   
    
    
    console.log(data.node.text);
    // $('#event_result').html('Selected: ' + r.join(', '));
  })
  // create the instance
  .jstree();
JS;
Yii::app()->clientScript->registerScript('myjquery', $js);



$this->menu=array(
	array('label'=>'Create Docs', 'url'=>array('create')),
	array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>
<?php //var_dump($this->menu);die;?>





    <a class="btn btn-primary" id="addFile" href="#">Загрузить в категорию (выберите категорию)</a>

<h1>Файлы</h1>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />


<div id="event_result"></div>

<div id="jstree">
<?php
$criteria=new CDbCriteria;
$criteria->order='t.root, t.lft'; // or 't.root, t.lft' for multiple trees
$categories=FileCategory::model()->findAll($criteria);
$level=0;
//var_dump($categories);die;

foreach($categories as $n=>$category)
{
if($category->level==$level)
echo CHtml::closeTag('li')."\n";
else if($category->level>$level)
echo CHtml::openTag('ul')."\n";
else
{
echo CHtml::closeTag('li')."\n";

for($i=$level-$category->level;$i;$i--)
{
echo CHtml::closeTag('ul')."\n";
echo CHtml::closeTag('li')."\n";
}
}?>
<li data-jstree='{"fileType":"dir","itemId":"<?= $category->id ?>"}'>
<?php
    echo   CHtml::link(CHtml::encode($category->name), Yii::app()->createUrl('/docs/category/?id=' . $category->id));
//echo CHtml::encode($category->name);
    if ($category->files): ?>
        <ul>
       <?php foreach ($category->files as $file): ?>
            <li data-jstree='{"icon":"glyphicon glyphicon-leaf","fileType":"file","itemId":"<?= $file->id ?>"}'><a  target="_blank" href="/docs/download/?id=<?= $file->id ?>"><?= $file->name?>(<?php echo CHtml::encode($file->downloads_count); ?>)</a></li>

        <?php endforeach;?>
        </ul>
            <?php endif; ?>

<?php
$level=$category->level;
}

for($i=$level;$i;$i--)
{
echo CHtml::closeTag('li')."\n";
echo CHtml::closeTag('ul')."\n";
}

?>


</div>

<div class="row">
    <div class="col-lg-12">
        <a class="btn btn-primary"  id="addCategory" href="#">Добавить категорию</a>
        <a class="btn btn-danger"  id="removeCategory" href="#">Удалить категорию</a>
        <a class="btn btn-warning"  id="addCategory" href="/fileCategory/create/?cat_id=0">Добавить корневую категорию</a>
    </div>
</div>