<?php
require_once("DBConnect.php");
require_once("jDate.php");
require_once("StringProcessor.php");
function CreateAdmin($user,$pass,$firstName,$lastName)
{
	$con = Connect();
	$user = mysqli_real_escape_string($con,$user);
	$firstName = mysqli_real_escape_string($con,$firstName);
	$lastName = mysqli_real_escape_string($con,$lastName);
	
	$pass=md5($pass);
	mysqli_query($con,"INSERT INTO tblteachers(`Username`,`Password`,`FirstName`,`LastName`) VALUES('$user','$pass','$firstName','$lastName');");
	Disconnect($con);
}

function HasExerciseReachedDeadline($exID)
{
	$ret=0;
	$con = Connect();
	$date = new jDateTime(true, true, 'Asia/Tehran');
	$currentDate= $date->date("Y-m-d", false, false);
	$exID = mysqli_real_escape_string($con,$exID);
	$res = mysqli_query($con,"SELECT DeadLine FROM tblEx WHERE `exID` = '$exID';");
	$res = mysqli_fetch_array($res);
	$dead = str_replace("/","-",$res["DeadLine"]);
	$deadD = GetDay($dead);
	$deadM = GetMonth($dead);
	$deadY = GetYear($dead);
	$deadF = $date->mktime(23,59,59,$deadM,$deadD,$deadY);
	$currentD = GetDay($currentDate);
	$currentM = GetMonth($currentDate);
	$currentY = GetYear($currentDate);
	date_default_timezone_set('Asia/Tehran');
	$currentF = $date->mktime(date("H"),date("i"),date("s"),$currentM,$currentD,$currentY);
	
	$cc =  $deadF - $currentF;
	Disconnect($con);
	if($cc <= 0)
	{
		$ret = 1;
	}
	return $ret;
}

function GetUserInfo($userID)
{
	$con=Connect();
	$userID = mysqli_real_escape_string($con,$userID);
	
	$r = mysqli_query($con,"SELECT * FROM tblteachers WHERE `teacherID` = '$userID';");
	Disconnect($con);
	return mysqli_fetch_array($r);
}

function AddExercise($teacherID,$title,$deadLine,$courseID)
{
	$con=Connect();
	$ret=0;
	$title=mysqli_real_escape_string($con,$title);
	$deadLine = mysqli_real_escape_string($con,$deadLine);
	if(mysqli_query($con,"INSERT INTO tblEx(`teacherID`,`Title`,`DeadLine`,`courseID`) VALUES('$teacherID','$title','$deadLine','$courseID');"))
	{
		
		$res = mysqli_query($con,"SELECT `exID` FROM tblEx WHERE `teacherID` = '$teacherID' ORDER BY `exID` DESC ");
		$res = mysqli_fetch_array($res);
		$ret = $res[0];
	}
	
	Disconnect($con);
	return $ret;
}

function SetExerciseStatus($fID,$teacherID,$status)
{
	$con = Connect();
	$fID = mysqli_real_escape_string($con,$fID);
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$status = mysqli_real_escape_string($con,$status);
	mysqli_query($con,"UPDATE `tblfiles` set `status`='$status' WHERE `fileID` = '$fID' AND `teacherID` = '$teacherID';");
	Disconnect($con);
}
function AddCourse($teacherID,$title)
{
	$con=Connect();
	$ret=0;
	$title=mysqli_real_escape_string($con,$title);
	if(mysqli_query($con,"INSERT INTO tblCourse(`teacherID`,`Name`) VALUES('$teacherID','$title');"))
	{
		
		$ret = 1;
	}
	
	Disconnect($con);
	return $ret;
}

function SubmitExercise($teacherID,$filename,$studentName,$studentID,$exID,$courseID)
{
	$con = Connect();
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$filename =  mysqli_real_escape_string($con,$filename);
	$studentName =  mysqli_real_escape_string($con,$studentName);
	$studentID =  mysqli_real_escape_string($con,$studentID);
	$exID = mysqli_real_escape_string($con,$exID);
	$courseID = mysqli_real_escape_string($con,$courseID);
	mysqli_query($con,"INSERT INTO tblFiles(`teacherID`,`fileName`,`StudentName`,`StudentID`,`exID`,`courseID`) VALUES ('$teacherID','$filename','$studentName','$studentID','$exID','$courseID');");
	Disconnect($con);
}

function GetCourseByID($courseID,$teacherID)
{
	$con = Connect();
	$courseID = mysqli_real_escape_string($con,$courseID);
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$ret="";
	if($res = mysqli_query($con,"SELECT * FROM tblCourse WHERE `teacherID` = '$teacherID' AND `courseID` = '$courseID';"))
	{
			$ret = $res;
	}
	Disconnect($con);
	return $ret;
}

function GetCourseIDByExerciseID($exID)
{
	$con = Connect();
	$exID = mysqli_real_escape_string($con,$exID);
	$res = mysqli_query($con,"SELECT `courseID` FROM `tblEx` WHERE `exID` = '$exID';");
	$res = mysqli_fetch_array($res);
	Disconnect($con);
	return $res["courseID"];
}

function GetSentByStudentID($studentID)
{
	$con = Connect();
	$studentID = mysqli_real_escape_string($con,$studentID);
	$res = mysqli_query($con,"SELECT * FROM tblfiles WHERE `studentID` = '$studentID' ORDER BY `fileID` DESC;");
	Disconnect($con);
	return $res;
}


function GetSentByTeacherID($teacherID,$limit=0)
{
	$con = Connect();
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	if($limit == 0)
		$res = mysqli_query($con,"SELECT * FROM tblfiles WHERE `teacherID` = '$teacherID' ORDER BY `fileID` DESC;");
	else
		$res = mysqli_query($con,"SELECT * FROM tblfiles WHERE `teacherID` = '$teacherID' ORDER BY `fileID` DESC LIMIT $limit;");
	Disconnect($con);
	return $res;
}

function GetExerciseByID($exID,$teacherID)
{
	$con = Connect();
	$exID = mysqli_real_escape_string($con,$exID);
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$ret="";
	if($res = mysqli_query($con,"SELECT * FROM `tblEx` WHERE `teacherID` = '$teacherID' AND `exID` = '$exID';"))
	{
			$ret = $res;
	}
	Disconnect($con);
	return $ret;
}

function UpdateCourse($courseID,$name,$teacherID)
{
	$con = Connect();
	$courseID = mysqli_real_escape_string($con,$courseID);
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$name = mysqli_real_escape_string($con,$name);
	mysqli_query($con,"UPDATE `tblcourse` set `Name`='$name' WHERE `teacherID` = '$teacherID' AND `courseID` = '$courseID';");
	Disconnect($con);
}
function UpdateExercise($exID,$title,$deadLine,$courseID,$teacherID)
{
	$con = Connect();
	$exID = mysqli_real_escape_string($con,$exID);
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$courseID = mysqli_real_escape_string($con,$courseID);
	$title = mysqli_real_escape_string($con,$title);
	$deadLine = mysqli_real_escape_string($con,$deadLine);
	mysqli_query($con,"UPDATE `tblex` set `Title`='$title',`DeadLine`='$deadLine',`courseID` = '$courseID' WHERE `teacherID` = '$teacherID' AND `exID` = '$exID';");
	Disconnect($con);
}
function GetAllTeachers()
{
	$con=Connect();
	$res=mysqli_query($con,"SELECT * FROM `tblTeachers`;");
	Disconnect($con);
	return $res;
}

function GetTeacherNameById($tID)
{
	$con = Connect();
	$tID = mysqli_real_escape_string($con,$tID);
	$res = mysqli_query($con,"SELECT * FROM `tblTeachers` WHERE `teacherID` = '$tID';");
	$res = mysqli_fetch_array($res);
	$ret = $res["FirstName"]." ".$res["LastName"];
	Disconnect($con);
	return $ret;
}
function DeleteExercise($exID,$teacherID)
{
	$con = Connect();
	$exID = mysqli_real_escape_string($con,$exID);
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	mysqli_query($con,"DELETE FROM `tblEx` WHERE `exID` ='$exID' AND `teacherID` = '$teacherID'; ");
	mysqli_query($con,"DELETE FROM `tblFiles` WHERE `exID` ='$exID' AND `teacherID` = '$teacherID'; ");
	Disconnect($con);
}

function DeleteCourse($courseID,$teacherID)
{
	$con = Connect();
	$courseID = mysqli_real_escape_string($con,$courseID);
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	mysqli_query($con,"DELETE FROM `tblCourse` WHERE `teacherID` = '$teacherID' AND `courseID` = '$courseID';");
	mysqli_query($con,"DELETE FROM `tblEx` WHERE `teacherID` = '$teacherID' AND `courseID` = '$courseID';");
	mysqli_query($con,"DELETE FROM `tblFiles` WHERE `teacherID` = '$teacherID' AND `courseID` = '$courseID';");
	Disconnect($con);
}
function GetCoursesByTeacherID($teacherID)
{
	$con = Connect();
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$ret="";
	if($res = mysqli_query($con,"SELECT * FROM tblCourse WHERE `teacherID` = '$teacherID';"))
	{
			$ret = $res;
	}
	Disconnect($con);
	return $ret;
}

function GetExerciseByTeacherID($teacherID)
{
	$con = Connect();
	$teacherID = mysqli_real_escape_string($con,$teacherID);
	$ret="";
	if($res = mysqli_query($con,"SELECT * FROM `tblEx` WHERE `teacherID` = '$teacherID' ORDER BY `exID` DESC;"))
	{
			$ret = $res;
	}
	Disconnect($con);
	return $ret;
}

function GetLastExercises()
{
	$con = Connect();
	$res=mysqli_query($con,"SELECT * FROM `tblEx` ORDER BY `exID` DESC LIMIT 5;");
	Disconnect($con);
	return $res;
}
function Validate($user,$pass)
{
	$con= Connect();
	$user = mysqli_real_escape_string($con,$user);
	$pass = md5($pass);
	$ret = 0;
	if($result = mysqli_query($con,"SELECT * FROM tblteachers WHERE `Username` = '$user' AND `Password` = '$pass';"))
	{
		$ra=mysqli_fetch_array($result);
		$c = count($ra[0]);
		if($c == 0)
		{
			$ret = -1;
		}
		else
		{
			$ret = $ra[0];
		}
	}
	else
	{
		$ret = -2;
	}
	
	Disconnect($con);
	return $ret;
}
?>