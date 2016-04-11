<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Console\Commands\CurlUtility;

class CurlUtilityTest extends TestCase
{
    public $curlUtil;
    
    protected function setup(){
        $this->curlUtil = new CurlUtility();
    }

    public function testGetInnerText(){
        $innerText = "Example.com";
        $aStr = "<a href=\"http://www.example.com\" name=\"aTag\">".$innerText."</a>";
        $this->assertEquals($this->curlUtil->getInnerText($aStr), $innerText);
    }
    
    
}


