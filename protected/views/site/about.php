<?php
    $this->setPageTitle("О проекте ". Yii::app()->name);
    Yii::app()->clientScript->registerMetaTag("Информация о проекте Юридические консультации онлайн 100 Юристов и Адвокатов", 'description');
?>




<h1>О проекте</h1>
<p></p>  
<p>Ваши предложения по работе портала и сотрудничеству с нами направляйте по адресу admin@100yuristov.com</p>  

<h2 align="center">Платные вопросы</h2>

<p>Проект 100 Юристов предлагает онлайн консультации юристов на платной основе, ниже представлены описание тарифных планов и их стоимость.</p>   
<table class="table center-align table-bordered alert alert-warning">
			<tr>
				<th class="center-align" style="width: 33%">Бронза</th>
				<th class="center-align" style="width: 33%">Серебро</th>
				<th class="center-align" style="width: 33%">Золото</th>
				<!--<th class="center-align" style="width: 33%">Vip1</th>-->
				<!--<th class="center-align" style="width: 33%">Vip2</th>-->
			</tr>
			<tr>

			</tr>
			<tr>
				<td>
				<strong style="font-size: 20px;">1</strong><br/><span class="mutted">гарантированный ответ</span>
				</td>
				
				<td>
				<strong style="font-size: 20px;">2</strong><br/><span class="mutted">гарантированных ответа</span>
				</td>
				
				<td>
				<strong style="font-size: 20px;">3</strong><br/><span class="mutted">гарантированных ответа</span>
				</td>

			</tr>
			<tr>
                            <td><?php echo Question::getPriceByLevel(Question::LEVEL_1);?> руб.</td>
                            <td><?php echo Question::getPriceByLevel(Question::LEVEL_2);?> руб.</td>
                            <td><?php echo Question::getPriceByLevel(Question::LEVEL_3);?> руб.</td>
			</tr>
        </table>
			
		<table class="table center-align table-bordered alert alert-warning">
			<tr>
				<th class="center-align" style="width: 33%">Vip</th>
				<th class="center-align" style="width: 33%">Vip+</th>
			</tr>
			<tr>
				<td>
				<strong style="font-size: 20px;">4</strong><br/><span class="mutted">гарантированных ответа</span>
				</td>
				<td>
				<strong style="font-size: 20px;">5</strong><br/><span class="mutted">гарантированных ответов</span>
				</td>
			</tr>
			<tr>
				<td><?php echo Question::getPriceByLevel(Question::LEVEL_4);?> руб.</td>
				<td><?php echo Question::getPriceByLevel(Question::LEVEL_5);?> руб.</td>
			</tr>
		</table>
			
