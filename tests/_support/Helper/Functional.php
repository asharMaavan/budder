<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    public function findElementPresent($selector)
    {
        return $this->getModule('WebDriver')->_findElements($selector);
    }

    public function setIncrementValue($selector, $value){
        $this->getModule('WebDriver')->executeJS("$('$selector').val($value).trigger('change');");
    }

    public function customClick($selector)
    {
        $this->getModule('WebDriver')->executeJS("$('$selector').click();");
    }

    public function dblClick($selector)
    {
        $this->getModule('WebDriver')->executeJS("$('$selector').dblclick();");
    }

    public function getValueFromInput($selector){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').val();");
    }

    public function addClassTo($selector,$class){
        return $this->getModule('WebDriver')->executeJS(" $('$selector').addClass('$class');");
    }

    public function getInputText($selector){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').text().trim();");
    }

    public function getHasClass($selector, $className){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').hasClass('$className');");
    }

    public function getInputLength($selector){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').length;");
    }

    public function getElementAttribute($selector, $attribute){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').attr('$attribute');");
    }

    public function setElementAttribute($selector, $attribute){
        $this->getModule('WebDriver')->executeJS("$('$selector').attr('$attribute','$attribute');");
    }

    public function getElementCss($selector, $css){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').css('$css');");
    }

    public function setElementCss($selector, $value){
        $this->getModule('WebDriver')->executeJS("$('$selector').css('$value');");
    }

    public function focusOnSelector($selector){
        $this->getModule('WebDriver')->executeJS("$($selector').trigger('change').trigger('focus');");
    }

    //confirms that checkbox is checked or not
    public function getCheckboxStatus($selector){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').is(':checked');");
    }

    public function getCheckboxProp($selector){
        return $this->getModule('WebDriver')->executeJS("return $('$selector').prop('checked');");
    }

    public function getMouseOver($selector, $trigger){
        $this->getModule('WebDriver')->executeJS("$('$selector').trigger('$trigger');");
    }

    public function useJquery($selector, $val){
        $this->getModule('WebDriver')->executeJS("var a = angular.element('[ng-model=\"$selector\"]').scope();
          $.apply(a.$selector = '$val');
        ");
    }

    public function useJqueryWithMulti($selector, $val1, $val2){
        $this->getModule('WebDriver')->executeJS("var a = angular.element('[ng-model=\"$selector\"]').scope();
          $.apply(a.$selector[0] = '$val1');
          $.apply(a.$selector[1] = '$val2');
        ");
    }

    public function getCurrentUrl()
    {
        return $this->getModule('WebDriver')->executeJS('return document.getElementById("demo").innerHTML =
        window.location.href;');
    }

    public function removePopUp()
    {
        $js_confirm = 'window.confirm = function(){ return false; }';
        //$I->executeJS($js_confirm);
        return $this->getModule('WebDriver')->executeJS($js_confirm);
    }

}