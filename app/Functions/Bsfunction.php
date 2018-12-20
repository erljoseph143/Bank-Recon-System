<?php
/**
 * Created by PhpStorm.
 * User: Erljoseph143
 * Date: 5/17/2001
 * Time: 10:50 AM
 */

namespace App\Functions;


class Bsfunction
{
    protected $id;

    public function __construct()
    {
        $this->id="Welcome";
    }
/**
 * Function Search for Year
 *
 */
   public function searchforyear($strtosearch)
   {
        $curdate=getdate();
        for($a=1900;$a<=$curdate['year']+10;$a++){
            if(findstr($strtosearch,$a)=='true'){
                return $a;
            }
        }
        return 'false';
    }

/*
     * Function return true if amount contains non-numeric
     *
     *
 */
    public 	function findstr($str,$strtofind){
        for($a=0;$a<strlen($str);$a++){
            if(substr($str,$a,strlen($strtofind))==$strtofind){
                return'true';
                break;
            }
        }
        return'false';
    }
/*
     * Function return only numbers
     *
     *
 */
    public 	function manipulatenumber($numtomanipulate)
    {
		if(strpos(trim($numtomanipulate),'.')== false)
		{
			
		}
	
		//preg_match_all('/\\d/', $numtomanipulate,$data);
//	    $amt = '';
//	    $count = count($data[0]);
	    preg_match_all('(-?\d+(?:\d+)?+)', $numtomanipulate,$data);
	    $number = "";
	    foreach($data[0] as $key => $num)
	    {
		    $number .=$num;
	    }
	    $amt = '';
	    $count = strlen($number);
	    for($key=0;$key<$count;$key++)
	    {
		    $num = $number[$key];
		    if(strpos(trim($numtomanipulate),'.')===strlen(trim($numtomanipulate))-2)
		    {
			    if($key == ($count-1))
			    {
				    $amt .='.'.$num;
			    }
			    else
			    {
				    $amt.=$num;
			    }
		    }
		    elseif(strpos(trim($numtomanipulate),'.')== false)
		    {
			    if($key == $count-1)
			    {
				    $amt .=$num.'.00';
			    }
			    else
			    {
				    $amt.=$num;
			    }
		    }
		    else
		    {
			    if($key == ($count-2))
			    {
				    $amt .='.'.$num;
			    }
			    else
			    {
				    $amt.=$num;
			    }
		    }
		
	    }
	    return $amt;
		  	    //return str_replace(",","",$numtomanipulate);
         
//        if($this->findstr($numtomanipulate," ")=='false' and $this->findstr($numtomanipulate,".")=='false' and $this->findstr($numtomanipulate,",")=='false'){
//            if(strlen($numtomanipulate)==0){
//                return 'false';
//            }
//            else{
//                return $numtomanipulate;
//            }
//        }
//        $numtomanipulate = str_replace(",","",$numtomanipulate);
//        $numtomanipulate = str_replace(" ",'',$numtomanipulate);
//        $tempcolumnE=$numtomanipulate;
//        for($m=0;$m<strlen($tempcolumnE);$m++)
//        {
//            if(!(is_numeric(substr($tempcolumnE,$m,1)))and!(substr($tempcolumnE,$m,1)==".")and!(substr($tempcolumnE,$m,1)=="-"))
//            {
//                $numtomanipulate=str_replace(substr($tempcolumnE,$m,1),"",$numtomanipulate);
//            }
//        }
//        $temp=explode(".",$numtomanipulate);
//        if(count($temp)>1){
//            if(strlen($temp[count($temp)-1])==1){
//                $decimalplace=$temp[count($temp)-1].'0';
//            }
//            else{
//                $decimalplace=$temp[count($temp)-1];
//            }
//            $temp[count($temp)-1]="";
//            $numtomanipulate=implode($temp);
//            $numtomanipulate=str_replace(".","",$numtomanipulate);
//            $numtomanipulate=str_replace(",","",$numtomanipulate).$decimalplace;
//        }
//        $numtomanipulate = str_replace(".",'',$numtomanipulate);
//        $numtomanipulate = substr($numtomanipulate,0,strlen($numtomanipulate)-2).'.'.substr($numtomanipulate,strlen($numtomanipulate)-2,2);
//
//        if(trim($numtomanipulate)=="."){
//            return 'false';
//        }
//        else{
//            return $numtomanipulate;
//        }
    }
/*
     * Function return only date
     *
     *
 */
	public function dateChecking($date,$format,$year,$separator)
	{
		if(strpos($format,'y')===false and strpos($format,'Y')===false)
		{
			if(strlen($separator)>0)
			{
				$format = $format.$separator."Y";
				$date   = $date.$separator.$year;
			}
			else
			{
				$format = $format."Y";
				$date   = $date.$year;
			}
		}
		$d = \DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}



    public 	function manipulatedate($datetomanipulate)
    {
        $datetomanipulate = strtolower($datetomanipulate);
        $stringdatenames=",jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec";
        $stringdatenames=explode(",",$stringdatenames);
        $month="";

        for($a=1;$a<count($stringdatenames);$a++){
            if($this->findstr($datetomanipulate, $stringdatenames[$a])=='true'){
                $month = $a;
                $datetomanipulate = str_replace($stringdatenames[$a],"",$datetomanipulate);
                break;
            }
        }
        $datetomanipulate = str_replace("/","",$datetomanipulate);
        $datetomanipulate = str_replace("-","",$datetomanipulate);
        $datetomanipulate = str_replace(" ","",$datetomanipulate);
        //msgbox($datetomanipulate."    ".$month);
        //$datevalfromstring=(strpos($stringdatenames,"dec")/3)+1;
    }
/*
     * Function remove characters
     *
     *
 */
    public function removecodechars($strvalue){
        $strvalue = str_replace("'","",$strvalue);
        $strvalue = str_replace('"','',$strvalue);
        return $strvalue;
    }
/*
     * Function adding errors to session
     *
     *
 */
    function addtoerror($errorval){
       // $_SESSION['mgaerrors'] = $_SESSION['mgaerrors'].$errorval;
        session(['mgaerrors' => session()->get('mgaerrors').$errorval]);

        //return session()->get('mgaerrors');
        //msgbox($mgaerrors);
    }
/*
     * Function display error
     *
     *
 */
    function displayerror($description,$row,$column,$value,$filename,$key2,$value2,$excel='Excel2007')
    {
       // session(['errorid'=> session()->get('errorid'+1)]);
        $this->addtoerror( '<tr>');
        $localIP = getenv('REMOTE_ADDR');
        if($localIP == "::1")
        {
            //$localIP = "172.16.16.199";
            $localIP = "172.16.40.12";
        }
        else
        {
            $localIP = $localIP;
        }
        if(!file_exists("functions/tempuploads/".$localIP))
        {
            mkdir("functions/tempuploads/".$localIP);
        }

        copy($value2,"functions/tempuploads/".$localIP."/".$filename);
        //addtoerror( '<td><font size=\"2\">'.$description.'</font></td>'.'<td><font size=\"2\">'.$row.'</font></td>'.'<td><font size=\"2\">'.$column.'</font></td>'.'<td><font size=\"2\">'.$value.'</font></td>'.'<td><font size=\"2\"><a href=\"#\" id=\"'.$key2.'\" onclick=\"$.get(\'functions/read_excel.php?filename='.$localIP."/".$filename.'&row='.$row.'&col='.$column.'\',function(result){showMessageSuccess(result,\''.$filename.'   -   '.$description.'\',\'\');})\">'.$filename.'</a></font></td><td><input onclick=\"ignoreerrorchecked('.$_SESSION['errorid'].',\''.$key2.'\',\''.$row.'\',\''.$column.'\')\" style=\"margin-left:45%\" type=\"checkbox\" id=\"check'.$_SESSION['errorid'].'\"/></td>');
        //addtoerror( '<td><font size=\"2\">'.$description.'</font></td>'.'<td><font size=\"2\">'.$row.'</font></td>'.'<td><font size=\"2\">'.$column.'</font></td>'.'<td><font size=\"2\">'.$value.'</font></td>'.'<td><font size=\"2\"><a href=\"#\" id=\"'.$key2.'\" onclick=\"$.get(\'functions/read_excel.php?filename='.$localIP."/".$filename.'&row='.$row.'&col='.$column.'\',function(result){showMessageSuccess(result,\''.$filename.'   -   '.$description.'\',\'\');})\">'.$filename.'</a></font></td><td><input style=\"margin-left:45%\" class=\"ignoreerrorcheckboxes\" type=\"checkbox\" disabled /></td>');
//        $this->addtoerror( '<td><font size=\"2\">'.$description.'</font></td>'.'<td><font size=\"2\">'.$row.'</font></td>'.'<td><font size=\"2\">'.$column.'</font></td>'.'<td><font size=\"2\">'.$value.'</font></td>'.'<td><font size=\"2\"><a href=\"#\" id=\"'.$key2.'\" onclick=\"$.get(\'functions/read_excel.php?filename='.$localIP."/".$filename.'&row='.$row.'&col='.$column.'&excel='.$excel.'\',function(result){showMessageSuccess(result,\''.$filename.'   -   '.$description.'\',\'\');})\">'.$filename.'</a></font></td>');
//        $this->addtoerror( '</tr>');
        $arrFile = "\'".$filename."|".'Error'."|".$row."\'";
        $this->addtoerror( '<td><font size=\"2\">'.$description.'</font></td>'.'<td><font size=\"2\">'.$row.'</font></td>'.'<td><font size=\"2\">'.$column.'</font></td>'.'<td><font size=\"2\">'.$value.'</font></td>'.'<td><font size=\"2\"><a href=\"#\" id=\"'.$key2.'\" onclick=\"loadError('.$arrFile.')\">'.$filename.'</a></font></td>');
        $this->addtoerror( '</tr>');
    }
/*
     *Function display error of bank format
     *
     *
 */
    public function displayerror_bankformat($description,$row,$column,$value,$filename,$key2,$value2,$excel='Excel2007')
    {

        session(['errorid'=> session()->get('errorid')+1]);
        $this->addtoerror( '<tr>');
        $localIP = getenv('REMOTE_ADDR');
        if($localIP == "::1")
        {
          //  $localIP = "172.16.16.199";
            $localIP = "172.16.40.12";
        }
        else
        {
            $localIP = $localIP;
        }
        if(!file_exists("functions/tempuploads/".$localIP))
        {
            mkdir("functions/tempuploads/".$localIP);
        }
        //echo "functions/tempuploads/".$localIP."/".$filename;
        copy($value2,"functions/tempuploads/".$localIP."/".$filename);

       // $this->addtoerror( '<td><font size=\"2\">'.$description.'</font></td>'.'<td><font size=\"2\">'.$row.'</font></td>'.'<td><font size=\"2\">'.$column.'</font></td>'.'<td><font size=\"2\">'.$value.'</font></td>'.'<td><font size=\"2\"><a href=\"#\" id=\"'.$key2.'\" onclick=\"$.get(\'functions/read_error_bank.php?filename='.$localIP."/".$filename.'&row='.$row.'&col=Error&excel='.$excel.'\',function(result){showMessageSuccess(result,\''.$filename.'   -   '.$description.'\',\'\');})\">'.$filename.'</a></font></td>');

        $arrFile = "\'".$filename."|".'Error'."|".$row."\'";
        $this->addtoerror( '<td><font size=\"2\">'.$description.'</font></td>'.'<td><font size=\"2\">'.$row.'</font></td>'.'<td><font size=\"2\">'.$column.'</font></td>'.'<td><font size=\"2\">'.$value.'</font></td>'.'<td><font size=\"2\"><a href=\"#\" id=\"'.$key2.'\" onclick=\"loadError('.$arrFile.')\">'.$filename.'</a></font></td>');
        $this->addtoerror( '</tr>');
    }
/*
     *Function add error 2
     *
     *
 */
    public 	function addtoerror2($errorval){
        session_start();

        $this->msgbox(session()->get('mgaerrors'));
        session(['mgaerrors' => session()->get('mgaerrors').$errorval]);

    }
/*
    *Function display error 2
    *
    *
*/
    public function displayerror2($description,$row,$column,$value,$filename){
        $this->addtoerror( '<tr>');
        $this->addtoerror( '<td>'.$description.'</td>'.'<td>'.$row.'</td>'.'<td>'.$column.'</td>'.'<td>'.$value.'</td>'.'<td>'.$filename.'</td>');
        $this->addtoerror( '</tr>');
    }
/*
    *Function display error 2
    *
    *
*/
    function m($var){
        $var = str_replace('\\n\\n','<br/>',$var);
        echo $var.'<br/>';
    }
/*
    *Function display error 2
    *
    *
*/
    function removebr($var)
    {
        $var = str_replace('\\n' ,'qwer',$var);
        echo $var;
    }
/*
    *Function display error 2
    *
    *
*/
    function ok()
    {
        m('ok');
    }
/*
    *Function display error 2
    *
    *
*/
    function msgbox($var)
    {
        echo '<script text="language"> alert("'.$var.'") </script>';
    }
/*
    *Function display error 2
    *
    *
*/
    function setpage($var)
    {
        echo '<script text="language">	
						window.location = "'.$var.'";
				</script>';
    }
}