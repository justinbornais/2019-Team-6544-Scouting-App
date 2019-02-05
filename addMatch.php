<?php include('includes/database.php'); ?>
<?php
	$numRows = 1;
	$startMatchNum = 1;
	if(isset($_GET['num'])) {
		$numRows = $_GET["num"];
		$startMatchNum = $_GET["start"];
	}
    $query = "SELECT DISTINCT teamNumber
                FROM team_info
                ORDER BY teamNumber
                ";
    //Get results
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$doc = new DomDocument;
	$values = array('blue1Team','blue2Team','blue3Team','red1Team','red2Team','red3Team');
?>
<?php
	function send() {
		if($_POST){
			//Get variables from post array
			$check = ($_POST['blueTeam1']);
			if($numRows > 1) {
				$blueTeam1 = explode ("|", $_POST['blue1Team']);
				$blueTeam2 = explode ("|", $_POST['blue2Team']);
				$blueTeam3 = explode ("|", $_POST['blue3Team']);
				$redTeam1 = explode ("|", $_POST['red1Team']);
				$redTeam2 = explode ("|", $_POST['red2Team']);
				$redTeam3 = explode ("|", $_POST['red3Team']);
				for($i = 0;i < $numRows;$i++) {
					$matchNumber = $i + 1;
					$query = "INSERT INTO matches (matchNumber, blueTeam1, blueTeam2, blueTeam3, redTeam1, redTeam2, redTeam3)
										VALUES ('$matchNumber','$blueTeam1[$i]','$blueTeam2[$i]','$blueTeam3[$i]','$redTeam1[$i]','$redTeam2[$i]','$redTeam3[$i]')";
					$mysqli->query($query);
				}
			}
			else {
				$matchNumber = $startMatchNum;
				$blueTeam1 = ($_POST['blue1Team']);
				$blueTeam2 = ($_POST['blue2Team']); 
				$blueTeam3 = ($_POST['blue3Team']);
				$redTeam1 = ($_POST['red1Team']);
				$redTeam2 = ($_POST['red2Team']);
				$redTeam3 = ($_POST['red3Team']);
				$query = "INSERT INTO matches (matchNumber, blueTeam1, blueTeam2, blueTeam3, redTeam1, redTeam2, redTeam3) 
									VALUES ('$matchNumber','$blueTeam1','$blueTeam2','$blueTeam3','$redTeam1','$redTeam2','$redTeam3')";
				$mysqli->query($query);
			}

			$msg='Match Info Added';
			header('Location: teamList.php?'.urlencode($msg).'');
			exit;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<!-- javascript to php = $var = "<script> document.write(var); </script>"; -->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="FIRSTicon_RGB_withTM.jpg">
    <title>A-Team Robotics Scouting Page</title>
    <!-- Bootstrap core CSS -->
		
    <link href="css/main.css" rel="stylesheet">
    <!-- Custom styles for this template -->
		<link href="css/custom.css" rel="stylesheet">
		<style>
			table {
				border: 1px solid black;
				width: 75%;
				position: relative;
				left: 30px;
			}
			tr, td, th {
				border: 1px solid black;
			}
			td, th {
				text-align: center;
			}
			textarea {
  			resize: none;
			}
			p {
				margin: 0;
				display: inline;
			}
		</style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h3 style="color:purple; font:bold;">A-Team Scouting Page</h3>
        <ul class="nav nav-pills pull-right">
          <li><a href="homePage.php">Home Page</a></li>
          <li><a href="teamList.php">Team List</a></li>
          <li><a href="addTeam.php">Add Team</a></li>
			<li><a href="robot.php">Add Robot</a></li>
			<li><a href="scoutTeam.php">Scout Team</a></li>
          <li class="active"><a href="<?php echo "addMatch.php?num=".$numRows."&start=".$startMatchNum?>">Add Match Info</a></li>
				</ul>
  	  </div>
		</div>
		<br />
		<div id="matchTable">
			<table id="matches">
				<tr>
					<th>Match Number</th>
					<th>Blue Team 1</th>
					<th>Blue Team 2</th>
					<th>Blue Team 3</th>
					<th>Red Team 1</th>
					<th>Red Team 2</th>
					<th>Red Team 3</th>
				</tr>
				<?php
					for($num = 0;$num < $numRows;$num++) {
						echo '<tr><td>'. ($num + 1). '</td>';
						$count = 0;
						for($i = 0;$i < 6;$i++) {
							$value = $values[$count].$num;
							echo '<td><select id="'.$value.'">';
							
							while ($row = mysqli_fetch_array($result)) {
								echo '<option value="'.$row['teamNumber'].'">' . $row['teamNumber'] . '</option>';
							}
							echo '</select></td>';
							mysqli_data_seek($result, 0);
							$count++;
						}
						echo '</tr>';
					}
					echo '</table>';

					
					function submit($numRows, $doc, $values, $mysqli) {

						$blueTeam1 = array_fill(0, abs($numRows), 0);
						$blueTeam2 = array_fill(0, abs($numRows), 0);
						$blueTeam2 = array_fill(0, abs($numRows), 0);
						$redTeam1 = array_fill(0, abs($numRows), 0);
						$redTeam2 = array_fill(0, abs($numRows), 0);
						$redTeam3 = array_fill(0, abs($numRows), 0);
						for($num = 0;$num < $numRows;$num++) {
							$matchNumber = $num + 1;
							$blueTeam1 = $doc->getElementById($values[0].$num);
							$blueTeam2 = $doc->getElementById($values[1].$num);
							$blueTeam3 = $doc->getElementById($values[2].$num);
							$redTeam1 = $doc->getElementById($values[3].$num);
							$redTeam2 = $doc->getElementById($values[4].$num);
							$redTeam3 = $doc->getElementById($values[5].$num);

							$query = "INSERT INTO matches (matchNumber, blueTeam1, blueTeam2, blueTeam3, redTeam1, redTeam2, redTeam3)
													VALUES ('$matchNumber','$blueTeam1','$blueTeam2','$blueTeam3','$redTeam1','$redTeam2','$redTeam3')";
							$mysqli->query($query);
						}
						echo "It is completed.";
					}
				?>
				
				<div id="hidden_form_container" style="display:none;">
					<script type="text/javascript">
						function getSelectionValue(rowNum, columnNum) {
							document.cookie = "rowNum=" + rowNum;
							//FOR EXTERNAL PHP FILE
							//window.location = "http://example.com/file.php";
							var id =
								<?php
								$index = 0;
								$row = 0;
								if ( ! empty( $_COOKIE['rowNum'] ) ) {
									$row = $_COOKIE['rowNum'];
								}
								echo '"'.$values[$index].$row.'"';
								?>;
							var e = document.getElementById(id);
							var selectedValue = e.options[e.selectedIndex].value;
							return selectedValue;
						}

						function postRefreshPage() {
							var theForm, newInput1, newInput2, newInput3, newInput4, newInput5, newInput6;
							var rows = <?php echo $numRows; ?>;
							var nums1 = new Array(rows);
							// Start by creating a <form>
							theForm = document.createElement('form');
							theForm.action = 'addMatch.php';
							theForm.method = 'post';
							// Next create the <input>s in the form and give them names and values
							newInput1 = document.createElement('input');
							newInput1.type = 'hidden';
							newInput1.name = 'blue1Team';
							newInput1.id = 'blue1Team'; //new concept
							newInput1.value = "";
							for(var i = 0;i < rows;i++) {
								newInput1.value += getSelectionValue(i, 0);
								if((i + 1) != rows) {
									newInput1.value += "|";
								}
							}

							newInput2 = document.createElement('input');
							newInput2.type = 'hidden';
							newInput2.name = 'blue2Team';
							newInput2.id = 'blue2Team';
							newInput2.value = "";
							for(var i = 0;i < rows;i++) {
								newInput2.value += getSelectionValue(i, 1);
								if((i + 1) != rows) {
									newInput2.value += "|";
								}
							}
							alert(newInput2.value);
							
							newInput3 = document.createElement('input');
							newInput3.type = 'hidden';
							newInput3.name = 'blue3Team';
							newInput3.id = 'blue3Team';
							newInput3.value = "";
							for(var i = 0;i < rows;i++) {
								newInput3.value += getSelectionValue(i, 2);
								if((i + 1) != rows) {
									newInput3.value += "|";
								}
							}
							
							newInput4 = document.createElement('input');
							newInput4.type = 'hidden';
							newInput4.name = 'red1Team';
							newInput4.id = 'red1Team';
							newInput4.value = "";
							for(var i = 0;i < rows;i++) {
								newInput4.value += getSelectionValue(i, 3);
								if((i + 1) != rows) {
									newInput4.value += "|";
								}
							}
							
							newInput5 = document.createElement('input');
							newInput5.type = 'hidden';
							newInput5.name = 'red2Team';
							newInput5.id = 'red2Team';
							newInput5.value = "";
							for(var i = 0;i < rows;i++) {
								newInput5.value += getSelectionValue(i, 4);
								if((i + 1) != rows) {
									newInput5.value += "|";
								}
							}
							
							newInput6 = document.createElement('input');
							newInput6.type = 'hidden';
							newInput6.name = 'red3Team';
							newInput6.id = 'red3Team';
							newInput6.value = "";
							for(var i = 0;i < rows;i++) {
								newInput6.value += getSelectionValue(i, 5);
								if((i + 1) != rows) {
									newInput6.value += "|";
								}
							}
							// Now put everything together...
							theForm.appendChild(newInput1);
							theForm.appendChild(newInput2);
							theForm.appendChild(newInput3);
							theForm.appendChild(newInput4);
							theForm.appendChild(newInput5);
							theForm.appendChild(newInput6);
							// ...and it to the DOM...
							document.body.appendChild(theForm);
							//document.getElementById('hidden_form_container').appendChild(theForm);
							// ...and submit it
							theForm.submit();
							//location.reload();
						}
					</script>
				</div>
				<button type="button" onclick="alert(getSelectionValue(2, 0));">Do it.</button>
				<button type="button" onclick="postRefreshPage();">Submit</button>
		</div>
      <div class="footer">
			<p style="color:purple;">&copy; A-Team Robotics 2018</p>
      </div>
	</div> <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>