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
				<label for="username">Username:</label>
				<input type="text" class="form-control" name="username" placeholder="Username" />
			</div>
			<br />
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" class="form-control" name="password" placeholder="Password" />
			</div>
			<br />
			<button type="submit" class="center-block btn btn-lg btn-default">Login</button>
			<?php
				if($_POST)
				{
					if(isset($_POST['username']) && isset($_POST['password']))
					{
						$u = $_POST['username'];
						$p = $_POST['password'];
						
						$ret = Validate($u,$p);
						
						if($ret == -1)
						{
							echo "<br><p align=center style='color:red'>نام کاربری یا کلمه عبور اشتباه است</p>";
						}
						else
						{
							session_start();
							$_SESSION['user'] = $u;
							$_SESSION['pass'] = $p;
							$_SESSION['userID'] = $ret;
							header("Location: Admin.php");
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
	</div>
	</body>
</html>

