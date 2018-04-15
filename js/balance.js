window.onload = function () {

    var userSumField = $('.balance-form input[name="user-sum"]');
    var paymentTypeField = $('input[name=paymentType]');

            
    // при загрузке страницы рассчитываем сумму, которая будет зачислена на счет
    calculateAmountDue(userSumField);

    // при изменении пользовательской суммы делаем пересчет
    userSumField.on('keyup', function (e) {
        calculateAmountDue(userSumField);
    });
    
    // при изменении типа оплаты делаем пересчет
    paymentTypeField.on('change', function (e) {
        calculateAmountDue(userSumField);
    });
    

    /*
     * Расчет суммы, которая будет зачислена на баланс при пополнении, 
     * с учетом комиссий Яндекса
     * field - JQuery объект поля с вводимой пользователем суммой
     */
    function calculateAmountDue(field)
    {
        var userSumField = field;
        var amount_due = userSumField.val();
        var paymentType = field.closest('form').find("input[name=paymentType]:checked").val();

        switch (paymentType) {
            case 'PC':
                // оплата яндекс деньгами
                var a = 0.005;
                sum = amount_due / (1 - a/(1+a));
                break;
            case "AC":
                // оплата картой
                a = 0.02;
                sum = amount_due / (1-a);
                break;
        }

        // округляем до копеек
        sum = Math.round(sum * 100) / 100;

//        console.log('Input sum:' + userSumField.val());
//        console.log('payment type:' + paymentType);
//        console.log('Sum:' + sum);
        
        var resultSumField = $('.balance-form input[name="sum"]');
        resultSumField.val(sum);
        $('#sum-for-pay').text(sum);

    }
}

