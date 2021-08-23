<html>

	<head>
		<title>Phonebook</title>
		<style>
		/* remove annoying selection highlights */
		::selection {
			background: none;
		}

		/* Make placeholder in texboxes look like values. We don't want to set the numbers as values becuase the values will be the updated numbers. This is a little trick to make my life easier.*/
		::placeholder{
			opacity: 100%;
			font-family: sans-serif;
	
		}

		/* make textbox invisible*/
		.number{
			font-family: sans-serif;
			border: hidden;
			outline: none;
			width: 225px;
			line-height:0%;
			padding: 7px;
			background-color: #ebebeb;

		}

		/* When hover, change color to a darker grey*/		
		.number:hover {
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
			background-color: white;
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
		.numinput {
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
		 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		  <script>
		  $(function(){
		    $("#draggable").draggable();
		  });
		  </script>
	</head>
	
	<body>
	

 
		<div class='div1' id="draggable">
			<h2>PhoneBook</h2>	
			<!-- Form to send a post request to this php file -->
			<form name="phonebook" action="phonebook.php" method="POST" id="phonebook">
				<input class="numinput" type="text" max="10" name="number" value="">
				<!-- hidden input we will use to delete a number -->
				<input type="hidden" name="delnum" value=""/>
				<br />
				<br />
				<button type="submit">Add</button>
				<button type="submit" name="delete_all" value="true">Delete All</button>
			</form>	
			<form action='phonebook.php' method='POST'>
	

<?php

//include the database configuration file
include_once 'dbconf.php';

//Get numbers and id from the phonebook table
$sql = "SELECT numbers, id FROM phonebook;";
$result = mysqli_query($connect, $sql);
//get number of rows in the phonebook table
$checkResult = mysqli_num_rows($result);

//if our table isn't empty, loop through every row and render results
if($checkResult > 0){
	while($row = mysqli_fetch_assoc($result)){
		//echo number as a text input, because we will use this to edit each number. When the user clicks, we will set the number ID as value to the delnum input and submit it as a post request.
		//TL;DR Give delnum the row ID so we can identify each number. Useful for deleting and editing numbers
		echo "<input name='edit_num[\"".$row['id']."\"]' class='number' type='text' ondblclick=\"document.phonebook.delnum.value='".$row['id']."';document.getElementById('phonebook').submit();\" placeholder='".$row['numbers']."'/><br/>";
		
	}
}

//Check if the request is a POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//If the number parameter is not empty, insert the data into the database
	if(isset($_POST['number'])){
		$number = $_POST['number'];
		//Generate a random ID
		$id = rand();
		//Insert number and ID to database
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

	/* looping through the dictionary when a user tried to update a value*/
	foreach($_POST['edit_num'] as $id => $new_num){
			#check for updated number
			if ($new_num){
				#sql statement to update the number by id
				$sql = "UPDATE phonebook SET numbers = ".$new_num." WHERE id = ".$id;
				mysqli_query($connect, $sql);
				#refresh page
				header("Location: phonebook.php");

			}
			
		}
	
}

//close mysql connection
mysqli_close($connect);

?>
<!-- end html -->
<button type="submit" hidden></button>
</form>
</div>

</body>

</html>
