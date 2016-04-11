<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;

/**
 * Description of curlUtility
 *
 * @author yoshida
 */
class CurlUtility {
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
        $html= str_get_html($str);
        return $html;
    }
    
    /**
     * Convert text into html dom object
     */
    public function getHtmlDom($str){
        return str_get_html($str);
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
     * Extract the Time from String
     * 
     * e.g. 
     * extractTime("Today is 2016/4/2 10:15:30!!") === "10:15:30"
     */
    public function extractTime($timeStrOrg){
        $timeStrArray = array();
        preg_match('/\d{2}:\d{2}:\d{2}/', $timeStrOrg, $timeStrArray);
        $timeStr = empty($timeStrArray) ? "00:00:00":$timeStrArray[0];
        return $timeStr;
    }

}
