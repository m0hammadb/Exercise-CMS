<?php
session_start();	
	if(!isset($_SESSION['userID'])){
		header("Location: Login.php");
	exit;
	}
require_once("DBOps.php");
$userID=$_SESSION['userID'];
$v = "class=\"active\"";
	$actNew="";
	$actSent="";
	$actHome="";
	$actTeacher="";
	$actNewCourse="";
	$actEditCourse="";
	$actDeleteCourse="";
	$actEditEx="";
	$actDeleteEx="";
if(isset($_GET['act']))
{
	
	$act = $_GET['act'];
	
	if($act == "sent")
	{
		$actSent = $v;
	}
	else if($act == "NewEx")
	{
		$actNew = $v;
	}
	else if($act == "teacher")
	{
		$actTeacher=$v;
	}
	else if($act == "NewCourse")
	{
		$actNewCourse=$v;
	}
	else if($act == "EditCourse")
	{
		$actEditCourse = $v;
	}
	else if($act == "DeleteCourse")
	{
		$actDeleteCourse = $v;
	}
	else if($act == "EditEx")
	{
		$actEditEx = $v;
	}
	else if($act == "DeleteEx")
	{
		$actDeleteEx = $v;
	}
}
else 
{
	$actHome=$v;
}
?>

<?php
	function PrintExercise($name,$studentID,$filename,$exTitle)
	{
	?>
	
	<div class="col-md-12" style="font-size:20px;">
		<div class="panel panel-primary" style="direction:rtl;padding:10px;background-color:lightyellow;border-radius:10px;">
			<label class="">نام دانشجو : </label>
			<br /><br />
			<span class="label label-large label-info"><?php echo $name ?> </span>
			<br /><br />
			<label class="">شماره دانشجویی : </label>
			<br /><br />
			<span class="label label-large label-warning"><?php echo $studentID ?> </span>
			<br /><br />
			<label class="">عنوان تمرین : </label>
			<br /><br />
			<span class="label label-large label-success"><?php echo $exTitle ?> </span>
			<br /><br />
			<label class="">فایل تمرین </label>
			<br /><br />
			<a href="Files/<? echo $filename; ?>"><button class="btn btn-danger">دریافت فایل</button></a>
			<br /><br />
		</div>
	</div>
	
<?php	
	}
?>
<?php
	function ShowAllExercises()
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
		$res=GetExerciseByTeacherID($GLOBALS['userID']);
		$c=0;
		$r2=0;
		while($r=mysqli_fetch_array($res))
		{
		$cID=$r["courseID"];
		$cName=GetCourseById($cID,$GLOBALS['userID']);
		$cName = mysqli_fetch_array($cName);
		$cName = $cName["Name"];
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
	  <td><a href="Admin.php?act=DeleteEx&exID=<?php echo $r["exID"]; ?>"><button class="btn btn-danger">حذف</button></a><a href="Admin.php?act=EditEx&exID=<?php echo $r["exID"]; ?>">&nbsp;<button class="btn btn-success">ویرایش</button></a></td>
	  <td><?php echo $r['DeadLine']; ?></td>
	  <td><?php echo $GLOBALS['fullName']; ?></td>
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
	function ShowAllCourses()
	{
		?>
		<table class="table">
		<thead>
			<tr>
				<th>عملیات</th>
				<th>نام استاد</th>
				<th>نام درس</th>				
			<tr>
		</thead>
		<tbody>
		<?php
		$res=GetCoursesByTeacherID($GLOBALS['userID']);
		$c=0;
		$r2=0;
		while($r=mysqli_fetch_array($res))
		{
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
	  <td><a href="Admin.php?act=DeleteCourse&courseID=<?php echo $r["courseID"]; ?>"><button class="btn btn-danger">حذف</button></a><a href="Admin.php?act=EditCourse&courseID=<?php echo $r["courseID"]; ?>">&nbsp;<button class="btn btn-success">ویرایش</button></a></td> 
	  <td><?php echo $GLOBALS['fullName']; ?></td>
        <td><?php echo $r["Name"]; ?></td>
       
        
      </tr>
      

<?php

	
	}
	?>
	
	    </tbody>
  </table>
  
  <?php
	if($r2 == 0)
	{
		echo "<h2 align=center>درسی برای نمایش وجود ندارد</h2>";
	}
	}
?>

<?php
	function PrintLastSent()
	{
	?>
	
	<h2 style="direction:rtl;padding:10px;" class = "primary">
		آخرین تمرین های ارسال شده
	</h2>
	<hr />
<?php	
	}
?>

<?php
	function PrintCourseCombo($courseID = 0)
	{
	$res = GetCoursesByTeacherID($GLOBALS['userID']);
	
	$c=0;
	$v="";
	if($res != "")
	{
	while($r = mysqli_fetch_array($res))
	{
	$c++;
	if($r["courseID"] == $courseID)
	{
		$v="selected";
	}
	else
	{
		$v="";
	}
		
?>
	<option <?php echo $v; ?>  value="<?php echo $r["courseID"]; ?>"><?php echo $r["Name"]; ?></option>
<?php
	
	}
	}
	else
	{
		
	}
	if($c==0)
	{
		echo "<option value='-1'>درسی وجود ندارد</option>";
	}
	}
?>

<?php
	$userInfo = GetUserInfo($userID);
	$FirstName=$userInfo["FirstName"];
	$LastName=$userInfo["LastName"];
	$fullName=$FirstName." ".$LastName;
?>
<html>
	<head>
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
	<script type="text/javascript">
    /*
     Default Functionality
     */
    $(document).ready(function () {
        /*
         Default
         */
        window.pd = $("#inlineDatepicker").persianDatepicker({
            timePicker: {
                enabled: true
            },
            altField: '#inlineDatepickerAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
//            minDate:1258675200000,
//            maxDate:1358675200000,
            checkDate: function (unix) {
                var output = true;
                var d = new persianDate(unix);
                if (d.date() == 20) {
                    output = false;
                }
                return output;
            },
            checkMonth: function (month) {
                var output = true;
                if (month == 1) {
                    output = false;
                }
                return output;

            }, checkYear: function (year) {
                var output = true;
                if (year == 1396) {
                    output = false;
                }
                return output;
            }

        }).data('datepicker');

        $("#inlineDatepicker").pDatepicker("setDate", [1391, 12, 1, 11, 14]);

        //pd.setDate([1333,12,28,11,20,30]);

        /**
         * Default
         * */
        $('#default').persianDatepicker({
            altField: '#defaultAlt'

        });


        /*
         observer
         */
        $("#observer").persianDatepicker({
            altField: '#observerAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            observer: true,
            format: 'YYYY/MM/DD'

        });

        /*
         timepicker
         */
        $("#timepicker").persianDatepicker({
            altField: '#timepickerAltField',
            altFormat: "YYYY MM DD HH:mm:ss",
            format: "HH:mm:ss a",
            onlyTimePicker: true

        });
        /*
         month
         */
        $("#monthpicker").persianDatepicker({
            format: " MMMM YYYY",
            altField: '#monthpickerAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            yearPicker: {
                enabled: false
            },
            monthPicker: {
                enabled: true
            },
            dayPicker: {
                enabled: false
            }
        });

        /*
         year
         */
        $("#yearpicker").persianDatepicker({
            format: "YYYY",
            altField: '#yearpickerAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            dayPicker: {
                enabled: false
            },
            monthPicker: {
                enabled: false
            },
            yearPicker: {
                enabled: true
            }
        });
        /*
         year and month
         */
        $("#yearAndMonthpicker").persianDatepicker({
            format: "YYYY MM",
            altFormat: "YYYY MM DD HH:mm:ss",
            altField: '#yearAndMonthpickerAlt',
            dayPicker: {
                enabled: false
            },
            monthPicker: {
                enabled: true
            },
            yearPicker: {
                enabled: true
            }
        });
        /**
         inline with minDate and maxDate
         */
        $("#inlineDatepickerWithMinMax").persianDatepicker({
            altField: '#inlineDatepickerWithMinMaxAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            minDate: 1416983467029,
            maxDate: 1419983467029
        });
        /**
         Custom Disable Date
         */
        $("#customDisabled").persianDatepicker({
            timePicker: {
                enabled: true
            },
            altField: '#customDisabledAlt',
            checkDate: function (unix) {
                var output = true;
                var d = new persianDate(unix);
                if (d.date() == 20 | d.date() == 21 | d.date() == 22) {
                    output = false;
                }
                return output;
            },
            checkMonth: function (month) {
                var output = true;
                if (month == 1) {
                    output = false;
                }
                return output;

            }, checkYear: function (year) {
                var output = true;
                if (year == 1396) {
                    output = false;
                }
                return output;
            }

        });

        /**
         persianDate
         */
        $("#persianDigit").persianDatepicker({
            altField: '#persianDigitAlt',
            altFormat: "YYYY MM DD HH:mm:ss",
            persianDigit: false
        });
    });
</script>
	<div >
		<img src="img/Header.jpg" style="width:100%" />
	</div>
	
	<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="Admin.php">سیستم ارسال حل تمرین</a>
    </div>
    <ul class="nav navbar-nav">
      <li <?php echo $actHome; ?> ><a href="Admin.php">خانه</a></li>
      <li <?php echo $actSent; ?>><a href="Admin.php?act=sent">لیست تمرین های ارسال شده</a></li>
      <li class="dropdown">
	  <a class="dropdown-toggle" data-toggle="dropdown" href="#">تمرین ها
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="Admin.php?act=NewEx">افزودن تمرین جدید</a></li>
          <li><a href="Admin.php?act=EditEx">ویرایش تمرین ها</a></li>
         
        </ul>
		</li></li>
	  <li class="dropdown">
	  <a class="dropdown-toggle" data-toggle="dropdown" href="#">دروس
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="Admin.php?act=NewCourse">افزودن درس جدید</a></li>
          <li><a href="Admin.php?act=EditCourse">ویرایش دروس</a></li>
         
        </ul>
		</li>
	  <li <?php echo $actTeacher; ?>><a href="Admin.php?act=teacher">تغییر رمز عبور</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
	  <div class="navbar-brand"><?php echo $FirstName." ".$LastName; ?> خوش آمدید</div>
      <li><a href="Logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>

<?php
	function AddNewExercise()
	{
		?>
	<form method="POST">
		
		<div class="col-md-3" style="margin-left:35%;margin-top:5em;">
	 <div class="panel" style="padding:1em;">
	 
	 <div class="form-group">
		<label for="teacher">نام استاد</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $GLOBALS['fullName']; ?>"  />
	 </div>
	 
	 <div class="form-group">
		<label for="course">نام درس</label>
		<select class="form-control" name="course">
		<?php PrintCourseCombo(); ?>
		</select>
	</div>
	 
	 <div class="form-group">
		<label for="title">عنوان تمرین</label>
		<input type="text" class="form-control" name="title" placeholder="عنوان تمرین را وارد کنید" />
	 </div>
	 
	 <div class="form-group">
		<label for="deadline">مهلت ارسال تمرین</label>
		
                <input id="observer" type="text" name="deadline" class="form-control" />
            
	 </div>
	 <div style="text-align:center">
		<button type="submit" class="btn btn-lg btn-primary"> ثبت تمرین جدید </button>
		</div>
		</div>
	 </div>
		</form>
<?php
	}
?>

<?php
	function AddNewCourse()
	{
		?>
	<form method="POST">
		
		<div class="col-md-3" style="margin-left:35%;margin-top:5em;">
	 <div class="panel" style="padding:1em;">
	 
	 <div class="form-group">
		<label for="teacher">نام استاد</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $GLOBALS['fullName']; ?>"  />
	 </div>
	 
	 <div class="form-group">
		<label for="course">نام درس</label>
		<input type="text" class="form-control" name="coursename" placeholder="نام درس را وارد کنید" />
	</div>
	 
	 
	 <div style="text-align:center">
		<button type="submit" class="btn btn-lg btn-primary">ثبت درس جدید </button>
		</div>
		</div>
	 </div>
		</form>
<?php
	}
?>

<?php
	function EditExercise($exID)
	{
		$e = GetExerciseByID($exID,$GLOBALS['userID']);
		$d=mysqli_fetch_array($e);
		$ccID = GetCourseIDByExerciseID($exID);
		?>
	<form method="POST">
		
		<div class="col-md-3" style="margin-left:35%;margin-top:5em;">
	 <div class="panel" style="padding:1em;">
	 
	 <div class="form-group">
		<label for="teacher">نام استاد</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $GLOBALS['fullName']; ?>"  />
	 </div>
	 
	 <div class="form-group">
		<label for="course">نام درس</label>
		<select class="form-control" name="course">
		<?php PrintCourseCombo($ccID); ?>
		</select>
	</div>
	 
	 <div class="form-group">
		<label for="title">عنوان تمرین</label>
		<input type="text" class="form-control" value="<?php echo $d["Title"]; ?>" name="title" placeholder="عنوان تمرین را وارد کنید" />
	 </div>
	 
	 <div class="form-group">
		<label for="deadline">مهلت ارسال تمرین</label>
		
                <input value="<?php echo $d["DeadLine"]; ?>" type="text" name="deadline" class="form-control" />
            
	 </div>
	 <div style="text-align:center">
		<button type="submit" class="btn btn-lg btn-primary"> ثبت تمرین جدید </button>
		</div>
		</div>
	 </div>
		</form>
<?php
	}
?>

<?php
	function EditCourse($courseID)
	{
		$c = GetCourseById($courseID,$GLOBALS['userID']);
		$d=mysqli_fetch_array($c);
		
		?>
		
		<?php
		if(count($d) == 0)
		{
			echo "<h2 align=center>درس مورد نظر یافت نشد</h2>";
			exit;
		}
		?>
	<form method="POST">
		
		<div class="col-md-3" style="margin-left:35%;margin-top:5em;">
	 <div class="panel" style="padding:1em;">
	 
	 <div class="form-group">
		<label for="teacher">نام استاد</label>
		<input type="text" class="form-control" name="title" disabled value="<?php echo $GLOBALS['fullName']; ?>"  />
	 </div>
	 
	 <div class="form-group">
		<label for="course">نام درس</label>
		<input type="text" class="form-control" value="<?php echo $d["Name"]; ?>" name="coursename" placeholder="نام درس را وارد کنید" />
	</div>
	 
	 <input type="hidden" name="courseid" value="<?php echo $courseID; ?>" />
	 
	 <div style="text-align:center">
		<button type="submit" class="btn btn-lg btn-primary">ویرایش درس </button>
		</div>
		</div>
	 </div>
		</form>
<?php
	}
?>

<?php
function ShowSentEx($teacherID,$limit=0)
	{
		
		?>
		<table style="direction:ltr;" class="table">
		<thead>
			<tr>
			<th>عملیات</th>
			<th>وضعیت</th>
			<th>فایل تمرین</th>
			<th>نام استاد</th>
			<th> نام درس</th>
			<th>عنوان تمرین</th>
			<th> نام و نام خانوادگی</th>
			<th> شماره دانشجویی</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$res=GetSentByTeacherID($teacherID,$limit);
		$c=0;
		$r2=0;
		while($r=mysqli_fetch_array($res))
		{
			$sID=$r["StudentID"];
		$cID=$r["courseID"];
		$fName=$r["fileName"];
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
	  <td><a href="Admin.php?act=sent&fID=<?php echo $r["fileID"];?>&c=2"><button class="btn btn-danger">رد تمرین</button></a>&nbsp;<a href="Admin.php?act=sent&fID=<?php echo $r["fileID"];?>&c=1"><button class="btn btn-success">تایید تمرین</button></a></td>
	  <td><button class="btn btn-<?php echo $cclass;?>"><?php echo $cText;?></button></td> 
	  <td><a target="_blank" href="Files/<?php echo $fName; ?>"><button class="btn btn-primary">دریافت فایل</button></a> </td>
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
	if($actNew != "")
	{
	if(!$_POST)
	{
		AddNewExercise();
	}
	else if($_POST)
	{
		if(isset($_POST['title']) && isset($_POST['deadline']))
		{
			$title=$_POST['title'];
			$deadline = $_POST['deadline'];
			if($title != "" && $_POST['course'] != -1){
			$ret = AddExercise($userID,$title,$deadline,$_POST['course']);
			if($ret != 0)
			{
					echo "<h2 align=center class='info'>تمرین با موفقیت افزوده شد</h2><br>";
					echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditEx'><button  class='btn btn-primary'>بازگشت به لیست تمرین ها</button></a>";
			}
			}
			else
			{
				AddNewExercise();
			}
		}
	}
	}
	
	if($actHome != "")
	{
		
		
	}
	
	if($actNewCourse != "")
	{
		if(!$_POST)
		{
			AddNewCourse();
		}
		else
		{
			$name=$_POST['coursename'];
			if($name != "")
			{
				$ret = AddCourse($userID,$name);
				if($ret == 1)
				{
					echo "<h2 align=center>درس با موفقیت افزوده شد</h2>";
					echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditCourse'><button  class='btn btn-primary'>بازگشت به لیست دروس</button></a>";
				}
			}
			else
			{
				AddNewCourse();
			}
		}
	}
	if($actEditCourse != "")
	{
		if(!isset($_GET['courseID']))
		{
			ShowAllCourses();
		}
		else
		{
			$cID=$_GET['courseID'];
			if(!$_POST)
			{
			EditCourse($cID);
			}
			else
			{
				$name=$_POST['coursename'];
				if($name != "")
				{
					UpdateCourse($cID,$name,$GLOBALS['userID']);
					echo "<h2 align=center class='info'>درس با موفقیت ویرایش شد</h2><br>";
					echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditCourse'><button  class='btn btn-primary'>بازگشت به لیست دروس</button></a>";
				}
			}
		}
	}
	
	if($actDeleteCourse != "")
	{
		if(!isset($_GET['courseID']))
		{
			
			echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditCourse'><button  class='btn btn-primary'>بازگشت به لیست دروس</button></a>";
		}
		else
		{
			$cID = $_GET['courseID'];
			if(!isset($_GET['confirm']))
			{
				echo "<b><h3 style='color:darkred;text-align:center;'>";
				echo "هشدار! در صورتی که درس حذف شود کلیه تمرین های ارسال شده برای درس نیز حذف می شود آیا برای حذف اطمینان دارید؟";
				echo "</h2></b><br />";
				echo "<div style='text-align:center;'>";
				echo "<a href='Admin.php?act=EditCourse'><button style='width:300px;' class='btn btn-success btn-lg'>خیر</button></a>";
				echo "<a href='Admin.php?act=DeleteCourse&courseID=$cID&confirm=1'><button style='width:300px;' class='btn btn-danger btn-lg'>بله</button></a>";
				echo "</div>";
			}
			
			else
			{
				DeleteCourse($cID,$GLOBALS['userID']);
				echo "<h2 align=center class='info'>درس با موفقیت حذف شد</h2><br>";
					echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditCourse'><button  class='btn btn-primary'>بازگشت به لیست دروس</button></a>";
			}
			
		}
	}
	
	if($actEditEx != "")
	{
		if(!isset($_GET['exID']))
		{
			ShowAllExercises();
		}
		else
		{
			$eID = $_GET['exID'];
			if(!$_POST)
			{
					EditExercise($eID);
			}
			else
			{
				$cID = $_POST['course'];
				$title = $_POST['title'];
				$d = $_POST['deadline'];
				UpdateExercise($eID,$title,$d,$cID,$GLOBALS['userID']);
				echo "<h2 align=center class='info'>تمرین با موفقیت ویرایش شد</h2><br>";
					echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditEx'><button  class='btn btn-primary'>بازگشت به لیست تمرین ها</button></a>";
				
			}
		}
	}
	
	if($actDeleteEx != "")
	{
		if(!isset($_GET['exID']))
		{
			echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditEx'><button  class='btn btn-primary'>بازگشت به لیست تمرین ها</button></a>";
		}
		else
		{
			$eID = $_GET['exID'];
			if(!isset($_GET['confirm']))
			{
				echo "<b><h3 style='color:darkred;text-align:center;'>";
				echo "هشدار! در صورتی که  تمرین حذف شود کلیه تمرین های ارسال شده نیز حذف می شود آیا برای حذف اطمینان دارید؟";
				echo "</h2></b><br />";
				echo "<div style='text-align:center;'>";
				echo "<a href='Admin.php?act=EditEx'><button style='width:300px;' class='btn btn-success btn-lg'>خیر</button></a>";
				echo "<a href='Admin.php?act=DeleteEx&exID=$eID&confirm=1'><button style='width:300px;' class='btn btn-danger btn-lg'>بله</button></a>";
				echo "</div>";
			}
			else
			{
				DeleteExercise($eID,$GLOBALS['userID']);
				echo "<h2 align=center class='info'>تمرین با موفقیت حذف شد</h2><br>";
					echo "<a style='text-align:center;display:block;' href='Admin.php?act=EditEx'><button  class='btn btn-primary'>بازگشت به لیست تمرین ها</button></a>";
			}
		}
	}
	else if($actSent != "" || $actHome != "")
	{
		
		if(isset($_GET['fID']) && isset($_GET['c']))
		{
			SetExerciseStatus($_GET['fID'],$GLOBALS['userID'],$_GET['c']);
		}
		if($actSent != "")
			ShowSentEx($GLOBALS['userID']);
		else
		{
			PrintLastSent();
			ShowSentEx($GLOBALS['userID'],5);
		}
	}
		
		
?>

