<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 5/17/2001
 * Time: 3:24 PM
 */

namespace App\Functions;


class Progress
{
    private $_x = 0;
    private $_totalrows=0;
    private $_tbefore=0;
    private $_tbefore2=0;
    private $_xbefore=0;
    private $_thecount=1;
    private $_thetime=0;
    private $_startingtime=0;
    private $_speedbefore=0;
    private $_tbefore3=0;
    private $_tbefore4=0;
    private $_thespeed=0;

    private $_dataremaining=0;
    private $_minsremain = 0;
    private $_secondsremain=0;
    private $_percent=0;
    private $_percentrounded=0;
    private $_totalprogress=0;
    private $_displaydetails=true;

    function initprogress(){

//        echo	$this->_tbefore=microtime(true);
//        echo "</br>";
        $this->_tbefore2=microtime(true);
        $this->_startingtime=microtime(true);
        $this->_tbefore3=microtime(true);
        $this->_tbefore4=microtime(true);
        echo '<script type="text/javascript">
					$("#progressDiv").fadeIn(0);
				</script>';
    }
    function settotalvalues($totalvalue){
        $this->_totalrows=$totalvalue;
    }
    function setprogress($curprogress)
    {
        $this->_x=$curprogress;
        $this->_percent = (($this->_x/($this->_totalrows+0.01)) * 100);
        $t = microtime(true);
        if(($t-$this->_tbefore)>=1){
            $this->_thecount=($this->_x-$this->_xbefore);
            $this->_xbefore=$this->_x;
            $this->_tbefore= microtime(true);
        }
        $this->_elapsetime=($t-$this->_startingtime);
        if((microtime(true)-$this->_tbefore2)>=0.5){
            $this->_thespeed=$this->_thecount;
            $this->_tbefore2= microtime(true);
        }
        if($this->_thespeed>0){
            $this->_secondsremain=round(($this->_totalrows-$this->_x)/($this->_thespeed+0.1));
        }
        $this->_minsremain=0;
        while($this->_secondsremain>=60)
        {
            $this->_secondsremain=$this->_secondsremain-60;
            $this->_minsremain=$this->_minsremain+1;
        }

        $this->_dataremaining=($this->_totalrows-$this->_x);
        $this->_percentrounded = round($this->_percent,2);
    }
    function displaydetails($displaydetails=true){
        $this->_displaydetails=$displaydetails;
    }
    function getspeed(){
        return $this->_thespeed;
    }
    function getminsremain(){
        return $this->_minsremain;
    }
    function getsecondsremain(){
        return $this->_secondsremain;
    }
    function getpercent(){
        return $this->_percent;
    }
    function getpercentrounded(){
        return $this->_percentrounded;
    }
    function saveprogress(){
    }
    function displayprogress($saveprogress='false'){

        if($this->_thespeed<=0){
            $texts = ($this->_totalrows-$this->_x)." data remaining<br/>Speed: Calculating...<br/>Remaining time: Calculating...";
        }
        else{
            $texts = ($this->_totalrows-$this->_x)." data remaining<br/>Speed: ".$this->_thespeed." data/secs<br/>Remaining time: ".$this->_minsremain." min. ".$this->_secondsremain.' sec';
        }
        $t=microtime(true);
        if($saveprogress=='true'){
            if(($t-$this->_tbefore4)>=0.07){
                $this->_tbefore4=microtime(true);
                // try{
                // $fp = fopen("Classes/progress/progress.txt",'c');
                // fwrite($fp,round($this->_percent,2));
                // fclose($fp);
                // }
                // catch(Exception $e){

                // }
            }
        }
        if(($t-$this->_tbefore3)>=0.05){

            $this->_tbefore3=microtime(true);
            echo '<script language="javascript">

					document.getElementById("progress").innerHTML="<div style=\"width:'.$this->_percent.'%; height:100%; background-color:#51d663;\">&nbsp;</div>";
					document.getElementById("percentDiv").innerHTML="'.round($this->_percent,2).'%";';
            if($this->_displaydetails){
                echo' document.getElementById("text").innerHTML="'.$texts.'";';
            }
            echo '</script>';
            ob_flush();
            flush();
        }

        if($this->_x==$this->_totalrows){
            echo '<script language="javascript">

					document.getElementById("progress").innerHTML="<div style=\"width:'.(100.00).'%; height:100%; background-color:#51d663;\">&nbsp;</div>";
					document.getElementById("percentDiv").innerHTML="'.(100).'%";';
            if($this->_displaydetails){
                echo 'document.getElementById("text").innerHTML="'.$texts.'";';
            }
            echo '</script>';
            ob_flush();
            flush();
        }
    }
    function progressdone(){
        echo '<script language="javascript">
					document.getElementById("progress").innerHTML="<div style=\"width:'.(100.00).'%; height:100%; background-color:#51d663;\">&nbsp;</div>";
					document.getElementById("percentDiv").innerHTML="'.(100).'%";';
        if($this->_displaydetails){
            echo 'document.getElementById("text").innerHTML="finished";';
        }
        echo '</script>';
        ob_flush();
        flush();
    }
}