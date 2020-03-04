<?php

namespace Tests\integration\models;

use Tests\Factories\UserFactory;
use Tests\integration\BaseIntegrationTest;
use TransactionCampaign;
use User;
use Yii;

class TransactionCampaignTest extends BaseIntegrationTest
{
    const MONEY_TABLE = '100_money';
    const USER_TABLE = '100_user';
    const CAMPAIGN_TRANSACTIONS_TABLE = '100_transactionCampaign';

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(self::USER_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::MONEY_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::CAMPAIGN_TRANSACTIONS_TABLE);
    }

    private function loadFixtures(): void
    {
        $usersFixture = [
            (new UserFactory())->generateOne([
                'name' => 'Юрист при деньгах',
                'role' => User::ROLE_JURIST,
                'balance' => 100000,
                'id' => 10004,
            ]),
            (new UserFactory())->generateOne([
                'name' => 'Юрист без денег',
                'role' => User::ROLE_JURIST,
                'balance' => 1000,
                'id' => 10005,
            ]),
        ];

        $this->loadToDatabase(self::USER_TABLE, $usersFixture);
    }

    /**
     * @dataProvider providerApproveRequest
     *
     * @param int  $userId
     * @param int  $requestAmount
     * @param int  $accountId
     * @param bool $expectedResult
     * @param int  $expectedBalance
     *
     * @throws \CException
     */
    public function testApproveRequest($userId, $requestAmount, $accountId, $expectedResult, $expectedBalance)
    {
        $this->loadFixtures();

        $transaction = new TransactionCampaign();
        $transaction->buyerId = $userId;
        $transaction->sum = $requestAmount;
        $transaction->status = TransactionCampaign::STATUS_PENDING;
        $transaction->description = 'some description';

        $approveRequestResult = $transaction->approveRequest($accountId);

        $this->assertEquals($expectedResult, $approveRequestResult);
        if (true == $expectedResult) {
            $this->tester->seeInDatabase(self::MONEY_TABLE, ['value' => $transaction->sum]);
        }
        $this->tester->seeInDatabase(self::USER_TABLE, ['balance' => $expectedBalance]);
    }

    /**
     * @return array
     */
    public function providerApproveRequest(): array
    {
        return [
            'rich yurist, success' => [
                'userId' => 10004,
                'requestAmount' => 50000,
                'accountId' => 9,
                'expectedResult' => true,
                'expectedBalance' => 50000,
            ],
            'rich yurist, full withdrawal' => [
                'userId' => 10004,
                'requestAmount' => 100000,
                'accountId' => 9,
                'expectedResult' => true,
                'expectedBalance' => 0,
            ],
            'poor yurist' => [
                'userId' => 10005,
                'requestAmount' => 100000,
                'accountId' => 9,
                'expectedResult' => false,
                'expectedBalance' => 1000,
            ],
        ];
    }
}
