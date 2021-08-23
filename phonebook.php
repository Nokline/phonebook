<html>

	<head>
		<title>Phonebook</title>
		<style>
		/* Remove bullets from <li>, move each number a little bit to the right*/
		li{

			list-style-type: none;
			line-height:0%;
			padding: 7px;

		}

		/* When hover, change color to a darker grey*/		
		li:hover {
		  background-color: #dbdbdb;

		}

		/* center everything in this html file and change font */
		html{
				    height: 50%;
				}
		
		html {
		    display: table;
		    margin: auto;
		}
		
		body {
		    display: table-cell;
		    vertical-align: middle;
		    font-family: sans-serif;
		}

		/* Background color, border, shadow and margins for the phonebook itself*/
		.div1{
			background-color: #ebebeb;
			border: 3px solid black;
			margin:100px;
			box-shadow: 5px 10px #888888;

		}

		/* Align title inside of div*/
		h2{
			text-align: center;

		}

		/* Hide the default text input border and highliting. Also change the width of the text box*/
		input {
			border: hidden;
			outline: none;
			width: 225px;

		}

		/* change buttons size to make it look more centered */
		button {
			width:90px;
			margin: 10px;

		}
		
		</style>
	</head>

	<body>
		<div class='div1'>
			<h2>PhoneBook</h2>	
			<!-- Form to send a post request to this php file -->
			<form name="phonebook" action="phonebook.php" method="POST" id="phonebook">
				<input type="text" max="10" name="number" value="">
				<!-- hidden input we will use to delete a number -->
				<input type="hidden" name="delnum" value=""/>
				<br />
				<br />
				<button type="submit">Submit</button>
				<button type="submit" name="delete_all" value="true">Delete All</button>
			</form>	
	</body>

</html>

<?php

//include the database configuration file
include_once 'dbconf.php';

//Get numbers and id from the phonebook table
$sql = "SELECT numbers, id FROM phonebook;";
$result = mysqli_query($connect, $sql);
//get number of rows in the phonebook table
$checkResult = mysqli_num_rows($result);

//if our table isn't empty, loop through every row and echo results
if($checkResult > 0){
	while($row = mysqli_fetch_assoc($result)){
		//echo number as a list. When the user clicks, we will set the number ID as value to the delnum input and submit it as a post request.
		//TL;DR Give delnum the row ID so we can identify each number. Useful for deleting numbers
		echo "<li onclick=\"document.phonebook.delnum.value='".$row['id']."';document.getElementById('phonebook').submit();\">".$row['numbers']."</li><br/>";
		
	}
}

//Check if the request is a POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//If the number parameter is not empty, insert the data into the database
	if(isset($_POST['number'])){
		$number = $_POST['number'];
		//Generate a random ID
		$id = rand();
		echo "<li>$number</li>";
		//Inser number and ID to database
		$sql = "INSERT INTO phonebook (numbers, id) VALUES ($number, $id)";
		mysqli_query($connect, $sql);
		//Refresh page
		header("Location: phonebook.php");

	}
	//Check if user clicked the delete all button
	if($_POST['delete_all'] == 'true'){
		//delete everything from the phonebook table
		$sql = "DELETE FROM phonebook";
		mysqli_query($connect, $sql);
	
	}
	//Check if user wants to delete a number
	if($_POST['delnum']){
		//Delete row by the number ID
		$delnum = $_POST['delnum'];
		$sql = "DELETE FROM phonebook WHERE id=$delnum";
		mysqli_query($connect, $sql);
		//refresh page
		header("Location: phonebook.php");
	}
	
}

//close mysql connection
mysqli_close($connect);

?>
<!-- end div -->
</div>
