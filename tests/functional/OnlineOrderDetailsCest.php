<?php


class OnlineOrderDetailsCest
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
    public function onlineOrderFromMenuTest(FunctionalTester $I)
    {
        $I->removePopUps();
        $I->login();
        $this->selectOrderDetails($I);
        $this->checkAndVerifyOrderDetails($I);
    }

    /**
     * select order detail page
     * select first product
     * compare shown detail of cost, status,
     * order number with in detail page
     *
     */
    private function selectOrderDetails(FunctionalTester $I)
    {
        if ($this->selectOrderOption($I)) {
            //Get product details
            $orderNumber = $I->getInputText('.ohw-tr div:nth-child(2) .giveorder1:eq(0)');
            $cost = (float) $I->extractAdditionalFromSelector('.ohw-tr div:nth-child(3) .giveorder1:eq(0)');
            $status = $I->getInputText('.ohw-tr div:nth-child(4) .giveorder1:eq(0)');
            if($this->goToDetailAndReviewPage($I)){
                $detailedPageOrderNo =  $I->getInputText('.row div:nth-child(4) .soh-th-last span');
                $detailedPageCost = (float) $I->extractAdditionalFromSelector('.amountright.blackbig');
                $detailedPageStatus = $I->getInputText('.row div:nth-child(1) .soh-th span');
                if(($orderNumber == $detailedPageOrderNo) && ($cost == $detailedPageCost) && ($status == $detailedPageStatus)){
                    return true;
                }else{
                    $I->assertFalse(true);
                }
            }else{
                $I->assertFalse(true);
            }
        } else {
            $I->assertFalse(true);
        }
    }

    private function goToDetailAndReviewPage(FunctionalTester $I)
    {
        if($this->isDetailReviewButtonVisible($I)){
            $I->customClick('.ohw-tr div:nth-child(5) a:eq(0)');
            return $this->isDetailedOrderPageVisible($I);
        }else{
            return false;
        }

    }

    private function isDetailedOrderPageVisible(FunctionalTester $I)
    {
        return $I->visible('.back-link2');
    }

    private function isDetailReviewButtonVisible(FunctionalTester $I)
    {
        return $I->visible('.ohw-tr div:nth-child(5) a');
    }

    private function selectOrderOption(FunctionalTester $I)
    {
        if ($this->isAccountButtonVisible($I)) {
            $I->click('.user-ddl:nth-child(2) a img');
            if ($this->waitForOptionsToBeVisible($I)) {
                $I->customClick('.user-ddl:nth-child(2) .account_dd a:nth-child(2) span');
            }
            return $this->waitForOptionsToBeVisible($I);
        } else {
            return false;
        }
    }

    private function waitForOptionsToBeVisible(FunctionalTester $I)
    {
        return $I->visible('.user-ddl:nth-child(2) .account_dd a');
    }

    private function isAccountButtonVisible(FunctionalTester $I)
    {
        return $I->visible('.user-ddl:nth-child(2) img');
    }

    /**
     *
     * If finds a product in stock
     * selects quantity
     * checkouts through checkout Notification
     *
     */
    private function checkAndVerifyOrderDetails(FunctionalTester $I)
    {

    }

    private function waitForProductsToLoad(FunctionalTester $I)
    {

    }


}
