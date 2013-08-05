<?php include '../../../config.php';?>
<?php
	header("Content-type: text/x-json");
	
	$student = filt($_SESSION['student']);
	$curriculum = filt(student($_SESSION['student'],'curriculum'));
	$course = filt(student($_SESSION['student'],'course'));
	$academicyear = filt(enlistment('ay'));
	$semester = filt(sem('1',enlistment('sem')));
	
	
	$qry = mysql_query("select a.course_code,b.title,b.lec,b.lab,b.prereq,b.year,b.sem,a.time,a.status,a.notes
						from renlistments as a inner join rsubjects as b on a.course_code = b.subject 
						where a.student='$student' and a.ay='$academicyear' and a.sem='$semester' and b.curriculum='$curriculum' and b.course='$course'") or die(mysql_error());
	
	echo "{page: '".mysql_num_rows($qry)."',rows: [\n";
	while($r=mysql_fetch_array($qry)){
		echo "{id: '".$r[0]."', cell: ['".$r[0]."','".$r[1]."','".$r[2]."','".$r[3]."','".$r[4]."','".$r[5]."','".$r[6]."','".$r[7]."','".$r[8]."','".$r[9]."']},\n";
	}
	echo "]}";
	
	
?>
