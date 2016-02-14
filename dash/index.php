<?php
$db = new SQLite3("/home/pi/speeds.db");

$limit = isset($_GET['n']) ? $_GET['n'] : 10;

$q = "SELECT timestamp, ping, up, down FROM speeds ORDER BY timestamp DESC limit $limit";

$results = $db->query($q);
$speeds = array();
while($row = $results->fetchArray()){
	$speeds[] = $row;
}
$speeds = array_reverse($speeds);

$ip = "99.236.83.180";
$lines = @file("/home/pi/ip.out");
foreach($lines as $line){
	$ip = $line;
}
?>
<!doctype html>
<html>
  <head>
    <title>Network Stats</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<style>
	.container .jumbotron{
		background-color: rgba(51, 122, 183, 0.65);
		color: white;
		padding: 1px 40px;
	}

	@media (min-width: 1200px){
		.container{
			width: 1380px;
		}
	}

	#speeds tbody tr:last-child{
		background-color: #d9edf7;
	}
	</style>
	<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/data.js"></script>
	<script>
		$(function(){
			$("time").each(function(){
				$(this).text((new Date($(this).data('unix') * 1000)).toLocaleString());
			});
			$("#speed-container").highcharts({
				chart: {
					type: "spline",
				},
				title:{
					text: "Last <?=$limit?> readings"
				},
				data: {
					table: "speeds",
				},
			});
		});
	</script>
</head>
<body class="container">
	<div class="page-header">
		<h1>Network Stats <small class="pull-right">Public IP Address: <?=$ip?></small></h1>
  	</div>
        <div class="row">
                <div class="col-sm-6 col-md-3">
                        <div class="jumbotron">
                                <h1>0</h1>
                                <p>ads blocked today</p>
                        </div>
                </div>
                <div class="col-sm-6 col-md-3">
                        <div class="jumbotron">
                                <h1>0%</h1>
                                <p>of today's traffic is ads</p>
                        </div>
                </div>
                <div class="col-sm-6 col-md-3">
                        <div class="jumbotron">
                                <h1>0</h1>
                                <p>total DNS queries</p>
                        </div>
                </div>
                <div class="col-sm-6 col-md-3">
                        <div class="jumbotron">
                                <h1>0</h1>
                                <p>domains being blocked</p>
                        </div>
                </div>
        </div>
	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Internet Speeds</h3>
				</div><!-- .panel-heading -->
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<div id="speed-container"></div>
						</div>
						<div class="col-md-6">
							<table class="table" id="speeds">
								<thead>
									 <tr>
		 								<th>Time</th>
										<th>Ping (ms)</th>
			     							<th>Down (Mbits/s)</th>
			      							<th>Up (Mbits/s)</th>
					     				</tr>
			    					</thead>
			    					<tbody>
					  			<?php
			    					foreach($speeds as $row){
								?>
									<tr>
										<td><time data-unix="<?=strtotime($row['timestamp'])?>"></time></td>
										<td><?=$row['ping']?></td>
										<td><?=$row['down']?></td>
										<td><?=$row['up']?></td>
									</tr>
								<?php
				    				}
								?>
    								</tbody>
							</table>
						</div><!-- .col-md-6-->
					</div><!-- .row -->
				</div><!-- .panel-body -->
			</div><!-- .panel -->
		</div><!-- .col-md-9 -->
		<div class="col-md-3">
                        <div class="panel panel-success">
                                <div class="panel-heading">
                                        <h3 class="panel-title">Connected Devices</h3>
                                </div>
                                <table class="table">
                                        <tbody>
                                        <?php
                                        $lines = @file("/home/pi/devices.list");
                                        foreach($lines as $line){
						$i = explode(": ", $line);
                                        ?>
                                                <tr>
                                                        <td><?=$i[0]?></td>
							<td><?=$i[1]?></td>
                                                </tr>
					<?php
                                        }
                                        ?>
                                        </tbody>
                                </table>
                        </div>
		</div>
	</div><!-- .row -->
</body>
</html>


