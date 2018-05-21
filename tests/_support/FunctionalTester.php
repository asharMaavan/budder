<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * Define custom actions here
     */

    public function removePopUps()
    {
        $this->amOnPage('/signup');
        $this->maximizeWindow();
        if ($this->isLoaderVisible()) {
            $this->isLoaderInvisible();
        }
        $this->isAboveAgePopUpVisible();
    }

    private function isAboveAgePopUpVisible()
    {
        if ($this->isAgeButtonVisible()) {
            $this->click('.age-pbtn1');
        }
    }

    private function isAgeButtonVisible()
    {
        return $this->visible('.age-pbtn1');
    }

    public function isLoaderVisible()
    {
        //#loading
        return $this->visible('#loading', 10);
    }

    public function isLoaderInvisible()
    {
        //#loading
        return $this->invisible('#loading', 10);
    }

    public function login()
    {

        if ($this->isLoginButtonVisible()) {
            $this->fillCredentials();
            if ($this->signInConfirm()) {
                return true;
            } else {
                $this->assertFalse(true);
            }
        } else {
            $this->assertFalse(true);
        }
    }

    public function signInConfirm()
    {
        if ($this->isMenuPageVisible()) {
            return true;
        } else {
            //re confirm
            if ($this->isHomePageVisible()) {
                return true;
            } else {
                $this->assertFalse(true);
            }
        }
    }

    public function isMenuPageVisible()
    {
        return $this->visible('#menuBtn');
    }

    public function isHomePageVisible()
    {
        return $this->visible('#prodSearch');
    }

    public function fillCredentials()
    {
        $this->fillField(['name' => 'loginEmail'], "qa.budder@gmail.com");
        $this->fillField(['name' => 'loginPassword'], "maavan321");
        //Click to Login
        $this->customClick('.chek-login-btn');
    }

    public function isLoginButtonVisible()
    {
        return $this->visible('#loginEmail');
    }

    ///////////////////////////////////////         Waits For Loading or Visiblity Invisibilty        /////////////////////////////////////////////////

    /*
     * Wait For Element Visible
     * required time (Default:30s)
     */
    public function visible($selector, $time = 30)
    {
        try {
            $this->waitForElementVisible($selector, $time);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
     *
     * For using Jquery
     * Wait for scope to change
     * Sometimes takes 2 to 3 minutes to change
     *
     */
    public function waitForScopeToBeChanged($selector, $time = 60)
    {
        $waitForScope = $this->visible($selector, $time);
        if (!$waitForScope) {
            $this->waitForScopeToBeChanged($selector, $time);
        }
        return true;
    }

    /*
     * Wait For Element Invisible
     * required time (Default:30s)
     */
    public function invisible($selector, $time = 30)
    {
        try {
            $this->waitForElementNotVisible($selector, $time);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectDispensary()
    {
        if ($this->isCategorySelectedConfirmation()) {
            $this->customClick('.popup-wrapper div .ws-btn3:nth-child(1)');
            if ($this->isDispensaryPageInvisible()) {
                return true;
            } else {
                $this->assertFalse(true);
            }
        } else {
            if ($this->amOnProductsPage()) {
                return true;
            } else {
                $this->assertFalse(true);
            }
        }
    }

    public function isCategorySelectedConfirmation()
    {
        return $this->visible('.popup-wrapper div .ws-btn3:nth-child(1)');
    }

    public function amOnProductsPage()
    {
        return $this->visible('.pl-box .pl-box-overlay.product-detail:nth-child(1)');
    }

    public function isDispensaryPageInvisible()
    {
        return $this->invisible('.popup-wrapper div .ws-btn3:nth-child(1)');
    }

    public function isProductInStockVisible()
    {
        return $this->visible('//*[contains(text(),\'In Stock\')]');
    }

    public function addProductToShop()
    {
        //select product to cart
        if ($this->isProductItemCartVisible()) {
            $this->customClick('tr:nth-child(1) .cartAddBtn');
            return $this->isCartItemsVisible();
        } else {
            return false;
        }
    }

    public function isCartItemsVisible()
    {
        return $this->visible('.cart-text a', 60);
    }

    public function isProductItemCartVisible()
    {
        return $this->visible('tr:nth-child(1) .cartAddBtn');
    }

    public function isGoToCheckoutVisible()
    {
        return $this->visible('//*[contains(text(),\'Go to checkout\')]');
    }

    public function isBuyProductPageVisible()
    {
        return $this->visible('[name="quantity"]');
    }

    public function productCheckedOutConfirmation()
    {
        if ($this->isThankYouPageVisible()) {
            return true;
        } else {
            //Failure
            $this->assertFalse(true);
        }
    }

    public function isThankYouPageVisible()
    {
        return $this->visible('//p[contains(text(),\'Thank You\')]');
    }

    public function extractAdditionalFromSelector($selector)
    {
        $name = str_replace(
            '$',
            "",
            $this->getInputText('' . $selector . '')
        );
        return $name;
    }


}
