<?php 
include 'pagination1.php';
include "connection.php";
/*$sql =  "select a.dateCreated,a.id,a.c_txtNoTicket,a.c_fldApprAnalystBy,a.c_fldAssignTo,concat(b.firstName,'  (',a.c_fldAssignTo,')') 
									as c_fldAssign,a.c_assignToUser from app_fd_acctmaintenance a join dir_user b
								on a.c_fldAssignTo COLLATE utf8_general_ci=b.username where a.c_assignToUser='true' order by a.dateCreated asc";
	*/
	$sql =  "select a.dateCreated,a.id,a.c_txtNoTicket,a.c_fldApprAnalystBy,b.firstName,a.c_fldAssignTo,concat(b.firstName,'  (',a.c_fldAssignTo,')') 
									as c_fldAssign,a.c_assignToUser from app_fd_acctmaintenance a join dir_user b
								on a.c_fldAssignTo COLLATE utf8_general_ci=b.username where a.c_assignToUser='true' order by a.dateCreated asc";
	$reload = "index.php?pagination=true";
    $result = mysql_query($sql);
	$rwdt = mysql_fetch_array($result);
	$rowcount = mysql_num_rows($result);    
    $rpp = 10; // jumlah record per halaman
    $page = intval($_GET["page"]);
    if($page<=0) $page = 1;  
    $tcount = mysql_num_rows($result);
    $tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
    $count = 0;
    $i = ($page-1)*$rpp;
    $no_urut = ($page-1)*$rpp;
	error_reporting(0) 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		  $(document).ready(function(){
		   $(document).on("click", "#pass", function () {
		   var tick1 = $(this).data('ticket'); var tick="";
		   if(tick1!=""){ tick=tick1;}else{tick="None";}
		   var create1 = $(this).data('created'); var create="";
		   if(create1!=""){ create=create1;}else{create="None";}
		   var assign1 = $(this).data('assign'); var assign="";
		   if(assign1!=""){ assign=assign1;}else{assign="None";}
		   var id1 = $(this).data('id'); var id="";
		   if(id1!=""){ id=id1;}else{id="None";}
		    var name1 = $(this).data('name'); var name="";
		   if(name1!=""){ name=name1;}else{name="None";}
			
		   //alert(id+assign+create+tick);
			 $(".modal-body #tickno").val( tick );
			 $(".modal-body #date").val( create );
			 $(".modal-title #tickno").val( tick );
			 $(".modal-body #nip").val( assign );
			 $(".modal-body #name").val( name );
			$(document).on("click",".modal-footer #save",function(event){
			//alert(id+assign+create+tick+data);
			window.location.href="update.php?srchby="+data+"&idPro="+id+"&tiket="+tick+"&userId="+assign;
			});
			var data = "";
			$(document).on("change",".modal-body #srchby",function(event){
			data=$(".modal-body #srchby").val();
			});
			});
			
			$(document).on("click", "#detail", function () {
		   var id1 = $(this).data('id'); var id="";
		   if(id1!=""){ id=id1;}else{id="None";}
		     var tick1 = $(this).data('ticket'); var tick="";
		   if(tick1!=""){ tick=tick1;}else{tick="None";}
		   $(".modal-title #tickno").val( tick );
		   document.getElementById('myframe').src='http://10.14.18.222/adminAm/detailAssign.php?id='+id;
			document.getElementById('myframe').style.width='580px';
			document.getElementById('myframe').style.height='380px';
			document.getElementById('myframe').style.border='none';
			document.getElementById('myframe').scrolling='no';
			});
			});
		  </script>
	<style>
		table {
			border-collapse: collapse;
			width: 100%;
		}

		th, td {
			text-align: center;
			vertical-align: middle;
		}

		tr:nth-child(even){background-color: #f2f2f2}

		th {
			background-color: #4CAF50;
			color: white;
		}
	</style>
</head>
<body>
    <div class="container">
    		<div class="row">
    			<h3><center><strong>Admin for Mapping User</strong></center></h3>
    		</div>
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>No</th>
						  <th>Date Created</th>
		                  <th>No Ticket</th>
		                  <th>Assign To</th>
						  <!--<th>Change Assign To</th>-->
		                  <th>Action</th>
						  <th>Log History</th>
		                </tr>
		              </thead>
		              <tbody>
						<?php
						while(($count<$rpp) && ($i<$tcount)) {
                        mysql_data_seek($result,$i);
                        $data = mysql_fetch_array($result);
						$createdate = $data['dateCreated'];
						$id = $data['id'];
						$name = $data['firstName'];
						$noTicket = $data['c_txtNoTicket'];
						$assignTo = $data['c_fldAssignTo'];
						$assign = $data['c_fldAssign'];
						$datamodal="data-created=$createdate data-ticket=$noTicket data-assign=$assignTo data-id=$id data-name=$name";
						$dataid="data-id=$id data-ticket=$noTicket";
						?>
                    <tr>
                        <td><?php echo ++$no_urut;?></td>
                        <td><?php echo $createdate; ?></td>
						<td><?php echo $noTicket; ?></td>
						<td><?php echo $assign; ?></td>
						<td width=70><a href="#myModal" data-toggle="modal" <?php echo $datamodal;?> id="pass" name="pass"class = "btn btn-primary">ReAssign</a></td>
						<td width=70><a id="detail" name="detail" href="#myModal2" data-toggle="modal" <?php echo $dataid;?> class = "btn btn-primary">Detail</a></td>
					</tr>
                    <?php
                        $i++; 
                        $count++;
                    }
                    ?>
				</tbody>
	        </table>
			<div><?php echo paginate_one($reload, $page, $tpages,$rowcount); ?></div>
		<div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
				<div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Data Assignment : <input type="text" name="tickno" id="tickno" value="" style="border: none;background: transparent;"disabled/></h4>
                  </div>
                  <div class="modal-body">
                  <table width="100%" align="center">
					<tr></tr>
					<tr>
						<td><B>Date Created</B></td><td>:</td><td><input type="text" name="date" id="date" class="form-control" value="" style="border: none;background: transparent;" disabled/></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td><B>Ticket Number</B></td><td>:</td><td><input type="text" name="tickno" class="form-control" id="tickno" value="" style="border: none;background: transparent;" disabled/></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td><B>UserName</B></td><td>:</td><td><input type="text" name="nip" id="nip" class="form-control" value="" style="border: none;background: transparent;" disabled/></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td><B>Name</B></td><td>:</td><td><input type="text" name="name" id="name" class="form-control" value="" style="border: none;background: transparent;" disabled/></td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr>
					<td><B>Assignment To</B></td><td>:</td>
					<?php
					//select a.userId,b.firstName from dir_user_group a  join dir_user b on a.userId =b.username where a.groupId='groupAnalystLimitAM'
						$querys = "select a.userId,b.firstName from dir_user_group a  join dir_user b on a.userId =b.username where a.groupId='groupAnalystLimitAM'";
						$result2 = mysql_query($querys) or die(mysql_error()."[".$querys."]");

					?>
						<td>
						<select method="post" name="srchby" id="srchby" class="form-control">
						<option value="">Select</option>
						<?php 
							while ($row = mysql_fetch_array($result2))
							{
								echo "<option value=".$row['userId'].">".$row['firstName']."(".$row['userId'].")</option>";
							}
						?>     
						</select></td>
					</tr>
				  </table>
                  </div>
                  <div class="modal-footer">
					<button type="button" class = "btn btn-primary" id="save" name="save" data-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                </div>
            
              </div>
         </div>
		 <div id="myModal2" class="modal fade" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
				<div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">History Assignment : <input type="text" name="tickno" id="tickno" value="" style="border: none;background: transparent;"disabled/></h4>
                  </div>
                  <div class="modal-body">
				  <iframe id="myframe" name="myframe"></iframe>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>
                </div>
            
              </div>
         </div>
		 
    </div> <!-- /container -->
	<script type="text/javascript">
		function popupwin(url) {
		var width  = 480;
		var height = 300;
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', scrollbars=1';
		newwin=window.open(url,'popupwin', params);
		if (window.focus) {newwin.focus()}
		return false;
}
	function popupwin1(url) {
		var width  = 600;
		var height = 500;
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', scrollbars=1';
		newwin=window.open(url,'popupwin', params);
		if (window.focus) {newwin.focus()}
		return false;
}
</script>
  </body>
</html>