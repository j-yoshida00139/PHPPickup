<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Link2ch;
use App\Page2ch;


class crawl2chPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl2chPage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the contents of 2ch pages';

    private $curlUtil;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->curlUtil = new CurlUtility();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach(Link2ch::all() as $link){
            $html= $this->curlUtil->getContentsCurl($link->url);
            $listDt = $html->find('dl dt');
            $listDd = $html->find('dl dd');
            if (count($listDt) !== count($listDd)){
                continue;
            }
            for ($i=0; $i<count($listDt); $i++){
                $dtStr = $listDt[$i]->outertext;
                echo $dtStr.PHP_EOL;
                $post = $this->curlUtil->getHtmlDom($dtStr);
                $page = new page2ch();
                $page->link2ches_id = $link->id;
                $pageBtag = $post->find('b');
                if (count($pageBtag)<1){
                    continue;
                }
                $page->posterName = $this->curlUtil->getInnerText($pageBtag[0]);
                $page->postedNo = mb_substr($dtStr, 4, mb_strpos($dtStr, "：")-5);
                $postedDate = $this->curlUtil->extractDate($dtStr);
                $postedTime = $this->curlUtil->extractTime($dtStr);
                $page->postedTime = new \DateTime($postedDate->format('Y/m/d')." ".$postedTime);
                $page->posterId = mb_substr($dtStr, mb_strpos($dtStr, "ID:")+3);
                echo $page->posterId.PHP_EOL;
                echo $listDd[$i].PHP_EOL;
                $page->text = mb_substr($listDd[$i], 5, mb_strlen($listDd[$i])-5);
                
                $latestPost = Page2ch::where('link2ches_id', $page->link2ches_id)->orderBy('postedTime', 'desc')->first();
                echo "count(latestPost)=".count($latestPost).PHP_EOL;
                if( count($latestPost)===0){
                    echo 'New post was saved. Because this is the first post in the page.'.PHP_EOL;
                    $page->save();
                }elseif(strtotime ($page->postedTime->format('Y-m-d H:i:s')) > strtotime($latestPost->postedTime) ){
                    echo $latestPost->id.PHP_EOL;
                    echo $page->postedTime->format('Y/m/d H:i:s').PHP_EOL;
                    echo $latestPost->postedTime.PHP_EOL;
                    echo 'New post was saved.'.PHP_EOL;
                    echo $page->link2ches_id.PHP_EOL;
                    $page->save();
                }else{
                    echo "The post was skipped.".PHP_EOL;
                }
            }
        }
    }
    
    /**
     * Get the text into some HTML tags.
     */
    public function extractInnerText($str){
        $tag = '';
        $isHit = preg_match('<*>', $str, $tag);
        echo "isHit===".$isHit.PHP_EOL;
        if ($isHit===1){
            echo "hit!";
            $closeTag = substr($tag[0], 0, 1)."/".substr($tag[0], 1, strlen($tag[0]));
            $strTmp = preg_replace($tag[0], "", $str);
            $strFinal = preg_replace($closeTag, "", $strTmp);
            return $this->curlUtil->getInnerText($strFinal);
        }        
        echo $str;
        return $str;
    }
    

}

