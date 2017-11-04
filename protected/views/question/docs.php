<?php
$this->setPageTitle("Заказ документов ". Yii::app()->name);
?>


<h1 class="header-block header-block-light-grey">Заказ документов</h1>
<?php 
//CustomFuncs::printr($order->errors);
//CustomFuncs::printr($author->errors);
?>

<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formDocs', array(
            'order'         =>  $order,
            'author'        =>  $author,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>


<script type="text/javascript">   
    var docs = {
        <?php foreach(DocType::getClassesArray() as $classId => $docClass):?>
            <?php echo $classId;?>: { type:'<?php echo $docClass['name'];?>',
                type_description:'<?php echo $docClass['description'];?>',
                subtypes:[
                    <?php foreach($docTypesArray[$classId] as $type):?>
                            {id:<?php echo $type->id;?>, name: '<?php echo $type->name;?>'},
                    <?php endforeach;?>
                ]
            },
        <?php endforeach;?>
    };
    
    $(function(){
        console.log(docs);
        
        for(var key in docs) {
            $("#docType").append('<div class="doc-type-wrapper"><label><input type="radio" name="doc_type" value="' + key + '"> <span class="doc_type_name">' + docs[key]['type'] + '</span></input><div class="doc-type-desc">'+ docs[key]['type_description'] +'</div></label></div>');
//            console.log(docs[key]['type']);
//            console.log(docs[key]['subtypes']);
//            for(var subtype in docs[key]['subtypes']) {
//                console.log('+' + docs[key]['subtypes'][subtype]);
//            }
        }
        
        $("[name=doc_type]").on('change', function(){
            var current_type = $(this).val();
            console.log(current_type);
            if(current_type == '') {
                return false;
            }
            /*
            if(current_type == 6) {
                var question = $("#docType input:checked").closest('label').find(".doc_type_name").text();
                $("#Lead_question_hidden").val(question);
                $("#docSubType option").remove();
                return false;
            }*/
            // удалим все пункты списка прежде чем наполнить его по новой
            $("#docSubType option").remove();
            
            if(docs[current_type]['subtypes']) {
                if(current_type != 6) {
                    $("#docSubType").append('<option value="">Выберите подтип документа</option>').show();
                }
                for(var subtype in docs[current_type]['subtypes']) {
                    $("#docSubType").append('<option value="'+ docs[current_type]['subtypes'][subtype].id +'">'+ docs[current_type]['subtypes'][subtype].name +'</option>');
                    //console.log('+' + docs[current_type]['subtypes'][subtype]);
                }
            }
        })
        
        $("#docSubType").on('change', function(){
            var subtype = $(this).val();
            var type = $("#docType input:checked").closest('label').find(".doc_type_name").text();
            
            question = type + ': ' + subtype;
            $("#Lead_question_hidden").val(question);
            console.log(question);
            
        });
        
    })
    
</script>
    