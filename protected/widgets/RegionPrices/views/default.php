<table class="table table-bordered">
    <tr>
        <th>
            Регион
        </th>
        <th class="text-left">
            Цена лида
        </th>
    </tr>
    <?php foreach($campaignsArray as $region => $price):?>
    <tr>
        <td>
            <?php echo $region;?>
        </td>
        <td class="text-left">
            <?php 
            if(Yii::app()->user->role == User::ROLE_PARTNER && Yii::app()->user->priceCoeff !== 0) {
                $price = $price * Yii::app()->user->priceCoeff;
            }   
            ?>
            от <?php echo round($price);?> руб.
        </td>
    </tr>
    <?php endforeach;?>
</table>


