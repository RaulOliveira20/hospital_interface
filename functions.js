function go_back()
{
	window.history.back();
}

function go_forward() 
{
 	window.history.forward();
}

function check_pass(id)
{
	input = document.getElementById(id);

	if(document.getElementById('pass').value != document.getElementById('conf_pass').value)
		input.setCustomValidity('Both passwords have to match.');
	else
		input.setCustomValidity('');
}

function check_diff(id)
{
	input = document.getElementById(id);

	if(document.getElementById('pass').value == document.getElementById('curr_pass').value)
		input.setCustomValidity('New password has to be different from current one.');
	else
		input.setCustomValidity('');
}

function check_input(id1, id2)
{
	input = document.getElementById(id1);

	if(document.getElementById(id1).value == "" && document.getElementById(id2).value == "")
		input.setCustomValidity('Both inputs cannot be empty.');
	else
		input.setCustomValidity('');
}

function enableOp(x) {

    var title = x.options[x.selectedIndex].text;

    if(title == "Doctor") {
        document.getElementById("spec_area").disabled = false;
    } else {
        document.getElementById("spec_area").disabled = true;
    }
}

function alert_ins_success()
{
	alert("Data inserted successfully!");
}

function alert_ins_fail()
{
	alert("Error occurred in inserting data.");
}

function alert_rem_success()
{
	alert("Data removed successfully!");
}

function alert_rem_fail()
{
	alert("Error occurred in removing data.");
}

function filterTable(j, k) {
	var input, filter, table, tr, td1, td2, i, txtValue1, txtValue2;
	input = document.getElementById("s_name");
	filter = input.value.toUpperCase();
	table = document.getElementById("tab");
	tr = table.getElementsByTagName("tr");
	
	for (i = 0; i < tr.length; i++) 
	{
		td1 = tr[i].getElementsByTagName("td")[j];
		td2 = tr[i].getElementsByTagName("td")[k];

		if(td1 || td2) {
			txtValue1 = td1.textContent || td1.innerText;
			txtValue2 = td2.textContent || td2.innerText;

			if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		}
	}
}