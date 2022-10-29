<?php
	function GetYear($inp)
	{
	
		$c=explode("-",$inp);
		return $c[0];
	}
	function GetMonth($inp)
	{
	
		$c=explode("-",$inp);
		return $c[1];
	}
	
	function GetDay($inp)
	{
	
		$c=explode("-",$inp);
		return $c[2];
	}
?>