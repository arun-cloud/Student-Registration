<?php
header("Content-Type: text/html; charset=utf-8");

if (is_ajax()) {
if (isset($_POST["action"]) && !empty($_POST["action"])) { 
$return = array();
$content="";



	    
    if ($_POST["scholarship"] == 17 || $_POST["scholarship"] == 16){
		if ($_POST["pendingfees"] == 0){
			$_POST['content'] = "Greetings Your registration was examined and is complete Please report to registration dept to collect the ID";
		}elseif($_POST["pendingfees"] > 0){
			$_POST['content'] = "Greetings Your registration was examined Please report to finance dept to pay the pending fees:".$_POST["pendingfees"]." SR within 48Hrs";
		}
	}elseif($_POST["scholarship"] == 18){
		$_POST['grandtotal'] = $_POST["semesterfees"]+ $_POST['vatamt'];
		if ($_POST["pendingfees"] == 0){
			if ($_POST["nationality"] == "Saudi"){
				$_POST['content'] = "Greetings, Your registration was examined.Please report to finance dept. to pay the semesterfees fees:".$_POST["grandtotal"]." SR within 48Hrs.";
			}else{
				$_POST['content'] = "Greetings, Your registration was examined.Please report to finance dept. to pay the semesterfees fees:".$_POST["grandtotal"]." SR(Incl VAT 5%) within 48Hrs.";
			}
			
		}elseif($_POST["pendingfees"] > 0){
			if ($_POST["nationality"] == "Saudi"){
			$_POST['content'] = "Greetings, Your registration was examined. Please report to finance dept. to pay the semester fees:".$_POST["grandtotal"]." and pending fees:".$_POST["pendingfees"]." SR within 48Hrs.";
				}else{
					$_POST['content'] = "Greetings, Your registration was examined. Please report to finance dept. to pay the semester fees:".$_POST["grandtotal"]."SR(Incl VAT 5%) and pending fees:".$_POST["pendingfees"]." SR within 48Hrs.";
				}
		}
	}

//$_POST['content'] =  "Greetings Your registration was examined Please report to finance dept to pay the pending fees:".$_POST["pendingfees"]." SR within 48Hrs";
		 $json = sendSMS('osama03','a325646',$_POST['content'],$_POST['mobileno'],'IBN SINA');


	
    if ($json == 100){
		include(getcwd().'/db_connect.php'); 
		$msgcount = $_POST["msgcount"]+1;
		$query = "update registration set msgcount= ".$msgcount." where uid= ".$_POST["reguid"]." and statusflag='A'";	
		$result=mysql_query($query); 
	}
	 echo json_encode($json);
}

}

function your_filter($value) {
    $newVal = trim($value);
    $newVal = htmlspecialchars($newVal);
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
}

function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function sendSMS($oursmsusername,$oursmspassword,$messageContent,$mobileNumber,$senderName)
{
$user = urlencode($oursmsusername);
$password = urlencode($oursmspassword);
$sendername = urlencode($senderName);
$text = urlencode($messageContent);
$to = urlencode($mobileNumber);
// auth call

//php
//$url = "http://www.oursms.net/api/sendsms.php?username=osama03&password=a325646&numbers=0576814364&message=test&sender=Ibn Sina&unicode=E&return=full";
$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=full";

//لارجاع القيمه json
//$url = "http://www.oursms.net/api/sendsms.php?username=".$user."&password=".$password."&numbers=".$to."&message=".$text."&sender=".$sendername."&unicode=E&return=json";
// لارجاع القيمه xml
//$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=xml";
// لارجاع القيمه string 
//$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E";
// Call API and get return message
//$handle = fopen($url,"r");
$ret = file_get_contents($url);
return nl2br($ret);

}