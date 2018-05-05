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
        $this->removePopUps($I);
        $this->goToLogin($I);
        $this->fillUpCredentials($I);
    }

    private function removePopUps(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        $I->maximizeWindow();
        if($this->isLoaderVisible($I)){
            $this->isLoaderInvisible($I);
        }
        $this->isAboveAgePopUpVisible($I);
    }

    private function isAboveAgePopUpVisible(FunctionalTester $I)
    {
        if($this->isAgeButtonVisible($I)){
            $I->click('.age-pbtn1');
        }
    }

    private function isAgeButtonVisible(FunctionalTester $I)
    {
        return $I->visible('.age-pbtn1');
    }

    private function isLoaderVisible(FunctionalTester $I){
        //#loading
        return $I->visible('#loading');
    }

    private function isLoaderInvisible(FunctionalTester $I){
        //#loading
        return $I->visible('#loading');
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
            $I->fillField(['name' => 'loginEmail'], "habib.maava2n@gmail.com");
            $I->fillField(['name' => 'loginPassword'], "test123");

            //Click to Login
            $I->customClick('.chek-login-btn');

            //Login Confirm
            if($this->isLoggedIn($I)){
                return true;
            }else{
                $I->assertFalse(true);
            }
        }else{
            $I->assertFalse(true);
        }
    }

    private function isLoggedIn(FunctionalTester $I)
    {
        return $I->visible('#menuBtn');
    }


}
