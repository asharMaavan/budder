<?php


class BackendLoginCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // Login
    public function backendLoginTest(FunctionalTester $I)
    {
        $I->backendLogin();
    }

    
}
