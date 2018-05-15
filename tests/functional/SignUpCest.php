<?php


class SignUpCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // sign up
    /**
     *
     * confirms the url
     * check for above age popup
     * create account fill up form
     * successful account creation confirmation
     *
     */
    public function signUpTest(FunctionalTester $I)
    {
        $this->removePopUps($I);
        $this->goToSignUp($I);
        $this->fillUpForm($I);
    }

    private function removePopUps(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        dd($I->maximizeWindow());
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

    private function isRegistrationButtonVisible(FunctionalTester $I)
    {
        return $I->visible('.reg-btn');
    }

    private function goToSignUp(FunctionalTester $I)
    {

        if($this->isRegistrationButtonVisible($I)){
            $I->click('.reg-btn');
            if($this->isUserFirstNameVisible($I)){
                return true;
            }else{
                $I->assertFalse(true);
            }
        }else{
            $I->assertFalse(true);
        }
    }

    private function isUserFirstNameVisible(FunctionalTester $I)
    {
        return $I->visible('[name="firstName"]');
    }

    private function isCreateButtonInvisible(FunctionalTester $I)
    {
        return $I->invisible('.creat-btn');
    }

    private function isCreateButtonVisible(FunctionalTester $I)
    {
        return $I->visible('.creat-btn');
    }

    private function fillUpForm(FunctionalTester $I)
    {
        if($this->isUserFirstNameVisible($I)){
            $I->fillField(['name' => 'firstName'], "First Name");
            $I->fillField(['name' => 'lastName'], "Last Name");
            //generate random email username
            $I->fillField(['name' => 'email'], $this->emailUsername($I).'@gmail.com');
            $I->fillField(['name' => 'phone'], "3435659876");

            //Select Date Of Birth
            $this->selectDob($I);

            $I->fillField(['name' => 'street'], "12");
            $I->fillField(['name' => 'city'], "Lahore");
            $I->fillField(['name' => 'zip'], "54056");

            //upload Documents
            $I->attachFile('#dlFile', 'test.png');
            $I->fillField(['name' => 'dlNumber'], "P420");
            //select License Expiry
            $this->selectExpiryDate($I);

            $password = $this->emailUsername($I);
            $I->fillField(['name' => 'password'], $password);
            $I->fillField(['name' => 'confirmPassword'], $password);

            //terms and conditions
            $I->customClick('#squaredtwo');
            //create account
            if($this->createAccount($I)){
                $I->assertTrue($this->isMenuButtonVisible($I));
            }else{
                $I->assertFalse(true);
            }

        }else{
            $I->assertFalse(true);
        }
    }

    private function isMenuButtonVisible(FunctionalTester $I){
        return $I->visible('#menuBtn');
    }

    private function createAccount(FunctionalTester $I)
    {
        //create account click
        if($this->isCreateButtonVisible($I)){
            $I->click('Create Account');
            return $this->isCreateButtonInvisible($I);
        }else{
            return false;
        }
    }

    private function isCalendarVisible(FunctionalTester $I)
    {
        return $I->visible('.ui-datepicker-prev.ui-corner-all',45);
    }

    private function emailUsername(FunctionalTester $I){

        $string = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for($i = 0; $i <= 10; $i++){
            $string.=substr($chars,rand(0,strlen($chars)),1);
        }
        return $string;
    }

    private function selectDob(FunctionalTester $I)
    {
        //select Calendar date
        $I->executeJS("$('#Dob').trigger('change').trigger('focus');");
        $I->wait(2);
        $I->executeJS("$('#Dob').trigger('change').trigger('focus');");

        if($this->isCalendarVisible($I)){
            //click previous month
            $I->click('.ui-datepicker-prev.ui-corner-all');
        }else{
            $I->assertFalse(true);
        }
        $I->selectOption('.ui-datepicker-title select:nth-child(2)', '1990');
        $I->wait(2);
        $I->click('tr:nth-child(2) .ui-datepicker-week-end:nth-child(1)');
    }

    private function selectExpiryDate(FunctionalTester $I)
    {
        //select Calendar date
        $I->executeJS("$('[name=\"dlExpiry\"]').trigger('change').trigger('focus');");
        $I->wait(2);
        $I->executeJS("$('[name=\"dlExpiry\"]').trigger('change').trigger('focus');");

        if($this->isCalendarVisible($I)){
            //click previous month
            $I->click('.ui-datepicker-next.ui-corner-all');
        }else{
            $I->assertFalse(true);
        }

        $I->wait(2);
        $I->click('tr:nth-child(2) .ui-datepicker-week-end:nth-child(1)');
    }
}
