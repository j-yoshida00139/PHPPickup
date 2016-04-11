<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Console\Commands\Crawl2chPage;

class Crawl2chPageTest extends TestCase
{
    public $crawler;
    
    protected function setup(){
        $this->crawler = new Crawl2chPage();
    }

    
}
