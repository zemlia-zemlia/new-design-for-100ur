<?php
/** @var array $rankByDay */

use App\helpers\DateHelper;
use App\models\UserActivity;

/* @var DateTime $firstDateInCalendar */
?>

<?php
$weeksInInterval = ceil(sizeof($rankByDay) / 7);
?>
<div class="container-fluid">
    <table class="table">
        <?php for ($dayOfWeek = 0; $dayOfWeek < 7; ++$dayOfWeek): ?>
            <tr>
                <?php for ($week = 0; $week < $weeksInInterval; ++$week): ?>

                    <?php
                    $daysFromStartDateInCalendar = $week * 7 + $dayOfWeek;
                    $currentDate = (clone $firstDateInCalendar)
                        ->add(new DateInterval('P' . $daysFromStartDateInCalendar . 'D'))
                        ->format('Y-m-d');

                    $rank = (isset($rankByDay[$currentDate])) ?
                        $rankByDay[$currentDate] :
                        0;
                    $rankColor = UserActivity::getColorByRank($rank);
                    ?>
                    <td class="small text-center" style="height:10px; background-color: <?php echo $rankColor; ?>">
                        <?php echo DateHelper::niceDate($currentDate, false, false, false); ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
</div>
