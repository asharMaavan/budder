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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

   /**
    * Define custom actions here
    */

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
}
