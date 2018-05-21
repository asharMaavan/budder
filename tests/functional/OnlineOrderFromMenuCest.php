<?php


class OnlineOrderFromMenuCest
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
     * Select Product type Using Menu
     * Select products
     * Check out products if in stock
     *
     */
    public function onlineOrderFromMenuTest(FunctionalTester $I)
    {
        $I->removePopUps();
        $I->login();
        $this->selectCategoryFromMenu($I);
        $this->buyShownProductIfInStock($I);
    }

    //checks and select category and product
    private function selectCategoryFromMenu(FunctionalTester $I)
    {
        if ($this->isMenuPageVisible($I)) {
            $I->click('#menuBtn');
            if ($this->isCategoryListVisible($I)) {
                $catLength = $I->getInputLength('#menudropdown ul a');
                for ($cat = 1; $cat <= $catLength; $cat++) {
                    $I->click('#menudropdown ul a:nth-child(' . $cat . ')');
                    if ($I->isCategorySelectedConfirmation()) {
                        $I->selectDispensary();
                    }
                    if ($this->waitForProductsToLoad($I)) {
                        $productLength = $I->getInputLength('[ng-if="productsCount > 0"] .pl-box-img.product-detail');
                        if ($productLength > 0) {
                            $productNo = rand(1, $productLength);
                            //select Product
                            $I->customClick('.pl-box .pl-box-overlay.product-detail:eq(' . $productNo . ')');
                            break;
                        }
                    }
                }
            } else {
                $I->assertFalse(true);
            }
        } else {
            $I->assertFalse(true);
        }
    }

    /**
     *
     * If finds a product in stock
     * selects quantity
     * checkouts through checkout Notification
     *
     */
    private function buyShownProductIfInStock(FunctionalTester $I)
    {
        if ($I->isProductInStockVisible()) {
            $I->addProductToShop();
            if ($I->isGoToCheckoutVisible()) {
                $I->click('//*[contains(text(),\'Go to checkout\')]');
                if ($I->isBuyProductPageVisible()) {
                    $I->click('.row .checkout-btn');
                    //Confirm product checked out
                    $I->productCheckedOutConfirmation();
                } else {
                    //quantity does not match
                    $I->assertFalse(true);
                }
            } else {
                $I->assertFalse(true);
            }
        }
        return true;
    }

    private function waitForProductsToLoad(FunctionalTester $I)
    {
        if ($this->isLoaderVisible($I)) {
            if ($this->isLoaderInvisible($I)) {
                return $this->isProductVisible($I);
            }
            return false;
        } else {
            return $this->isProductVisible($I);
        }
    }

    private function isLoaderVisible(FunctionalTester $I)
    {
        return $I->visible('#loading');
    }

    private function isLoaderInvisible(FunctionalTester $I)
    {
        return $I->invisible('#loading');
    }

    private function isProductVisible(FunctionalTester $I)
    {
        return $I->visible('[ng-if="productsCount > 0"]');
    }

    private function isMenuPageVisible(FunctionalTester $I)
    {
        return $I->visible('#menuBtn');
    }

    private function isCategoryListVisible(FunctionalTester $I)
    {
        return $I->visible('#menudropdown ul a');
    }


}
