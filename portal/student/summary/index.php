<?php include '../../config.php';?>
<?php
	if(!isset($_SESSION['student'])){
		header("Location: ../");
	}
	$con = mysql_query("select * from renlistments where student='".$_SESSION['student']."' and ay='".enlistment('ay')."' and sem='".sem('1',enlistment('sem'))."'");
	if(mysql_num_rows($con)==0){
		mysql_query("update rstudents set status='0' where student='".$_SESSION['student']."'") or die(mysql_error());
		header('location: ../');
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
			
			windowHeight = $(window).height() - 310;
		
			$('#enlistments').flexigrid({
				url: 'functions/list.php',
				dataType: 'json',
				colModel : [
					{display: 'SUBJECT CODE', name : 'SubjectCode', width : 80, align: 'center'},
					{display: 'SUBJECT TITLE', name : 'SubjectTitle', width : 460, align: 'center'},
					{display: 'LEC.', name : 'Lec', width : 50, align: 'center'},
					{display: 'LAB.', name : 'Lab', width : 50, align: 'center'},
					{display: 'PREREQUISITE', name : 'Prerequisite', width : 100, align: 'center'},
					{display: 'YEAR', name : 'Year', width : 50, align: 'center', hide: true},
					{display: 'SEM.', name : 'Sem', width : 50, align: 'center', hide: true},
					{display: 'TIME', name : 'Time', width : 110, align: 'center'},
					{display: 'STATUS', name : 'Time', width : 110, align: 'center'},
					{display: 'NOTES', name : 'Notes', width : 250, align: 'left', hide: false},
				],
				searchitems : [
					{display: 'Subject Code', name : 'a.subject_code'}
				],
				pagestat: 'Displaying {total} Records',
				nomsg: 'Search has no results.',
				title: 'ENLISTMENT SUMMARY : <span><?php echo strtoupper(enlistment('sem').' '.enlistment('ay')) ?></span>',
				height: windowHeight
			});

			$('#delete').click(function(){
				var trs = $('#enlistment > tbody tr');
				var row = $('.trSelected');
				var items = $('.trSelected :nth-child(9) > div');
				var itemid = $('.trSelected :nth-child(1) > div');
				var count = 0;
				var itemlist = "";
				$.each(items,function(i){
					if(items[i].innerHTML!="Approved"){ itemlist+=itemid[i].innerHTML + ",";count+=1; }
					else{ $(row[i]).toggleClass('trSelected'); }
				});
				if(count>0){
					var conf = confirm(count+" selected subjects.\nAre you sure you want to cancel the selected subject?");
					if(conf==true){
						$.ajax({
							type: "POST",
							url: "functions/delete.php",
							data: {
								subjects: itemlist
							},
							success:function(i){
								
								if(trs.length==0){
									window.location='../enlistment';
								}else{
									$('#enlistments').flexReload();
								}
							}
						});
					}else{ $(row[i]).toggleClass('trSelected');}
				}else{
					alert("Select the subjects you want to cancel.");
				}
				
			});
			
			setInterval(function(){
				var items = $('tr :nth-child(9) > div');
				$.each(items,function(i){
					if(items[i].innerHTML=="Disapproved"){
						$(items[i]).css('color','red');
					}else if(items[i].innerHTML=="&nbsp;" || items[i].innerHTML=="" || items[i].innerHTML=="REMARKS"){
						$(items[i]).css('color','');
					}else if(items[i].innerHTML=="Approved"){
						$(items[i]).css('color','green');
					}
				});
			},1);
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
			<table id="enlistments" style="display:none"></table>
			<div style="background:url(../../source/images/wbg.gif) repeat-x top;padding:8px 0px 8px 10px;height:25px;">
				<button class="btn right" id="delete"> DELETE </button>
				<button class="btn right" onclick="window.location='../edit-enlistment'"> EDIT </button>
			</div>
	</div>
	
	<div class="footer"></div>
</body>
</html>