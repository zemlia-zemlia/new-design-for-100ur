<?php

use Codeception\Util\HttpCode;

class SendLeadCest
{
    public function _before(ApiTester $I)
    {

    }

    /**
     * @param ApiTester $I
     * @dataProvider providerSendLead
     */
    public function trySendLeadTest(ApiTester $I, \Codeception\Example $example)
    {
        if ($example['method'] == 'GET') {
            $I->sendGET('/api/sendLead');
        } else {
            $I->sendPOST('/api/sendLead', $example['requestData']);
        }

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($example['expectedJson']);
    }

    protected function providerSendLead(): array
    {
        return [
            [
                'method' => 'GET',
                'requestData' => [],
                'expectedJson' => ['code' => 400, 'message' => 'No input data'],
            ],
            [
                'method' => 'POST',
                'requestData' => [],
                'expectedJson' => ['code' => 400],
            ],
        ];
    }
}
