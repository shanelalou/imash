<?php include '../../config.php';?>
<?php
	if(!isset($_SESSION['student'])){
		header("Location: ../");
	}
	
	$counts = mysql_query("select * from renlistments where student='".$_SESSION['student']."' and ay='".enlistment('ay')."' and sem='".sem('1',enlistment('sem'))."'");
	if(mysql_num_rows($counts)==0){
		mysql_query("update rstudents set status='0' where student='".$_SESSION['student']."'");
	}
	if(student($_SESSION['student'],"status")!="0"){
		header("Location: ../summary");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Student</title>
	<link rel="icon" type="image/png" href="../../source/images/icon.png">
	<link rel="stylesheet" type="text/css" href="../../source/styles/flexigrid.css">
	<link rel="stylesheet" type="text/css" href="../../source/styles/style.css">
	<script type="text/javascript" src="../../source/scripts/flexigrid.pack.js"></script>
	<script type="text/javascript" src="../../source/scripts/flexigrid.js"></script>
	<script>
		$(function(){
			
			windowHeight = $(window).height() - 355;
		
			$('#grid').flexigrid({
				url: 'functions/list.php',
				dataType: 'json',
				colModel : [
					{display: 'SUBJECT CODE', name : 'SubjectCode', width : 120, align: 'center'},
					{display: 'SUBJECT TITLE', name : 'SubjectTitle', width :460, align: 'center'},
					{display: 'LEC.', name : 'Lec', width : 60, align: 'center'},
					{display: 'LAB.', name : 'Lab', width : 60, align: 'center'},
					{display: 'PREREQUISITE', name : 'Prerequisite', width : 150, align: 'center'},
					{display: 'YEAR', name : 'Year', width : 60, align: 'center'},
					{display: 'SEM.', name : 'Sem', width : 60, align: 'center'},
				],
				searchitems : [
					{display: 'Subject Code', name : 'a.subject_code'}
				],
				pagestat: 'Displaying {total} Records',
				nomsg: 'Search has no results.',
				title: '', //'SELECT THE SUBJECTS YOU WANT TO ENLIST',
				height: windowHeight
			});
			
			$('.sDiv2 :nth-child(2),.pDiv2 :nth-child(2),.pDiv2 :nth-child(3),.pDiv2 :nth-child(4),.pDiv2 :nth-child(5),.pDiv2 :nth-child(6),.pDiv2 :nth-child(7),.pDiv2 :nth-child(8),.pDiv2 :nth-child(9)').hide();
			$('#error').hide();
			$('table').click(function(){
				var subjects = 0;
				var lecunits = 0;
				var labunits = 0;
				var items = $('.trSelected :nth-child(3) > div');
				var itemss = $('.trSelected :nth-child(4) > div');
				$.each(items,function(i){
					subjects+=1;
					lecunits += parseInt(items[i].innerHTML);
					labunits += parseInt(itemss[i].innerHTML);
				});
				if((lecunits + labunits)>30) $('#error').hide().fadeIn(1000).html("Please deselect other subjects. Units must not exceed to 30."); else $('#error').fadeOut(1000).html("");
				if(subjects>0) $('#subjects').html(subjects); else $('#subjects').html('0'); 
				if(lecunits>0) $('#lecunits').html(lecunits); else $('#lecunits').html('0'); 
				if(labunits>0) $('#labunits').html(labunits); else $('#labunits').html('0'); 
			});
			
			$('#enlist').click(function(){
				if($('#subjects').html()=="0")  alert("Please select the subjects you want to enlist.");
				else if($('#preferred-time').val()=="Select Preferred Time") alert("Please select your preferred time.");
				else if((parseInt($('#lecunits').html())+parseInt($('#labunits').html()))>30) alert("Please deselect other subjects. Units must not exceed to 30.");
				else {
					var sel = $('.trSelected :nth-child(1) > div');
					var list = "";
					$.each(sel,function(i){
						list = list + sel[i].innerHTML + ",";
					});
					$.ajax({
						type: "POST",
						url: "functions/save.php",
						data: {
							subjects: list,
							time: $('#preferred-time').val()
						},
						success: function(i){
							alert('Thank you for enlisting.\nWait for the approval of your enlistment.')
							window.location = '../summary';
						}
					});
				}
			});
		
		});
	</script>
</head>
<body>

	<div class="head">
		<div class="wraper">
			<div class="head-logo"></div>
			<div class="head-label">
				<div class="center" style="font-size:18px">COLLEGE OF COMPUTER STUDIES</div>
				<div class="center" style="font-size:15px">COURSE ENLISTMENT</div>
			</div>
			<div class="menu">
				<ul>
					<li><a href="../enlistment">ENLISTMENT</a></li>
					<li><a href="../grades">GRADES</a></li>
					<li><a href="../logout.php">LOGOUT</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="title">
		<div class="wraper">
			<div class="left" style="font-size:15px"><?php echo $_SESSION['student'].' - '.student($_SESSION['student'],"lastname").", ".student($_SESSION['student'],"firstname").", ".student($_SESSION['student'],"middlename")." - ".student($_SESSION['student'],"course")." ".student($_SESSION['student'],"year"); ?></div>
		</div>
	</div>
	<div class="page-content">
			<!-- ------------------------------------------------------------ -->
			<div style="background:url(../../source/images/wbg.gif) repeat-x top;padding:8px 0px 8px 10px;font-size:15px;">
				<span style="font-size:20px;">COURSE ENLISTMENT</span> : <span style="font-style:italic;font-size:16px;"><?php echo strtoupper(enlistment("sem")." A-Y: ".strtoupper(enlistment("ay")))?></span>
			</div>
			<form action="summary.php" method="post" id="form">
			<div style="background:url(../../source/images/wbg.gif) repeat-x top;padding:8px 0px 8px 10px;">
				<span class="normal">PREFERRED TIME: </span>
				<select class="normal" id="preferred-time" name="preferred-time" style="font-size:15px;">
					<option>Select Preferred Time</option>
					<option>MORNING</option>
					<option>AFTERNOON</option>
					<option>EVENING</option>
				</select>
				<span style="font-size:13px;font-style:italic;float:right;margin-right:20px;">Click to select a subject.</span>
			</div>
			<table id="grid" style="display:none"></table>
			<div style="background:url(../../source/images/wbg.gif) repeat-x top;padding:8px 0px 8px 10px;height:25px;">
				<table border="1" bordercolor="cccccc" style="border-collapse:collapse;display:inline-block;">
					<tr style="background:url(../../source/images/wbg.gif) repeat-x top;">
						<td class="normal" style="width:150px;">TOTAL SUBJECTS</td>
						<th class="normal" style="width:50px;;"><span class="normal" id="subjects">0</span></th>
						<td class="normal" style="width:125px;">LEC. UNITS</td>
						<th class="normal" style="width:50px;"><span class="normal" id="lecunits">0</span></th>
						<td class="normal" style="width:125px;">LAB. UNITS</td>
						<th class="normal" style="width:50px;"><span class="normal" id="labunits">0</span></th>
					</tr>
				</table>
			</div>
			</form>
			<button class="btn right normal" style="width:150px;display:inline-block;margin-top:-33px;" id="enlist">ENLIST</button>
	</div>
	
	<div class="footer"></div>
</body>
</html>