<?php

namespace models;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreate()
    {
        $user = new \User();
        $user->name = 'Vasya Pupkin';

        $saveResult = $user->save();
        $this->assertEquals(false, $saveResult);
        $this->tester->dontSeeInDatabase('100_user', ['name' => 'Vasya Pupkin']);

    }
}