<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Активные регионы</h3>
    </div>
    <div class="panel-body">
        В таблице ниже отображаются выкупаемые регионы в режиме реального времени. В зависимости от текущего времени, дня недели и других факторов перечень регионов и их стоимость могут полностью меняться.
    </div>
    <?php
    // выводим виджет с ценами по регионам
        $this->widget('application.widgets.RegionPrices.RegionPrices', array());
    ?> 
</div>