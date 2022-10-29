<?php
	session_start();
	require_once("DBOps.php");
	$Gtitle="";
	$actHome="";
	$actSent="";
	$actTeacher="";
	$actShowEx="";
	$actSendEx="";
	if(!isset($_SESSION["Name"]))
	{
		header("Location: index.php");
		exit;
	}
	$FullName=$_SESSION["Name"];
	$studentID=$_SESSION["studentID"];
	$v = "class=\"active\"";
	if(isset($_GET['act']))
	{
		if($_GET['act'] == "sent")
		{
			$actSent = $v;
			$Gtitle="لیست تمرین های ارسال شده توسط شما";
		}
		else if($_GET['act'] == "ShowEx")
		{
			$actShowEx = $v;
			$Gtitle="لیست تمرین ها";
		}
		else if($_GET['act'] == "SendEx")
		{
			$actSendEx = $v;
			$Gtitle="ارسال جواب تمرین";
		}
	}
	else
	{
		$Gtitle="صفحه اصلی";
		$actHome = $v;
	}
?>
<html>
	<head>
	<title><?php echo $Gtitle; ?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script type="text/javascript" src="lib/jquery.js"></script>
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
    <link href="css/persian-datepicker-0.4.5.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="lib/persian-date.js"></script>
    <script type="text/javascript" src="js/persian-datepicker-0.4.5.min.js"></script>
    <style>
        .center-block {
            width: 900px;
        }
    </style>
		
		
		<style>
			#navbar{
				background-color:lightgreen;
				height : 4.3em;
				text-align:right;
			}
			#navbar li {
				display:inline-block;
				background-color : green;
				border-radius:3px;
				color:white;
				padding:10px;
				margin-left:2em;
				font-family:tahoma;
				font-size:30px;
			}
			a{
				color:lightblue;
			}
		</style>
	</head>
	
	<body style="background-color:lightblue;font-family:tahoma;">
	
		<div >
		<img src="img/Header.jpg" style="width:100%" />
	</div>
	
	<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="Student.php">سیستم ارسال حل تمرین</a>
    </div>
    <ul class="nav navbar-nav">
      <li <?php echo $actHome; ?> ><a href="Student.php">خانه</a></li>
      <li <?php echo $actSent; ?>><a href="Student.php?act=sent"> لیست تمرین های ارسال شده توسط شما</a></li>
      <li class="dropdown">
	  <a class="dropdown-toggle" data-toggle="dropdown" href="#">تمرین ها بر اساس نام استاد
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php
			$res = GetAllTeachers();
			while($r=mysqli_fetch_array($res))
			{
		  ?>
			<li><a href="Student.php?act=ShowEx&teacherID=<?php echo $r["teacherID"]; ?>"><?php echo $r["FirstName"]." ".$r["LastName"]; ?></a></li>
		  <?php
			}
		  ?>
        </ul>
		</li></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
	  <div class="navbar-brand"><?php echo $FullName; ?> خوش آمدید</div>
      <li><a href="StudentLogout.php"><span class="glyphicon glyphicon-log-out"></span> خروج از اکانت</a></li>
    </ul>
  </div>
</nav>

<?php
	function UploadEx()
	{
		$allowedExt=array("jpg","jpeg","doc","pdf","rtf","txt");
		$target_dir = "Files/";
		$uploadOk = 0;
		$target_file = $target_dir . basename($_FILES["exFile"]["name"]);
		$FileExt = pathinfo($target_file,PATHINFO_EXTENSION);
		for($i=0;$i<count($allowedExt);$i++)
		{
			if(strtolower($FileExt) == $allowedExt[$i])
				$uploadOk = 1;
		}
		$ret="";
		if($uploadOk == 1)
		{
			$fName=basename($_FILES["exFile"]["name"]);
			$fName = $fName.rand(0,1000000).rand(0,200000);
			$fName = md5($fName).".".$FileExt;
			$fullDir=$target_dir.$fName;
			move_uploaded_file($_FILES["exFile"]["tmp_name"],$fullDir);
			$ret=$fName;
		}
		
		return $ret;
	}
?>
<?php
	function ShowAllExercises($tID=0)
	{
		
		?>
		<table style="direction:ltr;" class="table">
		<thead>
			<tr>
			<th>عملیات</th>
			<th>مهلت ارسال</th>
			<th>نام استاد</th>
			<th> نام درس</th>
			<th>عنوان تمرین</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if($tID != 0)
			$res=GetExerciseByTeacherID($tID);
		else
		{
			$res = GetLastExercises();
		}
		$c=0;
		$r2=0;
		while($r=mysqli_fetch_array($res))
		{
			$deadLine=$r["DeadLine"];
		$cID=$r["courseID"];
		$cName=GetCourseById($cID,$r["teacherID"]);
		$cName = mysqli_fetch_array($cName);
		$cName = $cName["Name"];
		$tName=GetTeacherNameById($r["teacherID"]);
		
		$r2++;
		$class="";
		$c++;
		if($c==4)
		{
			$c=1;
		}
		if($c==1)
			$class="success";
		else if($c==2)
			$class="danger";
		else
			$class="info";

?>
	
      <tr  class="<?php echo $class; ?>">
	  <td>
	  <?php
		if(!HasExerciseReachedDeadline($r["exID"]))
		{
	  ?>
	  <a href="Student.php?act=SendEx&exID=<?php echo $r["exID"]; ?>&teacherID=<?php echo $tID;?>"><button class="btn btn-success">ارسال پاسخ تمرین</button></a></td>
	  <?php
		}
		else
		{
	  ?>
		<button class="btn btn-danger">زمان ارسال پاسخ به پایان رسیده است</button>
	  <?php
		}
	  ?>
	  <td><?php echo $r['DeadLine']; ?></td>
	  <td><?php echo $tName; ?></td>
	  <td><?php echo $cName; ?></td>
        <td><?php echo $r["Title"]; ?></td>
        
		
        
      </tr>
      

<?php

	
	}
	?>
	
	    </tbody>
  </table>
  
  <?php
	if($r2 == 0)
	{
		echo "<h2 align=center>تمرینی برای نمایش وجود ندارد</h2>";
	}
	}
?>


<?php 
	function ShowSendEx($exID,$teacherID)
	{
		$r=GetExerciseById($exID,$teacherID);
		$r = mysqli_fetch_array($r);
		if(count($r) > 0 && !HasExerciseReachedDeadline($exID))
		{
			$teacherName=GetTeacherNameById($r["teacherID"]);
			$cName=GetCourseById($r["courseID"],$teacherID);
			$cName=mysqli_fetch_array($cName);
			$cName=$cName["Name"];
		?>
		<form method="POST" enctype="multipart/form-data">
		
		<div class="col-md-3" style="margin-left:35%;margin-top:5em;">
		<div class="panel" style="padding:1em;">
	 
	 <div class="form-group">
		<label for="teacher">نام دانشجو</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $GLOBALS["FullName"]; ?>"  />
		</div>
		<div class="form-group">
		<label for="teacher">شماره دانشجویی</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $GLOBALS["studentID"]; ?>"  />
		</div>
		<div class="form-group">
		<label for="teacher">نام استاد</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $teacherName; ?>"  />
		</div>
	 
		<div class="form-group">
		<label for="course">نام درس</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $cName; ?>"  />
		</div>
	 
		<div class="form-group">
		<label for="title">عنوان تمرین</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $r["Title"]; ?>"  />
		</div>
	 
		<div class="form-group">
		<label for="deadline">مهلت ارسال تمرین</label>
		
               <input type="text" class="form-control" name="title" disabled value="<?php echo $r["DeadLine"]; ?>"  />
            
		</div>
		<div class="form-group">
			<label for="exFile">فایل پاسخ تمرین</label>
			<input type="file" name="exFile">
			<p class="help-block">فرمت های مجاز : JPG,DOC,PDF,RTF</p>
		</div>
		<div style="text-align:center">
		<button type="submit" name="submit" class="btn btn-lg btn-primary"> ارسال پاسخ </button>
		</div>
		</div>
		</div>
		</form>
		<?php
	}
	}
?>
<?php
function ShowSentEx($studentID)
	{
		
		?>
		<table style="direction:ltr;" class="table">
		<thead>
			<tr>
			<th>وضعیت</th>
			<th>نام استاد</th>
			<th> نام درس</th>
			<th>عنوان تمرین</th>
			<th> نام</th>
			<th> شماره دانشجویی</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$res=GetSentByStudentID($studentID);
		$c=0;
		$r2=0;
		while($r=mysqli_fetch_array($res))
		{
			$sID=$r["StudentID"];
		$cID=$r["courseID"];
		$cName=GetCourseById($cID,$r["teacherID"]);
		$cName = mysqli_fetch_array($cName);
		$cName = $cName["Name"];
		$tName=GetTeacherNameById($r["teacherID"]);
		$eName=GetExerciseById($r["exID"],$r["teacherID"]);
		$eName = mysqli_fetch_array($eName);
		$eName = $eName["Title"];
		$status=$r["status"];
		$cclass="warning";
		$cText="در انتظار تایید";
		if($status == 1)
		{
			$cclass="success";
			$cText="تایید شده";
		}
		if($status == 2)
		{
			$cclass = "danger";
			$cText = "رد شده";
		}
		$r2++;
		$class="";
		$c++;
		if($c==4)
		{
			$c=1;
		}
		if($c==1)
			$class="success";
		else if($c==2)
			$class="danger";
		else
			$class="info";

?>
	
      <tr  class="<?php echo $class; ?>">
	  <td><button class="btn btn-<?php echo $cclass;?>"><?php echo $cText;?></button></td> 
	  <td><?php echo $tName; ?></td>
	  <td><?php echo $cName; ?></td>
	  <td><?php echo $eName; ?></td>
        <td><?php echo $r["StudentName"]; ?></td>
        <td><?php echo $r["StudentID"]; ?></td>
		
        
      </tr>
      

<?php

	
	}
	?>
	
	    </tbody>
  </table>
  
  <?php
	if($r2 == 0)
	{
		echo "<h2 align=center>تمرینی برای نمایش وجود ندارد</h2>";
	}
	}
?>
<?php
	function PrintLastSent()
	{
	?>
	
	<h2 style="direction:rtl;padding:10px;" class = "primary">
		 آخرین تمرین های ارسال شده توسط اساتید
	</h2>
	<hr />
<?php	
	}
?>
<?php
	function PrintExercise($name,$studentID,$filename,$exTitle)
	{
	?>
	
	<div class="col-md-12" style="font-size:18px;">
		<div class="panel panel-primary" style="direction:rtl;padding:10px;background-color:lightyellow;border-radius:10px;">
			<label class="">نام دانشجو : </label>
			<br /><br />
			<span class="label label-large label-primary"><?php echo $name ?> </span>
			<br /><br />
			<label class="">شماره دانشجویی : </label>
			<br /><br />
			<span class="label label-primary"><?php echo $studentID ?> </span>
			<br /><br />
			<label class="">عنوان تمرین : </label>
			<br /><br />
			<span class="label label-primary"><?php echo $exTitle ?> </span>
			<br /><br />
			<label class="">آدرس فایل : </label>
			<br /><br />
			<span class="label label-primary"><?php echo $filename ?> </span>
		</div>
	</div>
	
<?php	
	}
?>

<?php
	if($actShowEx != "")
	{
		if(isset($_GET['teacherID']))
		{
			$tID=$_GET['teacherID'];
			ShowAllExercises($tID);
		}
	}
	else if($actHome != "")
	{
		PrintLastSent();
		ShowAllExercises();
	}
	else if($actSent != "")
	{
		ShowSentEx($GLOBALS['studentID']);
	}
	else if ($actSendEx != "")
	{
		if(!isset($_GET['exID']) || !isset($_GET['teacherID']))
		{
			echo "<h2 align=center>تمرینی برای ارسال پاسخ وجود ندارد</h2>";
		}
		else
		{
			$exID=$_GET['exID'];
			$teacherID=$_GET['teacherID'];
			if(!isset($_POST['submit']))
			{
				
				ShowSendEx($exID,$teacherID);
			}
			else
			{
				$f = UploadEx();
				if($f != "")
				{
					//SubmitExercise($teacherID,$filename,$studentName,$studentID,$exID,$courseID)
					$c = GetExerciseById($exID,$teacherID);
					$c=mysqli_fetch_array($c);
					
					SubmitExercise($teacherID,$f,$GLOBALS['FullName'],$GLOBALS['studentID'],$exID,$c["courseID"]);
					echo "<h2 align=center class='info'>تمرین شما با موفقیت ثبت شد</h2><br>";
					echo "<a style='text-align:center;display:block;' href='Student.php?act=sent'><button  class='btn btn-primary'>لیست تمرین های ارسال شده توسط شما</button></a>";
				}
				else
				{
					echo "<h2 style='color:darkred;' align=center> فایل شما برای آپلود مجاز نیست</h2>";
					ShowSendEx($exID,$teacherID);
				}
			}
		}
	}
?>

