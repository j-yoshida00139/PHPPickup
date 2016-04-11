<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Link2ch;

class Crawl2chPHP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl2chPHP';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the link to 2ch pages';

    private $curlUtil;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->curlUtil = new curlUtility();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mainUrl = 'http://find.2ch.sc/?STR=php&TYPE=TITLE&x=29&y=9&BBS=ALL&ENCODING=SJIS&COUNT=50';
        $html = $this->curlUtil->getContentsCurl($mainUrl); 
        foreach($html -> find('dl dt a') as $list){
            $url = $list->href;
            if ($this->shouldBeRegisteredUrl($url)===false){
                continue;
            }
            $html_child = $this->curlUtil->getContentsCurl($url);
            $dateStrWithExtra = current($html_child -> find('dl dt'))->outertext;
            $link = new Link2ch();
            $link->url = $url;
            $link->postedDate = $this->curlUtil->extractDate($dateStrWithExtra);
            $link->text = $this->curlUtil->getInnerText($list->outertext);
            if ($this->shouldBeRegisteredText($link->text)){
                $link->save();
            }
        }
        $html -> clear();
        unset($html);
    }
    
    /**
     * Check the link should be registered
     */
    public function shouldBeRegisteredUrl($url)
    {
        if(Link2ch::where('url','=',$url)->count()>0){
            /* Duplicated data. Not saved. */
            return false;
        }
        $html= $this->curlUtil->getContentsCurl($url);
        if(empty($html)){
            /* Cannot get the posted date. */
            return false;
        }
        return true;
    }
    
    /**
     * Check the link text is suit to register
     */
    public function shouldBeRegisteredText($str)
    {
        if (mb_substr($str, mb_strlen($str)-1, 1)==="板"){
            return false;
        }
        $blackList = ["PHP文庫","PHP新書","PHP研究所"];
        foreach($blackList as $blackWord){
            if (mb_strpos($str, $blackWord)!==false){
                return false;
            }
        }
        return true;
    }
    
    
    /**
     * Check the dateString suit y/m/d or not
     */
    public function checkDateString($dateStr){
        if(empty($dateStr)) {return false; }
        if(strlen($dateStr)!==10) { return false; }
        if(substr($dateStr, 4, 1)!=="/") { return false; }
        if(substr($dateStr, 7, 1)!=="/") { return false; }
        if(!is_numeric(substr($dateStr, 0, 4))) { return false; }
        if(!is_numeric(substr($dateStr, 5, 2))) { return false; }
        if(!is_numeric(substr($dateStr, 8, 2))) { return false; }
        return true;
    }
    
    /**
     * Return the date of the week
     */
    public function calcDayOfWeek($dateStr){
        if(Empty($dateStr)){
            return "";
        }
        $weekjp = array('日','月','火','水','木','金','土');
        list($year, $month, $day) = explode("/", $dateStr);
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        $weekno = date('w', $timestamp);
        return $weekjp[$weekno];
    }
    

    
}
