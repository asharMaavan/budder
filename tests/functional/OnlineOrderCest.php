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
        $I->selectDispensary();
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
        return $I->isCategorySelectedConfirmation();
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
        if($I->isProductInStockVisible()){
            return $I->isCartItemsVisible();
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
            $I->selectDispensary();
            $this->selectProduct($I);
            return false;
        }else{
            $I->assertFalse(true);
        }
    }
    
    private function buyProductConfirmAndVerification(FunctionalTester $I)
    {
        if($I->addProductToShop() || $I->isGoToCheckoutVisible()){
            if($this->isGoToCheckoutInVisible($I)){
                $I->click('.cart-text a');
                if($I->isBuyProductPageVisible()){
                    $quantityValue = $I->getValueFromInput('[name="quantity"]');
                    if($quantityValue == "1"){
                        $I->click('.row .checkout-btn');
                        //Confirm product checked out
                        $I->productCheckedOutConfirmation();
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

    private function isGoToCheckoutInVisible(FunctionalTester $I)
    {
        return $I->invisible('//*[contains(text(),\'Go to checkout\')]');
    }
        
    private function isProductPageVisible(FunctionalTester $I)
    {
        return $I->visible('.search-field');
    }

}
