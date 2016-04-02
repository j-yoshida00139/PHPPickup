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
        include_once(getcwd().'/resources/views/simple_html_dom.php');
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, 'http://find.2ch.sc/?STR=php&TYPE=TITLE&x=29&y=9&BBS=ALL&ENCODING=SJIS&COUNT=50');  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
        $str = curl_exec($curl); 
        curl_close($curl); 
        $html= str_get_html($str);
        foreach($html -> find('dl dt a') as $list){
            $link = new Link2ch();
            $link->url = $list->href;
            $link->text = $this->getInnerText($list->outertext);
            $curl_child = curl_init();
            curl_setopt($curl_child, CURLOPT_URL, $link->url);
            curl_setopt($curl_child, CURLOPT_RETURNTRANSFER, 1);  
            curl_setopt($curl_child, CURLOPT_CONNECTTIMEOUT, 10);  
            $str_child = curl_exec($curl_child); 
            curl_close($curl_child); 
            $html_child= str_get_html($str_child);
            if(empty($html_child)){
                echo "Not found.".PHP_EOL;
                continue;
            }
            $dateStrWithExtra = current($html_child -> find('dl dt'))->outertext;
            $link->postedDate = $this->extractDate($dateStrWithExtra);
            if(Link2ch::where('url','=',$link->url)->count()===0){
                $link->save();
                echo 'Saved.'.PHP_EOL;
            }else{
                echo 'Duplicated. not saved.'.PHP_EOL;
            }
        }
        $html -> clear();
        unset($html);
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
        try{
            $date = new \DateTime($dateStr);
        } catch (Exception $e) {
            echo $e->getMessage();
            $date = new \DateTime("1000-01-01");
        }
        return $date;
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
        echo 'text is : '.$aStrArray[0].PHP_EOL;
        $innerText = mb_substr($aStrArray[0], 1, mb_strlen($aStrArray[0])-2);
        echo 'text is : '.$innerText.PHP_EOL;
        return $innerText;
    }
}
