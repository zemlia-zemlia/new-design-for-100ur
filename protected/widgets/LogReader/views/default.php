<div class="table-responsive">
    <table class="table table-bordered">
        <?php foreach ($records as $record): ?>
            <tr>
                <td></td>
                <td><?php echo str_replace(':', ': ', $record['message']); ?></td>
                <td><?php echo CustomFuncs::niceDate($record['created'], true, false); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>