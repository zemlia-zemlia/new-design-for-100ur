<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
    public function clearTable($table)
    {
        $db = $this->getModule('Db')->_getDriver();
        $db->load(["TRUNCATE TABLE `$table`"]);
    }
}
