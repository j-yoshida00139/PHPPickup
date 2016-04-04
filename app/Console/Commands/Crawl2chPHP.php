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
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mainUrl = 'http://find.2ch.sc/?STR=php&TYPE=TITLE&x=29&y=9&BBS=ALL&ENCODING=SJIS&COUNT=50';
        $str = $this->getContentsCurl($mainUrl); 
        $html= str_get_html($str);
        echo 'a'.PHP_EOL;
        foreach($html -> find('dl dt a') as $list){
            $url = $list->href;
            if ($this->shouldBeRegisteredUrl($url)===false){
                continue;
            }
            $str_child = $this->getContentsCurl($url);
            $html_child= str_get_html($str_child);
            echo 'aaa'.PHP_EOL;
            $dateStrWithExtra = current($html_child -> find('dl dt'))->outertext;
            $link = new Link2ch();
            $link->url = $url;
            $link->postedDate = $this->extractDate($dateStrWithExtra);
            $link->text = $this->getInnerText($list->outertext);
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
        $str = $this->getContentsCurl($url);
        $html= str_get_html($str);
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
     * Extract the Date from String
     * 
     * e.g. 
     * extractDate("Today is 2016/4/2 !!") === "2016/4/2"
     */
    public function extractDate($dateStrOrg){
        $dateStrArray = array();
        preg_match('/[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}/', $dateStrOrg, $dateStrArray);
        $dateStr = empty($dateStrArray) ? "":$dateStrArray[0];
        if(! $this->checkDateString($dateStr)){
            echo 'Illegal date format.'.PHP_EOL;
            $date = new \DateTime("1000-01-01");
        }else{
            try{
                $date = new \DateTime($dateStr);
            } catch (Exception $e) {
                echo $e->getMessage();
                $date = new \DateTime("1000-01-01");
            }
        }
        return $date;
    }
    
    /**
     * Check the dateString suit yyyy/mm/dd or not
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
    
    /**
     * Return inner text of an html tag
     */
    public function getInnerText($aStr){
        $aStrArray = array();
        preg_match('/>.*</',$aStr, $aStrArray);
        if(empty($aStrArray)){
            return "";
        }
        $innerText = mb_substr($aStrArray[0], 1, mb_strlen($aStrArray[0])-2);
        return $innerText;
    }
    
    /**
     * Get contents by curl
     */
    public function getContentsCurl($url){
        include_once(getcwd().'/resources/views/simple_html_dom.php');
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
        $str = curl_exec($curl); 
        curl_close($curl); 
        return $str;
    }
}
