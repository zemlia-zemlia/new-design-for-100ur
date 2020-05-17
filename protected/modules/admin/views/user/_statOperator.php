<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Получено контактов</th>
        <th>Заключено договоров</th>
    </tr>
    </thead>
    
    
    <?php foreach ($leadsArray as $year => $leadsPerYear):?>
        <?php foreach ($leadsPerYear as $month => $leadsPerMonth):?>
    
            <?php
                $leadsTotal = (int) $leadsPerMonth['total'];
                $leadsBrak = (int) $leadsPerMonth['brak'];
                $leadsNaBrak = (int) $leadsPerMonth['nabrak'];
                $leadsArchive = (int) $leadsPerMonth['archive'];
                $leadsActive = (int) $leadsPerMonth['active'];
                if ($leadsTotal > 0) {
                    $leadsBrakPercent = round((($leadsBrak + $leadsNaBrak) / $leadsTotal) * 100);
                }
            ?>
    
            <tr>
                <th colspan="2" class="center-align">
                    <?php echo $monthsArray[$month] . ' ' . $year; ?>
                </th>
            </tr>
            <tr class="success">
            <td>
                Всего: <?php echo $leadsTotal; ?><br />
                В работе: <?php echo $leadsActive; ?>
                <?php if ($leadsTotal > 0) {
                echo " <span class='muted'>(" . round(($leadsActive / $leadsTotal) * 100) . '%)</span>';
            }
                ?>
                <br />
                Отказ: <?php echo $leadsArchive; ?>
                <?php if ($leadsTotal > 0) {
                    echo " <span class='muted'>(" . round(($leadsArchive / $leadsTotal) * 100) . '%)</span>';
                }
                ?>
                <br />
                Брак: <?php echo $leadsBrak; ?> + <abbr title="на отбраковку"><?php echo $leadsNaBrak; ?></abbr>
                <span class="muted">
                    <?php if ($leadsArray[$year][$month]['total'] > 0) {
                    echo ' (' . $leadsBrakPercent . '%)';
                }?>
                </span> 
                <br />
                Консульт:
                <?php
                    // в массиве $noLeadsArray содержатся данные по количеству встреч с клиентами из нелидовых каналов
                    // ключи - id каналов, значения - количество встреч
                    $noLeadsArray = [];
                    $meetingsCounter = 0;
                    if (is_array($meetingsArray[$year][$month])) {
                        foreach ($meetingsArray[$year][$month] as $sourceId => $meetingsBySource) {
                            if (0 != $meetingsBySource['noLead']) {
                                $noLeadsArray[$sourceId] = $meetingsBySource['counter'];
                            }
                            $meetingsCounter += $meetingsBySource['counter'];
                        }
                    }

                    echo (int) $meetingsCounter;
                    if ($leadsActive > 0) {
                        echo ' <span class="muted">(' . round(($meetingsCounter / ($leadsTotal - $leadsBrak)) * 100) . '%)</span>';
                    }
                ?>
                <br />
                Консультации в текущем месяце:
                <?php
                    // в массиве $noLeadsArray содержатся данные по количеству встреч с клиентами из нелидовых каналов
                    // ключи - id каналов, значения - количество встреч
                    $noLeadsArray = [];
                    $meetingsCurrentCounter = 0;
                    if (is_array($meetingsCurrentArray[$year][$month])) {
                        foreach ($meetingsCurrentArray[$year][$month] as $sourceId => $meetingsCurrentBySource) {
                            if (0 != $meetingsCurrentBySource['noLead']) {
                                $noLeadsArray[$sourceId] = $meetingsCurrentBySource['counter'];
                            }
                            $meetingsCurrentCounter += $meetingsCurrentBySource['counter'];
                        }
                    }

                    echo (int) $meetingsCurrentCounter;

                ?>
            </td>
            
            <td>
                <?php
                    $noLeadsAgreementsArray = [];
                    $agreementsCounter = 0;
                    $agreementsSum = 0;
                    if (is_array($agreementsArray[$year][$month])) {
                        foreach ($agreementsArray[$year][$month] as $sourceId => $agreementsBySource) {
                            if (0 != $agreementsBySource['noLead']) {
                                $noLeadsAgreementsArray[$sourceId]['counter'] = $agreementsBySource['counter'];
                                $noLeadsAgreementsArray[$sourceId]['sum'] = $agreementsBySource['sum'];
                            }
                            $agreementsCounter += $agreementsBySource['counter'];
                            $agreementsSum += $agreementsBySource['sum'];
                        }
                    }

                    echo 'Заключено: ' . (int) $agreementsCounter;

                    if ($meetingsCounter > 0) {
                        echo ' <span class="muted">(' . round(($agreementsCounter / $meetingsCounter) * 100) . '%)</span>';
                    }
                    echo '<br />';
                    echo 'Сумма: ' . number_format($agreementsSum, 0, '.', ' ') . ' руб.';
                    echo '<br />';
                    if ($agreementsCounter > 0) {
                        echo 'Средн.: ' . number_format($agreementsSum / $agreementsCounter, 0, '.', ' ') . ' руб.';
                    }
                ?>
                <br /><br />
                <strong class='text-danger'>Раст: 
                    <?php
                        $agreementsAbortedSum = 0;
                        $agreementsAbortedCounter = 0;

                        if (is_array($agreementsArray[$year][$month])) {
                            foreach ($agreementsArray[$year][$month] as $sourceId => $agreementsBySource) {
                                if (0 != $agreementsBySource['aborted']['noLead']) {
                                    $noLeadsAgreementsAbortedArray[$sourceId]['aborted']['counter'] = $agreementsBySource['aborted']['counter'];
                                    $noLeadsAgreementsAbortedArray[$sourceId]['aborted']['sum'] = $agreementsBySource['aborted']['sum'];
                                }
                                $agreementsAbortedCounter += $agreementsBySource['aborted']['counter'];
                                $agreementsAbortedSum += $agreementsBySource['aborted']['sum'];
                            }
                        }
                    ?> 
                    
                <?php echo $agreementsAbortedCounter; ?></strong>
                <br />
                        <?php
                            if ($agreementsAbortedSum > 0) {
                                echo "<strong class='text-danger'>Сумма раст: " . $agreementsAbortedSum . '&nbsp;руб.</strong>';
                            }
                        ?>
            </td>
    
    <?php endforeach; // перебираем месяцы?>            
    <?php endforeach; // перебираем годы?>
</table>