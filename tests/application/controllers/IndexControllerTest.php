<?php
class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    
//     public function testCanDoUnitTest() {
//         $this->assertTrue(true);
//     }
    
    
    public function setUp()
    {
    	$application = new Zend_Application(
    			APPLICATION_ENV,
    			APPLICATION_PATH . '/configs/application.ini'
    	);
    
    	$this->bootstrap = $application;
    	return parent::setUp();
    }
    
    
    
    // Test of html
    public function testHtmlResult()
    {
    	$this->dispatch('/index/index');
    	$this->assertResponseCode(200);
    	$this->assertQueryContentContains('h1', 'WHAT TO COOK FOR TONIGHT ?');
    	$this->assertQuery('form#fridge_form');
    }
}
?>