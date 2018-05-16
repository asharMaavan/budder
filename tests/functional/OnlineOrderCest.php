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
        $I->login();
        $this->selectProductCategory($I);
        $this->selectDispensary($I);
        $this->selectProduct($I);
        $this->buyProductConfirmAndVerification($I);
    }

    //checks and select product category
    public function selectProductCategory(FunctionalTester $I)
    {
        //select random type of category
        if ($this->isProductCategoriesType1Visible($I)) {
            return $this->randomCat($I,'.deliver-content.catweb');
        }elseif($this->isProductCategoriesType2Visible($I)){
            return $this->randomCat($I,'.product-group');
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
        $categoryLength = $I->getInputLength($selector.'div a');
        if ($categoryLength > 0) {
            $categoryNo = rand(0, count($categoryLength)-1);
            $I->click($selector.'div:nth-child('.$categoryNo.') a');
        } else {
            $I->assertFalse(true);
        }
        return $this->isCategorySelectedConfirmation($I);
    }

    private function isCategorySelectedConfirmation(FunctionalTester $I)
    {
        return $I->visible('.popup-wrapper div a:nth-child(2)');
    }

    public function selectDispensary(FunctionalTester $I)
    {
        if($this->isCategorySelectedConfirmation($I)){
            $I->click('.popup-wrapper div a:nth-child(2)');
            if($this->isDispensaryPageInvisible($I)){
                return true;
            }else{
                $I->assertFalse(true);
            }
        }else{
            $I->assertFalse(true);
        }
    }

    private function isDispensaryPageInvisible(FunctionalTester $I)
    {
        return $I->invisible('.popup-wrapper div a:nth-child(2)');
    }

    public function selectProduct(FunctionalTester $I)
    {
        if($this->isProductPageVisible($I)){
            $productLength = $I->getInputLength('.pl-box-overlay.product-detail');
            if($productLength > 0){
                $productNo = rand(0, count($productLength)-1);
                //select Product
                $I->click('.pl-box .pl-box-overlay.product-detail:nth-child('.$productNo.')');

            }else{
                //if product was not found, select another product
            }
        }else{
            $I->assertFalse(true);
        }
        $this->productSelectedConfirmation($I);
    }

    private function productSelectedConfirmation(FunctionalTester $I)
    {
        //This means product is in stock
        if($this->isProductInStockVisible($I)){
            //add to cart
            $I->click('.cartAddBtn');
            if($this->isCartItemsVisible($I)){
                return true;
            }else{
                return false;
            }
        }else{
            //select a new product
        }
    }

    public function buyProductConfirmAndVerification(FunctionalTester $I)
    {
        if($this->isCartItemsVisible($I)){
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

    private function isCartItemsVisible(FunctionalTester $I)
    {
        return $I->visible('.cart-text a');
    }

    private function isProductInStockVisible(FunctionalTester $I)
    {
        return $I->visible('//*[contains(text(),\'In Stock\')]');
    }
    private function isProductPageVisible(FunctionalTester $I)
    {
        return $I->visible('.pl-box-overlay.product-detail');
    }

}
