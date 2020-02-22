<div class="table-responsive">
    <table class="table table-bordered">
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo LogReader::createLink($record); ?></td>
                <td><?php echo CHtml::encode(str_replace(':', ': ', $record['message'])); ?></td>
                <td class="text-nowrap"><?php echo CustomFuncs::niceDate($record['created'], true, false); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>