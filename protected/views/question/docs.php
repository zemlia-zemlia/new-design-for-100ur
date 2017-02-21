<?php
$this->setPageTitle("Заказ документов ". Yii::app()->name);
?>


<h1 class="header-block header-block-light-grey">Заказ документов</h1>


<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formDocs', array(
            'model'         =>  $model,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>


<script type="text/javascript">
    var docs = {
        1:{ type:'Регистрация бизнеса',
            type_description:'Комплекты документов для регистрации ООО, ИП, ТСЖ и др.',
            subtypes:['Регистрация ООО','Внесение изменений в учредительные документы','Регистрация ИП', 'Регистрация ТСЖ', 'Другое']
        },
        2:{ type:'Договоры и соглашения',
            type_description:'Договоры аренды, подряда, купли-продажи, займа, комиссии и др',
            subtypes:['Трудовой договор','Договор купли-продажи','Договор на оказание услуг','Договор дарения', 'Договор аренды','Другое']
        },
        3:{ type:'Документы в суд',
            type_description:'Исковое заявление, отзыв на исковое заявление, ходатайство, жалоба на решение суда и др.',
            subtypes:['Исковое заявление','Отзыв или возражение на исковое заявление','Ходатайство','Жалоба на решение суда','Жалоба на постановление по делу об административном правонарушении', 'Другое']
        },
        4:{ type:'Претензии потребителей',
            type_description:'Претензии на возврат денег за товар. Претензии в страховую, в банк, к ЖКХ и др.',
            subtypes:['Претензия на возврат денежных средств за товар (услугу) ненадлежащего качества',
                'Претензия в страховую компанию',
                'Претензия в банк',
                'Претензия к ЖКХ, управляющей компании',
                'Претензия к застройщику',
                'Другое'
            ]
        },
        5:{ type:'Жалоба на чиновника',
            type_description:'Жалоба на действия должностного лица, судебного пристава, сотрудника ГИБДД и др.',
            subtypes:['Жалоба на действия должностного лица',
                'Жалоба на действия судебного пристава-исполнителя',
                'Жалоба на действия сотрудника ГИБДД',
                'Другое'
            ]
        },
        6:{ type:'Другое',
            type_description:'Любой другой документ. Вы можете описать его самостоятельно.',
            subtypes:['Указывать не требуется']
        },
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
                    $("#docSubType").append('<option value="'+ docs[current_type]['subtypes'][subtype] +'">'+ docs[current_type]['subtypes'][subtype] +'</option>');
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
    