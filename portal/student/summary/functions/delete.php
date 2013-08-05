<?php include '../../../config.php';?>
<?php
	$subjects = explode(',',substr($_POST['subjects'],0,-1));
	$student = filt($_SESSION['student']);
	$academicyear = filt(enlistment('ay'));
	$semester = filt(sem('1',enlistment('sem')));
	
	foreach($subjects as $subject){
		mysql_query("delete from renlistments where student='$student' and course_code='$subject' and ay='$academicyear' and sem='$semester'") or die(mysql_error());
	}
?>