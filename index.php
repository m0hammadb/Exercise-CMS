<?php
	require_once("DBOps.php");
?>
<html>
	<head>
		<link href="Content/bootstrap.css" rel="StyleSheet" />
		<meta charset="utf-8">
		<link href="Content/bootstrap-theme.css" rel="StyleSheet" />
	</head>
	
	<body style="background:url('img/bg.jpg')">
	<div >
		<img src="img/Header.jpg" style="width:100%" />
	</div>
	<div class="panel col-md-3" style="padding:10px;margin-left:43em;margin-top:10em;border-radius:10px;">
		<form method="POST" class="center">
			<div class="form-group">
				<label for="username">نام و نام خانوادگی</label>
				<input type="text" class="form-control" name="username" placeholder="نام و نام خانوادگی خود را وارد کنید" />
			</div>
			<br />
			<div class="form-group">
				<label for="studentID">شماره دانشجویی</label>
				<input class="form-control" name="studentID" placeholder="شماره دانشجویی خود را وارد کنید" />
			</div>
			<br />
			<button type="submit" class="center-block btn btn-lg btn-default">ورود دانشجو</button>
			<?php
				if($_POST)
				{
					if(isset($_POST['username']) && isset($_POST['studentID']))
					{
						$u = $_POST['username'];
						$p = $_POST['studentID'];
						
						if($u != "" && $p != "")
						{
							session_start();
							$_SESSION["studentID"] = $p;
							$_SESSION["Name"] = $u;
							header("Location: Student.php");
						}
						
					}
				}
				else
				{
					session_start();
					session_unset();
					session_destroy();
				}
			?>
		</form>
		<h4 style="text-align:center;color:green;left:0px;right:0px;bottom:0px;">طراحی : محمد بشیری نیا</h4>
	</div>
	
	
	
	</body>
</html>

