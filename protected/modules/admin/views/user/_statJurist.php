<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Свои контакты</th>
        <th>Контакты от операторов</th>
        <th>Заключено договоров</th>
        <th>Внесено по договорам</th>
        <th>Задолженность</th>
    </tr>
    </thead>
    
    <?php foreach ($leadsArray as $year=>$leadsPerYear):?>
        <?php foreach ($leadsPerYear as $month=>$leadsPerMonth):?>
    <?php
            $leadsTotal = (int)$leadsPerMonth['total'];
            
            $leadsInitBrak = (int)$leadsPerMonth['init']['brak'];
            $leadsInitNaBrak = (int)$leadsPerMonth['init']['nabrak'];
            $leadsInitArchive = (int)$leadsPerMonth['init']['archive'];
            $leadsInitActive = (int)$leadsPerMonth['init']['active'];
            
            $leadsTakenBrak = (int)$leadsPerMonth['taken']['brak'];
            $leadsTakenNaBrak = (int)$leadsPerMonth['taken']['nabrak'];
            $leadsTakenArchive = (int)$leadsPerMonth['taken']['archive'];
            $leadsTakenActive = (int)$leadsPerMonth['taken']['active'];
            
            $leadsInit = (int)$leadsPerMonth['init']['total']; // контакты, взятые юристом из неразобранных
            $leadsTaken = (int)$leadsPerMonth['taken']['total']; // контакты, приведенные оператором или другим юристом
            
            if ($leadsInit>0) {
                $leadsInitBrakPercent = round((($leadsInitBrak + $leadsInitNaBrak)/$leadsInit)*100);
            } elseif ($leadsTaken>0) {
                $leadsTakenBrakPercent = round((($leadsTakenBrak + $leadsTakenNaBrak)/$leadsTaken)*100);
            }
    ?>
    <tr>
        <th colspan="5" class="center-align">
            <?php echo $monthsArray[$month] . ' ' . $year;?>
        </th>
    </tr>
    <tr class="success">
            <td>
                Всего: <?php echo $leadsInit; ?><br />
                В работе: <?php echo $leadsInitActive; ?>
                <?php if ($leadsInit>0) {
        echo " <span class='muted'>(" . round(($leadsInitActive/$leadsInit)*100) . '%)</span>';
    }
                ?>
                <br />
                Отказ: <?php echo $leadsInitArchive; ?>
                <?php if ($leadsInit>0) {
                    echo " <span class='muted'>(" . round(($leadsInitArchive/$leadsInit)*100) . '%)</span>';
                }
                ?>
                <br />
                Брак: <?php echo $leadsInitBrak; ?> + <abbr title="на отбраковку"><?php echo $leadsInitNaBrak; ?></abbr>
                <span class="muted">
                    <?php if ($leadsInit>0) {
                    echo ' (' . $leadsInitBrakPercent . '%)';
                }?>
                </span> 
                <br />
                Консульт:
                <?php
                    // в массиве $noLeadsArray содержатся данные по количеству встреч с клиентами из нелидовых каналов
                    // ключи - id каналов, значения - количество встреч
                    $noLeadsArray = array();
                    $meetingsCounter = 0;
                    if (is_array($meetingsArray[$year][$month])) {
                        foreach ($meetingsArray[$year][$month] as $sourceId=>$meetingsBySource) {
                            if ($meetingsBySource['noLead'] != 0) {
                                $noLeadsArray[$sourceId] = $meetingsBySource['counter'];
                            }
                            $meetingsInitCounter += $meetingsBySource['init'];
                        }
                    }

                    echo (int)$meetingsInitCounter;
                    if ($leadsInitActive>0) {
                        echo ' <span class="muted">(' . round(($meetingsInitCounter/($leadsInit - $leadsInitBrak))*100) . '%)</span>';
                    }
                ?>
                
                <br />
                Закл: 
                
                <?php
                    echo (int)$agreementsArray[$year][$month]['init'];
                    if ($meetingsInitCounter>0) {
                        echo ' <span class="muted">(' . round(((int)$agreementsArray[$year][$month]['init']/$meetingsInitCounter)*100) . '%)</span>';
                    }
                ?>
                
            </td>
           
            <td>
                Всего: <?php echo $leadsTaken; ?><br />
                В работе: <?php echo $leadsTakenActive; ?>
                <?php if ($leadsTaken>0) {
                    echo " <span class='muted'>(" . round(($leadsTakenActive/$leadsTaken)*100) . '%)</span>';
                }
                ?>
                <br />
                Отказ: <?php echo $leadsTakenArchive; ?>
                <?php if ($leadsTaken>0) {
                    echo " <span class='muted'>(" . round(($leadsTakenArchive/$leadsTaken)*100) . '%)</span>';
                }
                ?>
                <!--
                <br />
                Брак: <?php echo $leadsTakenBrak; ?> + <abbr title="на отбраковку"><?php echo $leadsTakenNaBrak; ?></abbr>
                <span class="muted">
                    <?php if ($leadsTaken>0) {
                    echo ' (' . $leadsTakenBrakPercent . '%)';
                }?>
                </span> 
                <br />
                Консульт:-->
                <?php
                    // в массиве $noLeadsArray содержатся данные по количеству встреч с клиентами из нелидовых каналов
                    // ключи - id каналов, значения - количество встреч
                    $noLeadsArray = array();
                    $meetingsTakenCounter = 0;
                    if (is_array($meetingsArray[$year][$month])) {
                        foreach ($meetingsArray[$year][$month] as $sourceId=>$meetingsBySource) {
                            if ($meetingsBySource['noLead'] != 0) {
                                $noLeadsArray[$sourceId] = $meetingsBySource['counter'];
                            }
                            $meetingsTakenCounter += $meetingsBySource['taken'];
                        }
                    }

                    //echo (int)$meetingsTakenCounter;
                    /*
                    if($leadsTakenActive>0) {
                        echo ' <span class="muted">(' . round(($meetingsTakenCounter/($leadsTaken - $leadsTakenBrak))*100) . '%)</span>';
                    }*/
                    ?>
                <br />
                Закл: 
                
                <?php
                    echo (int)$agreementsArray[$year][$month]['taken'];
                    if ($meetingsTakenCounter>0) {
                        echo ' <span class="muted">(' . round(((int)$agreementsArray[$year][$month]['taken']/$meetingsTakenCounter)*100) . '%)</span>';
                    }
                ?>
            </td>
            
            <td>
                <?php
                    $noLeadsAgreementsArray = array();
                    $agreementsCounter = 0;
                    $agreementsSum = 0;
                    if (is_array($agreementsArray[$year][$month])) {
                        foreach ($agreementsArray[$year][$month] as $sourceId=>$agreementsBySource) {
                            if ($agreementsBySource['noLead'] != 0) {
                                $noLeadsAgreementsArray[$sourceId]['counter'] = $agreementsBySource['counter'];
                                $noLeadsAgreementsArray[$sourceId]['sum'] = $agreementsBySource['sum'];
                            }
                            $agreementsCounter += $agreementsBySource['counter'];
                            $agreementsSum += $agreementsBySource['sum'];
                        }
                    }
                    
                    echo "Заключено: " . (int)$agreementsCounter;
                    
                    if ($meetingsCounter>0) {
                        echo ' <span class="muted">(' . round(($agreementsCounter/$meetingsCounter)*100) . '%)</span>';
                    }
                    echo '<br />';
                    echo 'Сумма: ' . number_format($agreementsSum, 0, '.', ' ') . ' руб.';
                    echo '<br />';
                    if ($agreementsCounter>0) {
                        echo 'Средн.: ' . number_format($agreementsSum/$agreementsCounter, 0, '.', ' ') . ' руб.';
                    }
                ?>
                <br /><br />
                <strong class='text-danger'>Раст: 
                    <?php
                        $agreementsAbortedSum = 0;
                        $agreementsAbortedCounter = 0;
                        
                        if (is_array($agreementsArray[$year][$month])) {
                            foreach ($agreementsArray[$year][$month] as $sourceId=>$agreementsBySource) {
                                if ($agreementsBySource['aborted']['noLead'] != 0) {
                                    $noLeadsAgreementsAbortedArray[$sourceId]['aborted']['counter'] = $agreementsBySource['aborted']['counter'];
                                    $noLeadsAgreementsAbortedArray[$sourceId]['aborted']['sum'] = $agreementsBySource['aborted']['sum'];
                                }
                                $agreementsAbortedCounter += $agreementsBySource['aborted']['counter'];
                                $agreementsAbortedSum += $agreementsBySource['aborted']['sum'];
                            }
                        }
                    ?> 
                    
                <?php echo $agreementsAbortedCounter;?></strong>
                <br />
                        <?php
                            if ($agreementsAbortedSum>0) {
                                echo "<strong class='text-danger'>Сумма раст: " . $agreementsAbortedSum . "&nbsp;руб.</strong>";
                            }
                        ?>
            </td>
            <td>
                <?php
                    $transactionsSum = 0;
                    $noLeadsTransactionsArray = array();
                    
                    if (is_array($transactionsArray[$year][$month])) {
                        foreach ($transactionsArray[$year][$month] as $sourceId=>$transactionsBySource) {
                            if ($transactionsBySource['total']['noLead'] != 0) {
                                $noLeadsTransactionsArray[$sourceId]['sum'] = $transactionsBySource['total']['sum'];
                            }
                            $transactionsSum += $transactionsBySource['total']['sum'];
                        }
                    }
                    echo "Внесено " . number_format($transactionsSum, 0, '.', ' ') . ' руб.';
                                        
                    if ($agreementsSum>0) {
                        echo " <span class='muted'>(" . round(($transactionsSum/$agreementsSum)*100) . '%)</span>';
                    }
                ?>
                <br /><br /><br /><br />
                
                <?php
                    $transactionsAbortedSum = 0;
                    $noLeadsTransactionsArray = array();
                    
                    if (is_array($transactionsArray[$year][$month])) {
                        foreach ($transactionsArray[$year][$month] as $sourceId=>$transactionsBySource) {
                            if ($transactionsBySource['aborted']['noLead'] != 0) {
                                $noLeadsTransactionsArray[$sourceId]['sum'] = $transactionsBySource['aborted']['sum'];
                            }
                            $transactionsAbortedSum += $transactionsBySource['aborted']['sum'];
                        }
                    }
                    ?>
                <strong class='text-danger'>
                    <?php
                    echo number_format($transactionsAbortedSum, 0, '.', ' ') . ' руб.';
                    
                    
                    if ($agreementsAbortedSum>0) {
                        echo " <span class='muted'>(" . round(($transactionsAbortedSum/$agreementsAbortedSum)*100) . '%)</span>';
                    }
                    
                    ?>
                </strong>
            </td>
            <td>
                <?php
                    echo($agreementsSum - $transactionsSum) . '&nbsp;руб.';
                ?>
                <br /><br /><br /><br />
                
                <strong class="text-danger">
                <?php
                    echo($agreementsAbortedSum - $transactionsAbortedSum) . '&nbsp;руб.';
                ?>
                </strong>
                
            </td>
    </tr>  
    
    <?php if (is_array($noLeadsArray)):?>
            <?php foreach ($noLeadsArray as $sourceId=>$noLeadsSource):?>
                <tr>
                    <td>
                        <?php echo $channelsArray[$sourceId]['name'];?>
                    </td>
                    <td>
                        Консульт: <?php echo (int)$noLeadsSource;?>
                    </td>
                    <td>
                        <?php echo (int)$noLeadsAgreementsArray[$sourceId]['counter'];?>
                        <span class='muted'>
                            <?php
                                if ((int)$noLeadsSource) {
                                    echo ' (' . round(((int)$noLeadsAgreementsArray[$sourceId]['counter'] / (int)$noLeadsSource)*100) . '%)';
                                }
                            ?>
                        </span>
                        
                        <br /><br />
                        
                        <strong class='text-danger'>Раст: 
                            <?php echo (int)$noLeadsAgreementsAbortedArray[$sourceId]['aborted']['counter'];?>
                        </strong>
                    </td>
                    <td>
                        
                    </td>
                </tr>
            <?php endforeach;?>
        <?php endif;?>
    <?php endforeach; // перебираем месяцы?>            
    <?php endforeach; // перебираем годы?>
</table>