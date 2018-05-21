<?php


class OnlineOrderCest
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
     * Select Product type
     * Add products
     * Check out products
     *
     */
    public function onlineOrderTest(FunctionalTester $I)
    {
        $I->removePopUps();
        $I->login();
        $this->selectProductCategory($I);
        $this->selectDispensary($I);
        $this->selectProduct($I);
        $this->buyProductConfirmAndVerification($I);
    }

    //checks and select product category
    private function selectProductCategory(FunctionalTester $I)
    {
        $this->scrollToProducts($I);
        //select random type of category
        if ($this->isProductCategoriesType1Visible($I)) {
            return $this->randomCat($I,'.deliver-content.catweb');
        }elseif($this->isProductCategoriesType2Visible($I)){
            return $this->randomCat($I,'.product-group');
        }else{
            $I->assertFalse(true);
        }
    }

    private function scrollToProducts(FunctionalTester $I)
    {
        if($I->visible('.deliver-content.catweb')){
            $I->scrollTo('.deliver-content.catweb', 20, 40);
        }elseif($I->visible('.product-group')){
            $I->scrollTo('.product-group', 20, 40);
        }else{
            $I->assertFalse(true);
        }
    }

    private function isProductCategoriesType1Visible(FunctionalTester $I)
    {
        return $I->visible('.deliver-content.catweb div a');
    }

    private function isProductCategoriesType2Visible(FunctionalTester $I)
    {
        return $I->visible('.product-group div a');
    }

    private function randomCat(FunctionalTester $I, $selector)
    {
        //select random type of category
        $categoryLength = $I->getInputLength($selector.' div a');
        if ($categoryLength > 0) {
            $categoryNo = rand(1, $categoryLength);
            $I->click($selector.' div:nth-child('.$categoryNo.') a');
        } else {
            $I->assertFalse(true);
        }
        return $this->isCategorySelectedConfirmation($I);
    }

    private function isCategorySelectedConfirmation(FunctionalTester $I)
    {
        return $I->visible('.popup-wrapper div .ws-btn3:nth-child(1)');
    }

    private function selectDispensary(FunctionalTester $I)
    {
        if($this->isCategorySelectedConfirmation($I)){
            $I->customClick('.popup-wrapper div .ws-btn3:nth-child(1)');
            if($this->isDispensaryPageInvisible($I)){
                return true;
            }else{
                $I->assertFalse(true);
            }
        }else{
            if($this->amOnProductsPage($I)){
                return true;
            }else{
                //click Home button
                $I->customClick('.nav-btn:eq(2)');
                if($this->isHomePageVisible($I)){
                    $this->selectProductCategory($I);
                    $this->selectDispensary($I);
                    return false;
                }else{
                    $I->assertFalse(true);
                }
            }
        }
    }

    private function amOnProductsPage(FunctionalTester $I)
    {
        return $I->visible('.pl-box .pl-box-overlay.product-detail:nth-child(1)');
    }

    private function isDispensaryPageInvisible(FunctionalTester $I)
    {
        return $I->invisible('.popup-wrapper div .ws-btn3:nth-child(1)');
    }

    private function scrollToSelectProduct(FunctionalTester $I)
    {
        if($I->visible('.product-detail')){
            $I->scrollTo('.pl-box .pl-box-overlay.product-detail', 20, 40);
        }
    }

    private function selectProduct(FunctionalTester $I)
    {
        if($this->isProductPageVisible($I)){
            $this->scrollToSelectProduct($I);
            $productLength = $I->getInputLength('[ng-if="productsCount > 0"] .pl-box-img.product-detail');
            if($productLength > 0){
                $productNo = rand(1, $productLength);
                //select Product
                $I->customClick('.pl-box .pl-box-overlay.product-detail:eq('.$productNo.')');
            }else{
                //if product was not found, select another product
                $this->redirectToSelectNewProduct($I);
            }
        }else{
            //if product was not found, select another product
            $this->redirectToSelectNewProduct($I);
        }
        $this->productSelectedConfirmation($I);
    }

    private function isHomePageVisible(FunctionalTester $I)
    {
        return $I->invisible('.chosen-choices');
    }

    private function productSelectedConfirmation(FunctionalTester $I)
    {
        //This means product is in stock
        if($this->isProductInStockVisible($I)){
            return $this->isCartItemsVisible($I);
        }else{
            //select a new product
            $this->redirectToSelectNewProduct($I);
            return false;
        }
    }

    private function redirectToSelectNewProduct(FunctionalTester $I)
    {
        //click Home button
        $I->customClick('.nav-btn:eq(2)');
        if($this->isHomePageVisible($I)){
            $this->selectProductCategory($I);
            $this->selectDispensary($I);
            $this->selectProduct($I);
            return false;
        }else{
            $I->assertFalse(true);
        }
    }

    private function addProductToShop(FunctionalTester $I)
    {
        //select product to cart
        if($this->isProductItemCartVisible($I)){
            $I->customClick('tr:nth-child(1) .cartAddBtn');
            return $this->isCartItemsVisible($I);
        }else{
            return false;
        }
    }

    private function isProductItemCartVisible(FunctionalTester $I)
    {
        return $I->visible('tr:nth-child(1) .cartAddBtn');
    }

    private function buyProductConfirmAndVerification(FunctionalTester $I)
    {
        if($this->addProductToShop($I) || $this->isGoToCheckoutVisible($I)){
            if($this->isGoToCheckoutInVisible($I)){
                $I->click('.cart-text a');
                if($this->isBuyProductPageVisible($I)){
                    $quantityValue = $I->getValueFromInput('[name="quantity"]');
                    if($quantityValue == "1"){
                        $I->click('.row .checkout-btn');

                        //Confirm product checked out
                        $this->productCheckedOutConfirmation($I);
                    }else{
                        //quantity does not match
                        $I->assertFalse(true);
                    }
                }else{
                    $I->assertFalse(true);
                }
            }else{
                $I->assertFalse(true);
            }
        }else{
            $I->assertFalse(true);
        }
    }

    private function productCheckedOutConfirmation(FunctionalTester $I)
    {
        if($this->isThankYouPageVisible($I)){
            return true;
        }else{
            //Failure
            $I->assertFalse(true);
        }
    }

    private function isThankYouPageVisible(FunctionalTester $I)
    {
        return $I->visible('//p[contains(text(),\'Thank You\')]');
    }

    private function isBuyProductPageVisible(FunctionalTester $I)
    {
        return $I->visible('[name="quantity"]');
    }

    private function isGoToCheckoutVisible(FunctionalTester $I)
    {
        return $I->visible('//*[contains(text(),\'Go to checkout\')]');
    }

    private function isGoToCheckoutInVisible(FunctionalTester $I)
    {
        return $I->invisible('//*[contains(text(),\'Go to checkout\')]');
    }

    private function isCartItemsVisible(FunctionalTester $I)
    {
        return $I->visible('.cart-text a',60);
    }

    private function isProductInStockVisible(FunctionalTester $I)
    {
        return $I->visible('//*[contains(text(),\'In Stock\')]');
    }
    private function isProductPageVisible(FunctionalTester $I)
    {
        return $I->visible('.search-field');
    }

}
