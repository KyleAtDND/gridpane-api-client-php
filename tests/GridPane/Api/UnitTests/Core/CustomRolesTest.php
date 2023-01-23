<?php

namespace KyleWLawrence\GridPane\API\UnitTests\Core;

use KyleWLawrence\GridPane\API\UnitTests\BasicTest;

class CustomRolesTest extends BasicTest
{
    public function testRoutes()
    {
        $this->assertTrue(method_exists($this->client->customRoles(), 'findAll'));
    }
}
