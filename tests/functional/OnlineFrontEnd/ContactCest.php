<?php


class OnlineContactCest
{

    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     *
     * Online Store
     * Logs In
     * Go to Profile
     * Select Order Details Page
     * Check and Verifies details
     *
     */
    public function onlineContactUsTest(FunctionalTester $I)
    {
        $I->removePopUps();
        $I->login();
        $this->goToContactPage($I);
        $this->fillUpContactFormAndSubmit($I);
    }

    /**
     *
     * go to contacts page
     *
     */
    private function goToContactPage(FunctionalTester $I)
    {
        if ($I->isMenuPageVisible()) {
            $I->customClick('.header-bottom-content .nav-btn:eq(0)');
            if($this->isOnContactPage($I)){
                return true;
            }else {
                $I->assertFalse(true);
            }
        } else {
            $I->assertFalse(true);
        }
    }

    private function isOnContactPage(FunctionalTester $I)
    {
        return $I->visible('.con-name');
    }

    /**
     *
     * fill ups form
     * submits email
     * confirm email success message
     *
     */
    private function fillUpContactFormAndSubmit(FunctionalTester $I)
    {
        if($this->isOnContactPage($I)){
            $I->fillField('.con-name', "Automated Test");
            $I->fillField(['name' => 'emailField'], "qa.budder@gmail.com");
            $I->fillField(['name' => 'phoneField'], "3335568956");
            $I->fillField(['name' => 'subjectField'], "Automated Test");
            $I->fillField('.contact-textarea', "Automation is Testing the email System For Contact");

            //submit
            if($this->isSendButtonVisible($I)){
                $I->click('#btnsubmit');
                return $this->isSendEmailSuccessVisible($I);
            }else{
                $I->assertFalse(true);
            }
        }else{
            $I->assertFalse(true);
        }

    }

    private function isSendButtonVisible(FunctionalTester $I)
    {
        return $I->visible('#btnsubmit');
    }


    private function isSendEmailSuccessVisible(FunctionalTester $I)
    {
        return $I->visible('#success-msg');
    }
}
