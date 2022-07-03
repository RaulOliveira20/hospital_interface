<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="main.css">
	<script src="functions.js" type="text/javascript"></script>
	<title>Hospital Interface</title>
</head>
<body>
	<div class="title_banner">
		<h1 id="title">Hospital Interface</h1>
	</div>
	<button class="back_button" type="button" onclick="go_back();">Go Back</button>

<?php 

function sch_result($result)
{
	if($result){
		echo '<script>alert("Schedule changed successfully!");</script>';
	} else {
		echo '<script>alert("Error in changing schedule.")</script>';
	}
}

$db = pg_connect("host=localhost port=5432 dbname=Hospital user=postgres password=postgres");

if(isset($_POST['register_button']))
{
	?>
	<div id="reg_menu">
	<h2 style="text-align: center;">Register new user</h2>
	<form action="test.php" method="post">
		<label id="s">Username: </label>
		<input type="text" name="user" required="required"><br><br>
		<label id="s">Password: </label>
		<input type="password" name="pass" id="pass" required="required"><br><br>
		<label id="s">Confirm Password: </label>
		<input type="password" name="conf_pass" id="conf_pass" required="required"><br><br>
		<label id="s">Type: </label>
		<select id="type" name="type">
		    <option value="staff">Hospital Staff</option>
		    <option value="admin">Administrator</option>
		</select>
		<br><br>
		<div id="reg">
			<input type="submit" name="register" value="Register" onclick="check_pass('conf_pass')">
		</div>
	</form>
	</div>

	<?php
}
else if(isset($_POST['login_button']))
{
	?>
	
	<div id="log_menu">
	<h2 style="text-align: center;">Login</h2>
	<form action="test.php" method="post">
		<label id="s">Username: </label>
		<input type="text" name="username" required="required"><br><br>
		<label id="s">Password: </label>
		<input type="password" name="password" id="pass" required="required"><br>
		<br><br>
		<div id="button">
			<input type="submit" name="login" value="Login">
		</div>
	</form>
	<form action="test.php" method="post">
		<div id="button">
			<input type="submit" name="alt_pass" value="Change password">
		</div>
	</form>

	<?php
}
else if(isset($_POST['alt_pass']))
{
	?>
	
	<div id="reg_menu">
	<h2 style="text-align: center;">Change Password</h2>
	<form action="test.php" method="post">
		<label id="s">Username: </label>
		<input type="text" name="username" required="required"><br><br>
		<label id="s">Current Password: </label>
		<input type="password" name="curr_password" id="curr_pass" required="required"><br><br>
		<label id="s">New Password: </label>
		<input type="password" name="password" id="pass" required="required"><br><br>
		<label id="s">Confirm New Password: </label>
		<input type="password" name="conf_pass" id="conf_pass" required="required"><br><br>
		<br>
		<div id="reg">
			<input type="submit" name="alt" value="Confirm" onclick="check_diff('pass'), check_pass('conf_pass')">
		</div>
	</form>

	<?php
}
else if(isset($_POST['register']))
{
	$name = trim($_POST['user']);

	$result = pg_query($db, "SELECT * FROM Users WHERE username = '$name'");
	$num_rows = pg_num_rows($result);

	if($num_rows > 0)
	{
		echo '<script>alert("Username '; echo($name); echo ' already exists.")</script>';
	}
	else
	{
		$pass = trim($_POST['pass']);
		$t = trim($_POST['type']);

		$res = pg_query($db, "INSERT INTO Users VALUES ('$name','$pass','$t')");

		if($res)
		{
			echo '<script>alert("User '; echo($name); echo ' successfully created!")</script>';
			echo '<script>window.location.replace("index.html");</script>';
		}
		else
		{
			echo '<script>alert("Error occurred in creating user.")</script>';
		}

	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['login']))
{
	$name = $_POST['username'];

	$result = pg_query($db, "SELECT * FROM Users WHERE username = '$name'");
	$num_rows = pg_num_rows($result);

	if($num_rows == 0)
	{
		echo '<script>alert("Username '; echo($name); echo ' does not exist.")</script>';

		echo '<script>go_back()</script>';
	}
	else
	{
		$row = pg_fetch_row($result);

		trim($pass = $_POST['password']);

		if($pass != trim($row[1]))
		{
			echo '<script>alert("Password does not match username.")</script>';

			echo '<script>go_back()</script>';
		}
		else
		{
			echo '<script>alert("Login successful!")</script>';

			if(trim($row[2]) == "staff")
				echo '<script>window.location.replace("staff.html");</script>';
			else
				echo '<script>window.location.replace("admin.html");</script>';
		}
	}

}
else if(isset($_POST['alt']))
{
	$name = $_POST['username'];

	$result = pg_query($db, "SELECT * FROM Users WHERE username = '$name'");
	$num_rows = pg_num_rows($result);

	if($num_rows == 0)
	{
		echo '<script>alert("Username '; echo($name); echo ' does not exist.")</script>';
	}
	else
	{
		$row = pg_fetch_row($result);
		$curr_pass = $_POST['curr_password'];

		if($curr_pass != trim($row[1]))
		{
			echo '<script>alert("Current password does not match username.")</script>';
		}
		else
		{
			$new_pass = $_POST['password'];

			$r = pg_query($db, "UPDATE Users SET password = '$new_pass' WHERE username = '$name'");

			echo '<script>alert("Password changed successfully for user '; echo($name); echo'!")</script>';
		}
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['logout']))
{
	echo '<script>window.location.replace("index.html");</script>';
}
else if(isset($_POST['see_employees']))
{
	$result = pg_query($db, "SELECT * FROM Empregado ORDER BY id_empregado");
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	
	echo '<h2>List of employees ('; print_r($num_rows); echo ' employees found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for name/title..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Employee ID</th>';
	echo '<th>Name</th>';
	echo '<th>Title</th>';
	echo '<th>Specialty Area</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['titulo']); echo'</td>';
		echo '<td>'; print_r($row[$i]['area_especializacao']); echo'</td>';

		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['see_schedule']))
{
	$result = pg_query($db, "SELECT Empregado.id_empregado, nome_empregado, titulo, dia_semana, hora_entrada, hora_saida
							FROM Empregado INNER JOIN Horario
							ON Empregado.id_empregado = Horario.id_empregado
							ORDER BY Empregado.id_empregado");
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of schedules ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,3)" placeholder="Search for name/day..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Employee ID</th>';
	echo '<th>Name</th>';
	echo '<th>Title</th>';
	echo '<th>Day of the Week</th>';
	echo '<th>Entry Time</th>';
	echo '<th>Exit Time</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['titulo']); echo'</td>';
		echo '<td>'; print_r($row[$i]['dia_semana']); echo'</td>';
		echo '<td>'; print_r($row[$i]['hora_entrada']); echo'</td>';
		echo '<td>'; print_r($row[$i]['hora_saida']); echo'</td>';

		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['see_allergies']))
{
	$result = pg_query($db, "SELECT * FROM Alergia ORDER BY id_alergia");
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of allergies registered ('; print_r($num_rows); echo ' allergies found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(0,1)" placeholder="Search..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Allergy ID</th>';
	echo '<th>Name</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_alergia']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_alergia']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['see_medicines']))
{
	$result = pg_query($db, "SELECT * FROM Medicamento ORDER BY id_medicamento");
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of medicine registered ('; print_r($num_rows); echo ' medicines found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(0,1)" placeholder="Search..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Medicine ID</th>';
	echo '<th>Name</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_medicamento']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_medicamento']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['see_emp_working']))
{
	?>

	<h2>Show employees working at a certain time</h2>
	<form action="test.php" method="post">
		<label id="s">Day of the week</label><br>
		<select id="week_day" name="week_day">
			<option value="Monday">Monday</option>
			<option value="Tuesday">Tuesday</option>
			<option value="Wednesday">Wednesday</option>
			<option value="Thursday">Thursday</option>
			<option value="Friday">Friday</option>
			<option value="Saturday">Saturday</option>
			<option value="Sunday">Sunday</option>
		</select>
		<br><br>
		<label id="s">Hours</label><br>
		<select id="hour" name="hour">
			<option value="00">00</option>
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
		</select>
		<br><br>
		<input type="submit" name="see_emp_work" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['show_clients']))
{
	$result = pg_query($db, "SELECT * FROM Cliente ORDER BY id_cliente");
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of clients ('; print_r($num_rows); echo ' clients found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for name/address..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Client ID</th>';
	echo '<th>Name</th>';
	echo '<th>Address</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['morada']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['show_clients_all']))
{
	$result = pg_query($db, "SELECT Cliente.id_cliente, nome_cliente, nome_alergia, grau_intensidade
							 FROM Cliente INNER JOIN ClienteAlergia
							 ON Cliente.id_cliente = ClienteAlergia.id_cliente
							 INNER JOIN Alergia
							 ON Alergia.id_alergia = ClienteAlergia.id_alergia
							 ORDER BY Cliente.id_cliente");
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of clients allergies ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for client/allergy name..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Client ID</th>';
	echo '<th>Client Name</th>';
	echo '<th>Allergy Name</th>';
	echo '<th>Intensity Degree</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_alergia']); echo'</td>';
		echo '<td>'; print_r($row[$i]['grau_intensidade']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['show_clients_med']))
{
	$result = pg_query($db, "SELECT Cliente.id_cliente, nome_cliente, nome_medicamento, frequencia_dosagem
							 FROM Cliente INNER JOIN ClienteMedicamento
							 ON Cliente.id_cliente = ClienteMedicamento.id_cliente
							 INNER JOIN Medicamento
							 ON Medicamento.id_medicamento = ClienteMedicamento.id_medicamento
						 	 ORDER BY id_cliente");
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of clients medicines ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for client/medicine name..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Client ID</th>';
	echo '<th>Client Name</th>';
	echo '<th>Medicine Name</th>';
	echo '<th>Dose Frequency</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_medicamento']); echo'</td>';
		echo '<td>'; print_r($row[$i]['frequencia_dosagem']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['show_clients_cons']))
{
	$result = pg_query($db, "SELECT Cliente.id_cliente, nome_cliente, Consulta.id_consulta, Empregado.id_empregado, nome_empregado, Empregado.titulo, razao_consulta, conclusao_medica
		FROM Cliente INNER JOIN Consulta
		ON Cliente.id_cliente = Consulta.id_cliente
		INNER JOIN ConsultaEmpregado
		ON Consulta.id_consulta = ConsultaEmpregado.id_consulta
		INNER JOIN Empregado
		ON Empregado.id_empregado = ConsultaEmpregado.id_empregado
		ORDER BY id_cliente, id_consulta
		");
	
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of clients consultations ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,4)" placeholder="Search for client/employee name..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Client ID</th>';
	echo '<th>Client Name</th>';
	echo '<th>Consultation ID</th>';
	echo '<th>Employee ID</th>';
	echo '<th>Employee Name</th>';
	echo '<th>Employee Title</th>';
	echo '<th>Reason for Consultation</th>';
	echo '<th>Medical Conclusion</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['id_consulta']); echo'</td>';
		echo '<td>'; print_r($row[$i]['id_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['titulo']); echo'</td>';
		echo '<td>'; print_r($row[$i]['razao_consulta']); echo'</td>';
		echo '<td>'; print_r($row[$i]['conclusao_medica']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['add_allergy']))
{
	?>

	<h2>Add new allergy</h2>
	<form action="test.php" method="post">
		<label id="s">Allergy name:</label><br>
		<input type="text" name="allergy_name" required="required"><br><br>
		<input type="submit" name="add_a" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['remove_allergy']))
{
	?>

	<h2>Remove allergy</h2>
	<p id="note">(At least one field is required)</p>
	<form action="test.php" method="post">
		<label id="s">Allergy ID:</label><br>
		<input type="number" id="allergy_id" name="allergy_id" pattern="[0-9]*"><br><br>
		<label id="s">Allergy Name:</label><br>
		<input type="text" id="allergy_name" name="allergy_name"><br><br>
		<input type="submit" name="remove_a" value="Submit" onclick="check_input('allergy_id','allergy_name')">
	</form>

	<?php

}
else if(isset($_POST['add_medicine']))
{
	?>

	<h2>Add new medicine</h2>
	<form action="test.php" method="post">
		<label id="s">Medicine name:</label><br>
		<input type="text" name="medicine_name" required="required"><br><br>
		<input type="submit" name="add_med" value="Submit">
	</form>

	<?php	
}
else if(isset($_POST['remove_medicine']))
{
	?>

	<h2>Remove medicine</h2>
	<p id="note">(At least one field is required)</p>
	<form action="test.php" method="post">
		<label id="s">Medicine ID:</label><br>
		<input type="number" id="medicine_id" name="medicine_id" pattern="[0-9]*"><br><br>
		<label id="s">Medicine Name:</label><br>
		<input type="text" id="medicine_name" name="medicine_name"><br><br>
		<input type="submit" name="remove_med" value="Submit" onclick="check_input('medicine_id', 'medicine_name')">
	</form>

	<?php
}
else if(isset($_POST['add_client']))
{
	?>

	<h2>Add new client</h2>
	<form action="test.php" method="post">
		<label id="s">Client Name:</label><br>
		<input type="text" id="client_name" name="client_name" required="required"><br><br>
		<label id="s">Client Address:</label><br>
		<input type="text" id="client_address" name="client_address" required="required"><br><br>
		<input type="submit" name="add_cli" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['remove_client']))
{
	?>

	<h2>Remove client</h2>
	<p id="note">(At least one field is required)</p>
	<form action="test.php" method="post">
		<label id="s">Client ID:</label><br>
		<input type="number" id="client_id" name="client_id" pattern="[0-9]*"><br><br>
		<label id="s">Client Name:</label><br>
		<input type="text" id="client_name" name="client_name"><br><br>
		<input type="submit" name="rem_cli" value="Submit" onclick="check_input('client_id', 'client_name')">
	</form>

	<?php
}
else if(isset($_POST['add_clients_all']))
{
	?>

	<h2>Add an allergy to a client</h2>
	<form action="test.php" method="post">
		<label id="s">Client ID:</label><br>
		<input type="number" id="client_id" name="client_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Allergy ID:</label><br>
		<input type="number" id="allergy_id" name="allergy_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Intensity Degree:</label><br>
		<select id="grau_int" name="grau_int">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>
		<br><br>
		<input type="submit" name="add_cli_all" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['remove_clients_all']))
{
	?>

	<h2>Remove allergy from a client</h2>
	<form action="test.php" method="post">
		<label id="s">Client ID:</label><br>
		<input type="number" id="client_id" name="client_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Allergy ID:</label><br>
		<input type="number" id="allergy_id" name="allergy_id" required="required" pattern="[0-9]*"><br><br>
		<input type="submit" name="rem_cli_all" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['add_clients_med']))
{
	?>

	<h2>Add an medicine to a client</h2>
	<form action="test.php" method="post">
		<label id="s">Client ID:</label><br>
		<input type="number" id="client_id" name="client_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Medicine ID:</label><br>
		<input type="number" id="medicine_id" name="medicine_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Dose Frequency:</label><br>
		<select id="dose_freq" name="dose_freq">
			<option value="1/day">1/day</option>
			<option value="1/week">1/week</option>
			<option value="2/day">2/day</option>
			<option value="2/week">2/week</option>
			<option value="3/day">3/day</option>
			<option value="3/week">3/week</option>
			<option value="4/week">4/week</option>
			<option value="5/week">5/week</option>
			<option value="1 after waking up, 1 before going to sleep">1 after waking up, 1 before going to sleep</option>
			<option value="1 before bed">1 before bed</option>
			<option value="1 in the morning">1 in the morning</option>
			<option value="1 after any meal">1 after any meal</option>
			<option value="2 after any meals (Different meals)">2 after any meals (Different meals)</option>
		</select>
		<br><br>
		<input type="submit" name="add_cli_med" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['remove_clients_med']))
{
	?>

	<h2>Remove medicine from a client</h2>
	<form action="test.php" method="post">
		<label id="s">Client ID:</label><br>
		<input type="number" id="client_id" name="client_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Medicine ID:</label><br>
		<input type="number" id="medicine_id" name="medicine_id" required="required" pattern="[0-9]*"><br><br>
		<input type="submit" name="rem_cli_med" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['add_cons']))
{
	?>

	<h2>Add a consultation</h2>
	<form action="test.php" method="post">
		<label id="s">Client ID:</label><br>
		<input type="number" id="client_id" name="client_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Employee ID (has to be a Doctor):</label><br>
		<input type="number" id="doctor_id" name="doctor_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Reason for consultation:</label><br>
		<select id="reason_cons" name="reason_cons">
			<option value="Head pain">Head pain</option>
			<option value="Chest pain">Chest pain</option>
			<option value="Abdomen pain">Abdomen pain</option>
			<option value="Back pain">Back pain</option>
			<option value="Excessive coughing">Excessive coughing</option>
			<option value="Sore throat">Sore throat</option>
			<option value="Chills">Chills</option>
			<option value="Fever">Fever</option>
			<option value="Dizzy">Dizzy</option>
			<option value="Nauseated">Nauseated</option>
			<option value="Shortness of breath">Shortness of breath</option>
			<option value="Feeling weak">Feeling weak</option>
			<option value="Ringing in ears">Ringing in ears</option>
			<option value="Losing hearing">Losing hearing</option>
			<option value="Blurred vision">Blurred vision</option>
			<option value="Double vision">Double vision</option>
			<option value="Trouble sleeping">Trouble sleeping</option>
			<option value="Trouble swallowing">Trouble swallowing</option>
			<option value="Lack of appetite">Lack of appetite</option>
			<option value="Skin rashes">Skin rashes</option>
		</select>
		<br><br>
		<label id="s">Medical conclusion:</label><br>
		<select id="med_conc" name="med_conc">
			<option value="Medicine prescribed">Medicine prescribed</option>
			<option value="Specialist should look at the situation">Specialist should look at the situation</option>
			<option value="Surgery needed">Surgery needed</option>
			<option value="Sleep more">Sleep more</option>
			<option value="Do more exercise">Do more exercise</option>
			<option value="Change eating habits">Change eating habits</option>
			<option value="Drink more water">Drink more water</option>
		</select>
		<br><br>
		<input type="submit" name="add_c" value="Submit">

	<?php
}
else if(isset($_POST['add_emp_to_cons']))
{
	?>

	<h2>Add employee to consultation</h2>
	<form action="test.php" method="post">
		<label id="s">Consultation ID:</label><br>
		<input type="number" id="cons_id" name="cons_id" required="required" pattern="[0-9]*"><br><br>
		<label id="s">Employee ID:</label><br>
		<input type="number" id="emp_id" name="emp_id" required="required" pattern="[0-9]*"><br><br>
		<input type="submit" name="add_emp_to_c" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['remove_cons']))
{
	?>

	<h2>Remove consultation</h2>
	<form action="test.php" method="post">
		<label id="s">Consultation ID:</label><br>
		<input type="number" id="cons_id" name="cons_id" required="required" pattern="[0-9]*"><br><br>
		<input type="submit" name="remove_c" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['add_employee']))
{
	?>

	<h2>Add new employee</h2>
	<form action="test.php" method="post">
		<label id="s">Employee Name:</label><br>
		<input type="text" id="employee_name" name="employee_name" required="required"><br><br>
		<label id="s">Title:</label><br>
		<select id="title" name="title" onchange="enableOp(this);" required="required">
		    <option value="Doctor">Doctor</option>
		    <option value="Nurse">Nurse</option>
		    <option value="Clinical assistant">Clinical assistant</option>
		    <option value="Patient services assistant">Patient services assistant</option>
		</select><br><br>
		<label id="s">Specialty Area:</label><br>
		<select id="spec_area" name="spec_area">
		    <option value="Anesthesiology">Anesthesiology</option>
		    <option value="Dermatology">Dermatology</option>
		    <option value="Endocrinology">Endocrinology</option>
		    <option value="General Medicine">General Medicine</option>
		    <option value="General Surgery">General Surgery</option>
		    <option value="Imaging">Imaging</option>
		    <option value="Internal Medicine">Internal Medicine</option>
		    <option value="Nefrology">Nefrology</option>
		    <option value="Neuropsychology">Neuropsychology</option>
		    <option value="Nutrition">Nutrition</option>
		    <option value="Orthopaedics">Orthopaedics</option>
		    <option value="Physical and Rehabilitation Medicine">Physical and Rehabilitation Medicine</option>
		    <option value="Proctology">Proctology</option>
		    <option value="Psychology">Psychology</option>
		    <option value="Rheumatology">Rheumatology</option>
		    <option value="Urology">Urology</option>
		    <option value="Cardiology">Cardiology</option>
		    <option value="General Practice">General Practice</option>
		    <option value="Gynaecology">Gynaecology</option>
		    <option value="Immunohemotherapy">Immunohemotherapy</option>
		    <option value="Maxillofacial Surgery">Maxillofacial Surgery</option>
		    <option value="Neurology">Neurology</option>
		    <option value="Neurosurgery">Neurosurgery</option>
		    <option value="Ophthalmology">Ophthalmology</option>
		    <option value="Otolaryngology">Otolaryngology</option>
		    <option value="Plastic and Reconstructive Surgery">Plastic and Reconstructive Surgery</option>
		    <option value="Psychiatry">Psychiatry</option>
		    <option value="Pulmonology">Pulmonology</option>
		    <option value="Speech Therapy">Speech Therapy</option>
		    <option value="Vascular Surgery">Vascular Surgery</option>
		</select>
		<br><br>
		<input type="submit" name="add_emp" value="Submit">
	</form>

	<?php
}
else if(isset($_POST['remove_employee']))
{
	?>

	<h2>Remove employee</h2>
	<p id="note">(At least one field is required)</p>
	<form action="test.php" method="post">
		<label id="s">Employee ID:</label><br>
		<input type="number" id="employee_id" name="employee_id" pattern="[0-9]*"><br><br>
		<label id="s">Employee Name:</label><br>
		<input type="text" id="employee_name" name="employee_name"><br><br>
		<input type="submit" name="remove_emp" value="Submit" onclick="check_input('employee_id', 'employee_name')">
	</form>

	<?php
}
else if(isset($_POST['change_emp_schedule']))
{
	?>

	<h2>Change employee's schedule</h2>
	<p id="note">(At least one field is required (ID or name) and at least one new hour)</p>
	<form action="test.php" method="post">
		<label id="s">Employee ID:</label><br>
		<input type="number" id="employee_id" name="employee_id" pattern="[0-9]*"><br><br>
		<label id="s">Employee Name:</label><br>
		<input type="text" id="employee_name" name="employee_name"><br><br>
		<label id="s">Day of the week</label><br>
		<select id="week_day" name="week_day">
			<option value="Monday">Monday</option>
			<option value="Tuesday">Tuesday</option>
			<option value="Wednesday">Wednesday</option>
			<option value="Thursday">Thursday</option>
			<option value="Friday">Friday</option>
			<option value="Saturday">Saturday</option>
			<option value="Sunday">Sunday</option>
		</select>
		<br><br>
		<label id="s">New Entry Hour:</label><br>
		<select id="entry_hour" name="entry_hour">
			<option value=""></option>
			<option value="00:00">00</option>
			<option value="01:00">01</option>
			<option value="02:00">02</option>
			<option value="03:00">03</option>
			<option value="04:00">04</option>
			<option value="05:00">05</option>
			<option value="06:00">06</option>
			<option value="07:00">07</option>
			<option value="08:00">08</option>
			<option value="09:00">09</option>
			<option value="10:00">10</option>
			<option value="11:00">11</option>
			<option value="12:00">12</option>
			<option value="13:00">13</option>
			<option value="14:00">14</option>
			<option value="15:00">15</option>
			<option value="16:00">16</option>
			<option value="17:00">17</option>
			<option value="18:00">18</option>
			<option value="19:00">19</option>
			<option value="20:00">20</option>
			<option value="21:00">21</option>
			<option value="22:00">22</option>
			<option value="23:00">23</option>
		</select>
		<br><br>
		<label id="s">New Exit Hour:</label><br>
		<select id="exit_hour" name="exit_hour">
			<option value=""></option>
			<option value="00:00">00</option>
			<option value="01:00">01</option>
			<option value="02:00">02</option>
			<option value="03:00">03</option>
			<option value="04:00">04</option>
			<option value="05:00">05</option>
			<option value="06:00">06</option>
			<option value="07:00">07</option>
			<option value="08:00">08</option>
			<option value="09:00">09</option>
			<option value="10:00">10</option>
			<option value="11:00">11</option>
			<option value="12:00">12</option>
			<option value="13:00">13</option>
			<option value="14:00">14</option>
			<option value="15:00">15</option>
			<option value="16:00">16</option>
			<option value="17:00">17</option>
			<option value="18:00">18</option>
			<option value="19:00">19</option>
			<option value="20:00">20</option>
			<option value="21:00">21</option>
			<option value="22:00">22</option>
			<option value="23:00">23</option>
		</select>
		<br><br>
		<input type="submit" name="change_emp_sch" value="Submit" onclick="check_input('employee_id', 'employee_name')">
	</form>

	<?php
}
else if(isset($_POST['change_emp_sch']))
{
	$id = $_POST['employee_id'];
	$name = $_POST['employee_name'];
	$week_day = $_POST['week_day'];
	$entry_hour = $_POST['entry_hour'];
	$exit_hour = $_POST['exit_hour'];

	if($name == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Empregado WHERE id_empregado = '$id'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$res_day = pg_query($db, "SELECT * FROM Horario WHERE id_empregado = '$id' AND dia_semana = '$week_day'");
			$num_res_day = pg_num_rows($res_day);

			if($num_res_day == 1)
			{
				if($entry_hour == NULL && $exit_hour == NULL)
				{
					echo '<script>alert("One of the hours needs to be selected to make any changes.")</script>';
				}
				else if($exit_hour == NULL)
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_entrada = '$entry_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");

					sch_result($result);
				}
				else if($entry_hour == NULL)
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_saida = '$exit_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");
				
					sch_result($result);
				}
				else
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_entrada = '$entry_hour', hora_saida = '$exit_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");
				
					sch_result($result);
				}
			}
			else
			{
				if($entry_hour == NULL && $exit_hour == NULL)
				{
					echo '<script>alert("One of the hours needs to be selected to make any changes.")</script>';
				}
				else if($exit_hour == NULL)
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day','$entry_hour',NULL)");

					sch_result($result);
				}
				else if($entry_hour == NULL)
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day',NULL,'$exit_hour')");
				
					sch_result($result);
				}
				else
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day','$entry_hour','$exit_hour')");
				
					sch_result($result);
				}
			}
		}
		else
			echo '<script>alert("ID '; echo($id); echo' does not exist.");</script>';

	}
	else if($id == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Empregado WHERE nome_empregado = '$name'");
		$r = pg_fetch_row($res);
		$num_res = pg_num_rows($res);

		if($num_res > 1)
		{
			echo '</script>alert("There is more than one record with name '; echo($name); echo '.")</script>';
		}
		else if($num_res == 1)
		{
			$id = $r[0];

			$res_day = pg_query($db, "SELECT * FROM Horario WHERE id_empregado = '$id' AND dia_semana = '$week_day'");
			$num_res_day = pg_num_rows($res_day);

			if($num_res_day == 1)
			{
				if($entry_hour == NULL && $exit_hour == NULL)
				{
					echo '<script>alert("One of the hours needs to be selected to make any changes.")</script>';
				}
				else if($exit_hour == NULL)
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_entrada = '$entry_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");

					sch_result($result);
				}
				else if($entry_hour == NULL)
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_saida = '$exit_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");

					sch_result($result);
				}
				else
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_entrada = '$entry_hour', hora_saida = '$exit_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");

					sch_result($result);
				}
			}
			else
			{
				if($entry_hour == NULL && $exit_hour == NULL)
				{
					echo '<script>alert("One of the hours needs to be selected to make any changes.")</script>';
				}
				else if($exit_hour == NULL)
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day','$entry_hour',NULL)");

					sch_result($result);
				}
				else if($entry_hour == NULL)
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day',NULL,'$exit_hour')");
				
					sch_result($result);
				}
				else
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day','$entry_hour','$exit_hour')");
				
					sch_result($result);
				}
			}
		}
		else
			echo '<script>alert("There are no records with name '; echo($name); echo '.");</script>';
	
	}
	else
	{
		$res = pg_query($db, "SELECT * FROM Empregado WHERE id_empregado = '$id' AND nome_empregado = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$res_day = pg_query($db, "SELECT * FROM Horario WHERE id_empregado = '$id' AND dia_semana = '$week_day'");
			$num_res_day = pg_num_rows($res_day);

			if($num_res_day == 1)
			{
				if($entry_hour == NULL && $exit_hour == NULL)
				{
					echo '<script>alert("One of the hours needs to be selected to make any changes.")</script>';
				}
				else if($exit_hour == NULL)
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_entrada = '$entry_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");

					sch_result($result);
				}
				else if($entry_hour == NULL)
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_saida = '$exit_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");

					sch_result($result);
				}
				else
				{
					$result = pg_query($db, "UPDATE Horario
											 SET hora_entrada = '$entry_hour', hora_saida = '$exit_hour'
											 WHERE id_empregado = '$id'
											 AND dia_semana = '$week_day'");

					sch_result($result);
				}
			}
			else
			{
				if($entry_hour == NULL && $exit_hour == NULL)
				{
					echo '<script>alert("One of the hours needs to be selected to make any changes.")</script>';
				}
				else if($exit_hour == NULL)
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day','$entry_hour',NULL)");

					sch_result($result);
				}
				else if($entry_hour == NULL)
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day',NULL,'$exit_hour')");
				
					sch_result($result);
				}
				else
				{
					$result = pg_query($db, "INSERT INTO Horario VALUES ('$id','$week_day','$entry_hour','$exit_hour')");
				
					sch_result($result);
				}
			}
		}
		else
		{
			echo '<script>alert("Either\nID '; echo($id); echo' does not exist\nOR\nID ';
			echo ($id); echo ' does not correspond to name '; echo($name); echo '.");</script>';
		}
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['see_emp_work']))
{
	$week = $_POST['week_day'];
	$hour = $_POST['hour'];

	$str = "SELECT Empregado.id_empregado, nome_empregado, titulo, area_especializacao, hora_entrada, hora_saida
			FROM Empregado INNER JOIN Horario
			ON Empregado.id_empregado = Horario.id_empregado";

	$str = $str." WHERE dia_semana = '$week'";

	if($hour == '22' || $hour == '23' || $hour == '00' || $hour == '01') {
		$str = $str." AND hora_entrada >= '18:00'";
	} else if($hour == '02') {
		$str = $str." AND hora_entrada >= '18:00' AND hora_saida > '02:00'";
	} else if($hour == '03') {
		$str = $str." AND hora_entrada >= '18:00' AND hora_saida > '03:00'";
	} else if($hour == '04') {
		$str = $str." AND hora_entrada >= '18:00' AND hora_saida > '04:00'";
	} else if($hour == '05') {
		$str = $str." AND hora_entrada >= '18:00' AND hora_saida > '05:00'";
	} else if($hour == '06') {
		$str = $str." AND hora_entrada <= '06:00'";
	} else if($hour == '07') {
		$str = $str." AND hora_entrada <= '07:00'";
	} else if($hour == '08') {
		$str = $str." AND hora_entrada <= '08:00'";
	} else if($hour == '09') {
		$str = $str." AND hora_entrada <= '09:00'";
	} else if($hour == '10' || $hour == '11' || $hour == '12' || $hour == '13') {
		$str = $str." AND hora_entrada <= '10:00'";
	} else if($hour == '14') {
		$str = $str." AND hora_entrada <= '10:00' AND hora_saida > '14:00'";
	} else if($hour == '15') {
		$str = $str." AND hora_entrada <= '10:00' AND hora_saida > '15:00'";
	} else if($hour == '16') {
		$str = $str." AND hora_entrada <= '10:00' AND hora_saida > '16:00'";
	} else if($hour == '17') {
		$str = $str." AND hora_entrada <= '10:00' AND hora_saida > '17:00'";
	} else if($hour == '18') {
		$str = $str." AND hora_entrada <= '18:00' AND hora_saida <= '06:00'";
	} else if($hour == '19') {
		$str = $str." AND hora_entrada <= '19:00' AND hora_saida <= '06:00'";
	} else if($hour == '20') {
		$str = $str." AND hora_entrada <= '20:00' AND hora_saida <= '06:00'";
	} else if($hour == '21') {
		$str = $str." AND hora_entrada <= '21:00' AND hora_saida <= '06:00'";
	}

	$str_final = $str." ORDER BY Empregado.id_empregado";
	
	$result = pg_query($db, $str_final);
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of employees working on '; echo($week); echo ' at '; echo($hour); echo 'h ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for name/title..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Employee ID</th>';
	echo '<th>Name</th>';
	echo '<th>Title</th>';
	echo '<th>Specialty Area</th>';
	echo '<th>Entry Time</th>';
	echo '<th>Exit Time</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';

		echo '<td>'; print_r($row[$i]['id_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['titulo']); echo'</td>';
		echo '<td>'; print_r($row[$i]['area_especializacao']); echo'</td>';
		echo '<td>'; print_r($row[$i]['hora_entrada']); echo'</td>';
		echo '<td>'; print_r($row[$i]['hora_saida']); echo'</td>';

		echo '</tr>';

		$i++;
	}

	echo '</table>';

}
else if(isset($_POST['see_high_all']))
{
	$result = pg_query($db, "SELECT Alergia.id_alergia, nome_alergia, COUNT(id_cliente) AS num_clientes
							 FROM Alergia INNER JOIN ClienteAlergia
							 ON Alergia.id_alergia = ClienteAlergia.id_alergia
							 GROUP BY Alergia.id_alergia
							 ORDER BY num_clientes DESC");
	
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of most gotten allergies ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for name/number..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Allergy ID</th>';
	echo '<th>Allergy Name</th>';
	echo '<th>Number of clients that have it</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_alergia']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_alergia']); echo'</td>';
		echo '<td>'; print_r($row[$i]['num_clientes']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['see_high_med']))
{
	$result = pg_query($db, "SELECT Medicamento.id_medicamento, nome_medicamento, COUNT(id_cliente) AS num_clientes
							 FROM Medicamento INNER JOIN ClienteMedicamento
							 ON Medicamento.id_medicamento = ClienteMedicamento.id_medicamento
							 GROUP BY Medicamento.id_medicamento
							 ORDER BY num_clientes DESC");
	
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of most used medicines ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for name/number..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Medicine ID</th>';
	echo '<th>Medicine Name</th>';
	echo '<th>Number of clients that use it</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_medicamento']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_medicamento']); echo'</td>';
		echo '<td>'; print_r($row[$i]['num_clientes']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['see_cli_high_cons']))
{
	$result = pg_query($db, "SELECT Cliente.id_cliente, nome_cliente, COUNT(id_consulta) AS num_consultas
							 FROM Cliente INNER JOIN Consulta
							 ON Cliente.id_cliente = Consulta.id_cliente
							 GROUP BY Cliente.id_cliente
							 ORDER BY num_consultas DESC");
	
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of clients with most consultations ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for name/number..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Client ID</th>';
	echo '<th>Client Name</th>';
	echo '<th>Number of Consultations</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_cliente']); echo'</td>';
		echo '<td>'; print_r($row[$i]['num_consultas']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['see_emp_high_cons']))
{
	$result = pg_query($db, "SELECT Empregado.id_empregado, nome_empregado, Empregado.titulo, COUNT(id_consulta) AS num_consultas
							 FROM Empregado INNER JOIN ConsultaEmpregado
							 ON Empregado.id_empregado = ConsultaEmpregado.id_empregado
							 GROUP BY Empregado.id_empregado
							 ORDER BY num_consultas DESC");
	
	$row = pg_fetch_all($result);

	$i = 0;
	$num_rows = pg_num_rows($result);

	echo '<h2>List of employees with most consultations ('; print_r($num_rows); echo ' records found)</h2>';
	echo '<input type="text" id="s_name" onkeyup="filterTable(1,2)" placeholder="Search for name/title..."></input>';

	echo '<table id="tab">';
	echo '<tr>';
	echo '<th>Employee ID</th>';
	echo '<th>Employee Name</th>';
	echo '<th>Title</th>';
	echo '<th>Number of Consultations</th>';
	echo '</tr>';

	while($i < $num_rows)
	{
		echo '<tr>';
		echo '<td>'; print_r($row[$i]['id_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['nome_empregado']); echo'</td>';
		echo '<td>'; print_r($row[$i]['titulo']); echo'</td>';
		echo '<td>'; print_r($row[$i]['num_consultas']); echo'</td>';
		echo '</tr>';

		$i++;
	}

	echo '</table>';
}
else if(isset($_POST['add_a']))
{
	$a = $_POST['allergy_name'];

	$ch = pg_query($db, "SELECT * FROM Alergia WHERE nome_alergia = '$a'");
	$r = pg_num_rows($ch);

	if($r > 0)
	{
		echo '<script>alert("Allergy with name '; echo($a); echo ' already exists.");</script>';
	}
	else
	{
		$res = pg_query($db, "SELECT id_alergia FROM Alergia ORDER BY id_alergia DESC LIMIT 1");
		$row = pg_fetch_row($res);

		$id = $row[0];
		$id++;

		$result = pg_query($db, "INSERT INTO Alergia VALUES ('$id','$a')");

		if($result){
			echo '<script>alert("Data inserted successfully with ID '; echo($id); echo'!");</script>';
		} else {
			echo '<script>alert_ins_fail()</script>';
		}
	}		

	echo '<script>go_back()</script>';
}
else if(isset($_POST['remove_a']))
{
	$id = $_POST['allergy_id'];
	$name = $_POST['allergy_name'];

	if($name == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Alergia WHERE id_alergia = '$id'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Alergia WHERE id_alergia = '$id'");

			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("ID '; echo($id); echo' does not exist.");</script>';

	}
	else if($id == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Alergia WHERE nome_alergia = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res >= 1)
		{
			$result = pg_query($db, "DELETE FROM Alergia WHERE nome_alergia = '$name'");

			if($result){
				echo '<script>alert("'; echo($num_res); echo' record(s) with name '; echo($name);

				if($num_res == 1)
					echo ' was ';
				else
					echo ' were ';

				echo 'removed successfully!");</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("There are no records with name '; echo($name); echo '.");</script>';	
	
	}
	else
	{
		$res = pg_query($db, "SELECT * FROM Alergia WHERE id_alergia = '$id' AND nome_alergia = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Alergia WHERE id_alergia = '$id' AND nome_alergia = '$name'");
		
			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
		{
			echo '<script>alert("Either\nID '; echo($id); echo' does not exist\nOR\nID ';
			echo ($id); echo ' does not correspond to name '; echo($name); echo '.");</script>';
		}
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['add_med']))
{
	$m = $_POST['medicine_name'];

	$ch = pg_query($db, "SELECT * FROM Medicamento WHERE nome_medicamento = '$m'");
	$r = pg_num_rows($ch);

	if($r > 0)
	{
		echo '<script>alert("Medicine with name '; echo($m); echo ' already exists.");</script>';
	}
	else
	{
		$res = pg_query($db, "SELECT id_medicamento FROM Medicamento ORDER BY id_medicamento DESC LIMIT 1");
		$row = pg_fetch_row($res);

		$id = $row[0];
		$id++;

		$result = pg_query($db, "INSERT INTO Medicamento VALUES ('$id','$m')");

		if($result){
			echo '<script>alert("Data inserted successfully with ID '; echo($id); echo'!");</script>';
		} else {
			echo '<script>alert_ins_fail()</script>';
		}
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['remove_med']))
{
	$id = $_POST['medicine_id'];
	$name = $_POST['medicine_name'];

	if($name == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Medicamento WHERE id_medicamento = '$id'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Medicamento WHERE id_medicamento = '$id'");

			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("ID '; echo($id); echo' does not exist.");</script>';

	}
	else if($id == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Medicamento WHERE nome_medicamento = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res >= 1)
		{
			$result = pg_query($db, "DELETE FROM Medicamento WHERE nome_medicamento = '$name'");

			if($result){
				echo '<script>alert("'; echo($num_res); echo' record(s) with name '; echo($name);

				if($num_res == 1)
					echo ' was ';
				else
					echo ' were ';

				echo 'removed successfully!");</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("There are no records with name '; echo($name); echo '.");</script>';	
	
	}
	else
	{
		$res = pg_query($db, "SELECT * FROM Medicamento WHERE id_medicamento = '$id' AND nome_medicamento = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Medicamento WHERE id_medicamento = '$id' AND nome_medicamento = '$name'");
		
			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
		{
			echo '<script>alert("Either\nID '; echo($id); echo' does not exist\nOR\nID ';
			echo ($id); echo ' does not correspond to name '; echo($name); echo '.");</script>';
		}
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['add_cli']))
{
	$name = $_POST['client_name'];
	$address = $_POST['client_address'];

	$res = pg_query($db, "SELECT id_cliente FROM Cliente ORDER BY id_cliente DESC LIMIT 1");
	$row = pg_fetch_row($res);

	$id = $row[0];
	$id++;

	$result = pg_query($db, "INSERT INTO Cliente VALUES ('$id','$name','$address')");

	if($result){
		echo '<script>alert("Data inserted successfully with ID '; echo($id); echo'!");</script>';
	} else {
		echo '<script>alert_ins_fail()</script>';
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['rem_cli']))
{
	$id = $_POST['client_id'];
	$name = $_POST['client_name'];

	if($name == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Cliente WHERE id_cliente = '$id'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Cliente WHERE id_cliente = '$id'");

			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("ID '; echo($id); echo' does not exist.");</script>';

	}
	else if($id == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Cliente WHERE nome_cliente = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res >= 1)
		{
			$result = pg_query($db, "DELETE FROM Cliente WHERE nome_cliente = '$name'");

			if($result){
				echo '<script>alert("'; echo($num_res); echo' record(s) with name '; echo($name);

				if($num_res == 1)
					echo ' was ';
				else
					echo ' were ';

				echo 'removed successfully!");</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("There are no records with name '; echo($name); echo '.");</script>';	
	
	}
	else
	{
		$res = pg_query($db, "SELECT * FROM Cliente WHERE id_cliente = '$id' AND nome_cliente = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Cliente WHERE id_cliente = '$id' AND nome_cliente = '$name'");
		
			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
		{
			echo '<script>alert("Either\nID '; echo($id); echo' does not exist\nOR\nID ';
			echo ($id); echo ' does not correspond to name '; echo($name); echo '.");</script>';
		}
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['add_cli_all']))
{
	$cli_id = $_POST['client_id'];
	$all_id = $_POST['allergy_id'];
	$grau = $_POST['grau_int'];

	$result = pg_query($db, "SELECT * FROM Cliente WHERE id_cliente = '$cli_id'");
	$row = pg_num_rows($result);

	if($row == 1)
	{
		$res = pg_query($db, "SELECT * FROM Alergia WHERE id_alergia = '$all_id'");
		$row_a = pg_num_rows($res);

		if($row_a == 1)
		{
			$re = pg_query($db, "SELECT * FROM ClienteAlergia WHERE id_cliente = '$cli_id' AND id_alergia = '$all_id'");
			$row_have = pg_num_rows($re);

			if($row_have == 0)
			{
				$r = pg_query($db, "INSERT INTO ClienteAlergia VALUES ('$cli_id','$all_id','$grau')");

				echo '<script>alert("Allergy ID '; echo($all_id); echo ' was successfully added to Client ID '; echo($cli_id); echo'!");</script>';
			}
			else
				echo '<script>alert("Client ID '; echo($cli_id); echo ' already has Allergy ID '; echo($all_id); echo'.");</script>';
		}
		else
			echo '<script>alert("Allergy ID '; echo($all_id); echo' does not exist.");</script>';
	}
	else
		echo '<script>alert("Client ID '; echo($cli_id); echo' does not exist.");</script>';

	echo '<script>go_back()</script>';
}
else if(isset($_POST['rem_cli_all']))
{
	$cli_id = $_POST['client_id'];
	$all_id = $_POST['allergy_id'];

	$result = pg_query($db, "SELECT * FROM Cliente WHERE id_cliente = '$cli_id'");
	$row = pg_num_rows($result);

	if($row == 1)
	{
		$res = pg_query($db, "SELECT * FROM Alergia WHERE id_alergia = '$all_id'");
		$row_a = pg_num_rows($res);

		if($row_a == 1)
		{
			$re = pg_query($db, "SELECT * FROM ClienteAlergia WHERE id_cliente = '$cli_id' AND id_alergia = '$all_id'");
			$row_have = pg_num_rows($re);

			if($row_have == 1)
			{	
				$r = pg_query($db, "DELETE FROM ClienteAlergia WHERE id_cliente = '$cli_id' AND id_alergia = '$all_id'");

				echo '<script>alert("Allergy ID '; echo($all_id); echo ' was successfully removed from Client ID '; echo($cli_id); echo'!");</script>';
			}
			else
				echo '<script>alert("Client ID '; echo($cli_id); echo ' does not have Allergy ID '; echo($all_id); echo'.");</script>';
		}
		else
			echo '<script>alert("Allergy ID '; echo($all_id); echo' does not exist.");</script>';
	}
	else
		echo '<script>alert("Client ID '; echo($cli_id); echo' does not exist.");</script>';

	echo '<script>go_back()</script>';
}
else if(isset($_POST['add_cli_med']))
{
	$cli_id = $_POST['client_id'];
	$med_id = $_POST['medicine_id'];
	$dose_freq = $_POST['dose_freq'];

	$result = pg_query($db, "SELECT * FROM Cliente WHERE id_cliente = '$cli_id'");
	$row = pg_num_rows($result);

	if($row == 1)
	{
		$res = pg_query($db, "SELECT * FROM Medicamento WHERE id_medicamento = '$med_id'");
		$row_a = pg_num_rows($res);

		if($row_a == 1)
		{
			$re = pg_query($db, "SELECT * FROM ClienteMedicamento WHERE id_cliente = '$cli_id' AND id_medicamento = '$med_id'");
			$row_have = pg_num_rows($re);

			if($row_have == 0)
			{
				$r = pg_query($db, "INSERT INTO ClienteMedicamento VALUES ('$cli_id','$med_id','$dose_freq')");

				echo '<script>alert("Medicine ID '; echo($med_id); echo ' was successfully added to Client ID '; echo($cli_id); echo'!");</script>';
			}
			else
				echo '<script>alert("Client ID '; echo($cli_id); echo ' already uses Medicine ID '; echo($med_id); echo'.");</script>';
		}
		else
			echo '<script>alert("Medicine ID '; echo($med_id); echo' does not exist.");</script>';
	}
	else
		echo '<script>alert("Client ID '; echo($cli_id); echo' does not exist.");</script>';

	echo '<script>go_back()</script>';
}
else if(isset($_POST['rem_cli_med']))
{
	$cli_id = $_POST['client_id'];
	$med_id = $_POST['medicine_id'];

	$result = pg_query($db, "SELECT * FROM Cliente WHERE id_cliente = '$cli_id'");
	$row = pg_num_rows($result);

	if($row == 1)
	{
		$res = pg_query($db, "SELECT * FROM Medicamento WHERE id_medicamento = '$med_id'");
		$row_a = pg_num_rows($res);

		if($row_a == 1)
		{
			$re = pg_query($db, "SELECT * FROM ClienteMedicamento WHERE id_cliente = '$cli_id' AND id_medicamento = '$med_id'");
			$row_have = pg_num_rows($re);

			if($row_have == 1)
			{
				$r = pg_query($db, "DELETE FROM ClienteMedicamento WHERE id_cliente = '$cli_id' AND id_medicamento = '$med_id'");

				echo '<script>alert("Medicine ID '; echo($med_id); echo ' was successfully removed from Client ID '; echo($cli_id); echo'!");</script>';
			}
			else
				echo '<script>alert("Client ID '; echo($cli_id); echo ' does not use Medicine ID '; echo($med_id); echo'.");</script>';
		}
		else
			echo '<script>alert("Medicine ID '; echo($med_id); echo' does not exist.");</script>';
	}
	else
		echo '<script>alert("Client ID '; echo($cli_id); echo' does not exist.");</script>';

	echo '<script>go_back()</script>';
}
else if(isset($_POST['add_c']))
{
	$cli_id = $_POST['client_id'];

	$result = pg_query($db, "SELECT * FROM Cliente WHERE id_cliente = '$cli_id'");
	$number = pg_num_rows($result);

	if($number == 1)
	{
		$doc_id = $_POST['doctor_id'];

		$res = pg_query($db, "SELECT * FROM Empregado WHERE id_empregado = '$doc_id'");
		$num = pg_num_rows($res);

		if($num == 1)
		{
			$row = pg_fetch_row($res);
			$title = trim($row[2]);

			if($title == "Doctor")
			{
				$reason = $_POST['reason_cons'];
				$med_conc = $_POST['med_conc'];

				$re = pg_query($db, "SELECT id_consulta FROM Consulta ORDER BY id_consulta DESC LIMIT 1");
				$ro = pg_fetch_row($re);

				$id_cons = $ro[0];
				$id_cons++;

				$result1 = pg_query($db, "INSERT INTO Consulta VALUES ('$id_cons','$cli_id','$reason','$med_conc')");

				if($result1)
				{
					$result2 = pg_query($db, "INSERT INTO ConsultaEmpregado VALUES ('$id_cons','$doc_id','$title')");

					if($result2)
					{
						echo '<script>alert("Consultation inserted successfully with ID '; echo($id_cons); echo'!");</script>';
					}
					else
					{
						echo'<script>alert("Error in attaching doctor to consultation.")</script>';
					}
				}
				else
				{
					echo '<script>alert("Error in inserting consultation.");</script>';
				}
			}
			else
				echo '<script>alert("Employee ID '; echo($doc_id); echo ' is not a doctor.");</script>';
		}
		else
			echo '<script>alert("Employee ID '; echo($doc_id); echo' does not exist.");</script>';
	}
	else
		echo '<script>alert("Client ID '; echo($cli_id); echo' does not exist.");</script>';

	echo '<script>go_back()</script>';
}
else if(isset($_POST['add_emp_to_c']))
{
	$cons_id = $_POST['cons_id'];

	$result = pg_query($db, "SELECT * FROM Consulta WHERE id_consulta = '$cons_id'");
	$number = pg_num_rows($result);

	if($number == 1)
	{
		$emp_id = $_POST['emp_id'];

		$res = pg_query($db, "SELECT * FROM Empregado WHERE id_empregado = '$emp_id'");
		$num = pg_num_rows($res);

		if($num == 1)
		{
			$resu = pg_query($db, "SELECT * FROM ConsultaEmpregado WHERE id_consulta = '$cons_id' AND id_empregado = '$emp_id'");
			$numb = pg_num_rows($resu);

			if($numb == 0)
			{
				$re = pg_query($db, "SELECT * FROM Empregado WHERE id_empregado = '$emp_id'");
				$nu = pg_fetch_row($re);

				$title = trim($nu[2]);

				$r = pg_query($db, "INSERT INTO ConsultaEmpregado VALUES ('$cons_id','$emp_id','$title')");

				if($r)
				{
					echo '<script>alert("Employee ID '; echo($emp_id); echo' added successfully to Consultation ID '; echo($cons_id); 
					echo '!");</script>';
				}
				else
				{
					echo '<script>alert("Error in adding employee to consultation.");</script>';
				}
			}
			else
			{
				echo '<script>alert("Employee ID '; echo($emp_id); echo' is already on Consultation ID '; echo($cons_id); 
				echo '.");</script>';	
			}
		}
		else
			echo '<script>alert("Employee ID '; echo($emp_id); echo' does not exist.");</script>';
	}
	else
		echo '<script>alert("Consultation ID '; echo($cons_id); echo' does not exist.");</script>';

	echo '<script>go_back()</script>';
}
else if(isset($_POST['remove_c']))
{
	$cons_id = $_POST['cons_id'];

	$res = pg_query($db, "SELECT * FROM Consulta WHERE id_consulta = '$cons_id'");
	$num = pg_num_rows($res);

	if($num == 1)
	{
		$r = pg_query($db, "DELETE FROM ConsultaEmpregado WHERE id_consulta = '$cons_id'");

		if($r)
		{
			$rr = pg_query($db, "DELETE FROM Consulta WHERE id_consulta = '$cons_id'");

			if($rr)
			{
				echo '<script>alert("Consultation ID '; echo($cons_id); echo ' removed successfully!");</script>';
			}
			else
				echo '<script>alert("Error in removing consultation.");</script>';
		}
		else
			echo '<script>alert("Error in removing employees from consultation.");</script>';
	}
	else
		echo '<script>alert("Consultation ID '; echo($cons_id); echo' does not exist.");</script>';

	echo '<script>go_back()</script>';
}
else if(isset($_POST['add_emp']))
{
	$name = $_POST['employee_name'];
	$title = $_POST['title'];

	$res = pg_query($db, "SELECT id_empregado FROM Empregado ORDER BY id_empregado DESC LIMIT 1");
	$row = pg_fetch_row($res);

	$id = $row[0];
	$id++;

	if($title == "Doctor") {
		$spec = $_POST['spec_area'];
		$result = pg_query($db, "INSERT INTO Empregado VALUES ('$id','$name','$title','$spec')");
	}
	else {
		$result = pg_query($db, "INSERT INTO Empregado VALUES ('$id','$name','$title')");
	}

	if($result){
		echo '<script>alert("Data inserted successfully with ID '; echo($id); echo'!");</script>';
	} else {
		echo '<script>alert_ins_fail()</script>';
	}

	echo '<script>go_back()</script>';
}
else if(isset($_POST['remove_emp']))
{
	$id = $_POST['employee_id'];
	$name = $_POST['employee_name'];

	if($name == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Empregado WHERE id_empregado = '$id'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Empregado WHERE id_empregado = '$id'");

			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("ID '; echo($id); echo' does not exist.");</script>';

	}
	else if($id == NULL)
	{
		$res = pg_query($db, "SELECT * FROM Empregado WHERE nome_empregado = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res >= 1)
		{
			$result = pg_query($db, "DELETE FROM Empregado WHERE nome_empregado = '$name'");

			if($result){
				echo '<script>alert("'; echo($num_res); echo' record(s) with name '; echo($name);

				if($num_res == 1)
					echo ' was ';
				else
					echo ' were ';

				echo 'removed successfully!");</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
			echo '<script>alert("There are no records with name '; echo($name); echo '.");</script>';	
	
	}
	else
	{
		$res = pg_query($db, "SELECT * FROM Empregado WHERE id_empregado = '$id' AND nome_empregado = '$name'");
		$num_res = pg_num_rows($res);

		if($num_res == 1)
		{
			$result = pg_query($db, "DELETE FROM Empregado WHERE id_empregado = '$id' AND nome_empregado = '$name'");
		
			if($result){
				echo '<script>alert_rem_success()</script>';
			} else {
				echo '<script>alert_rem_fail()</script>';
			}
		}
		else
		{
			echo '<script>alert("Either\nID '; echo($id); echo' does not exist\nOR\nID ';
			echo ($id); echo ' does not correspond to name '; echo($name); echo '.");</script>';
		}
	}

	echo '<script>go_back()</script>';
}
?>

</body>
</html>
