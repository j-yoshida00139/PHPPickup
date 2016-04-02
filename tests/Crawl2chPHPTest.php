<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Console\Commands\Crawl2chPHP;

class Crawl2chPHPTest extends TestCase
{
    public $crawler;
    
    protected function setup(){
        $this->crawler = new Crawl2chPHP();
    }
    /**
     * Check the date string. Both 03/01 and 3/1 are OK as format.
     * @return void
     */
    public function testExtractDate()
    {
        $dateStrOrg = array("Today is 2015/3/1.", "Tomorrow is 2015/03/02.");
        $dateStr    = array("2015/3/1",           "2015/03/02");
        for($i=0; $i<count($dateStrOrg); $i++){
            $date = $this->crawler->extractDate($dateStrOrg[$i]);
            $this->assertEquals($date, new \DateTime($dateStr[$i]));
        }
    }
    
    public function testCalcDayOfWeek()
    {
        $dateStrArray      = array("2016/4/1", "2016/04/02");
        $dayOfWeekArray    = array("金"       , "土");
        for($i=0; $i<count($dateStrArray); $i++){
            $day = $this->crawler->calcDayOfWeek($dateStrArray[$i]);
            $this->assertEquals($day, $dayOfWeekArray[$i]);
        }
    }
    
    public function testGetInnerText(){
        $innerText = "Example.com";
        $aStr = "<a href=\"http://www.example.com\" name=\"aTag\">".$innerText."</a>";
        $this->assertEquals($this->crawler->getInnerText($aStr), $innerText);
    }
    
}
