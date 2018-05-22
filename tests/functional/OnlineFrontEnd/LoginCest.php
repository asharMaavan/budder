<?php


class LoginCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // Login
    public function loginTest(FunctionalTester $I)
    {
        $I->removePopUps();
        $this->goToLogin($I);
        $this->fillUpCredentials($I);
    }

    private function isLoginButtonVisible(FunctionalTester $I)
    {
        return $I->visible('.user-ddl:nth-child(1) a');
    }

    private function goToLogin(FunctionalTester $I)
    {
        if($this->isLoginButtonVisible($I)){
            $I->click('.user-ddl:nth-child(1) a');
            if($this->isEmailVisible($I)){
                return true;
            }else{
                $I->assertFalse(true);
            }
        }else{
            $I->assertFalse(true);
        }
    }

    private function isEmailVisible(FunctionalTester $I)
    {
        return $I->visible('[name="loginEmail"]');
    }

    private function fillUpCredentials(FunctionalTester $I)
    {
        if($this->isEmailVisible($I)){
            $I->fillField(['name' => 'loginEmail'], "qa.budder@gmail.com");
            $I->fillField(['name' => 'loginPassword'], "maavan321");

            //Click to Login
            $I->customClick('.chek-login-btn');

            //Login Confirm
            if($this->isLoggedIn($I) && $this->isUsernameInvisible($I)){
                return true;
            }else{
                $I->assertFalse(true);
            }
        }else{
            $I->assertFalse(true);
        }
    }

    private function isUsernameInvisible(FunctionalTester $I)
    {
        return $I->invisible(['name' => 'loginEmail']);
    }

    private function isLoggedIn(FunctionalTester $I)
    {
        return $I->visible('#menuBtn');
    }
}
