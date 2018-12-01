<?php

class PravovedGetTownTest extends CTestCase
{
    public function testGetMoscowId()
    {
        $townName = 'Москва';
        $pravovedTownGetter = PravovedGetTown::getInstance();
        $this->assertEquals($pravovedTownGetter->getTownId($townName), 8);
    }
}
