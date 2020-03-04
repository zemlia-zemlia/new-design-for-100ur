<div class="box">
    <div class="box-header">
        <div class="box-title">Активные регионы</div>
        <p> В таблице ниже отображаются выкупаемые регионы в режиме реального времени. В зависимости от текущего времени, дня недели и других факторов перечень регионов и их стоимость могут изменяться.</p>
    </div>
    <div class="box-body">
        <?php
        // выводим виджет с ценами по регионам
        $this->widget('application.widgets.RegionPrices.RegionPrices', []);
        ?>
    </div>
</div>
