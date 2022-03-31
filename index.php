<?php
$connect = new PDO("mysql:host=localhost;dbname=send-multiple-email", "root", "");
$query = "SELECT * FROM users ORDER BY ID";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
	<title>Send Multiple Email Using PHP and AJAX</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<style>
		.btn-light {
			width: 60% !important;
			border: 0;
			border-radius: 0;
		}
		.btn-light:hover {
			background-color: #f0e97c;
		}
	</style>
</head>

<body>
	<br />
	<div class="container">

		<div class="table-responsive">
			<table class="table table-bordered table-dark table-hover">
				<tr class="thead-light">
					<th>User Name</th>
					<th>Email</th>
					<th>Select</th>
					<th>Action</th>
				</tr>
				<?php
				$count = 0;
				foreach ($result as $row) {
					$count = $count + 1;
					echo '
					<tr>
						<td>' . $row["user_name"] . '</td>
						<td>' . $row["user_email"] . '</td>
						<td>
							<input type="checkbox" name="single_select" class="single_select" data-email="' . $row["user_email"] . '" data-name="' . $row["user_name"] . '" />
						</td>
						<td>
						<button type="button" name="email_button" class="btn btn-light shadow-none email_button" id="' . $count . '" data-email="' . $row["user_email"] . '" data-name="' . $row["user_name"] . '" data-action="single">Send Single</button>
						</td>
					</tr>
					';
				}
				?>
				<tr>
					<td colspan="3"></td>
					<td><button type="button" name="bulk_email" class="btn btn-light shadow-none  email_button" id="bulk_email" data-action="bulk">Send Bulk...</button></td>
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>

</html>

<script>
	$(document).ready(function() {
		$('.email_button').click(function() {
			$(this).attr('disabled', 'disabled');
			var id = $(this).attr("id");
			var action = $(this).data("action");
			var email_data = [];
			if (action == 'single') {
				email_data.push({
					email: $(this).data("email"),
					name: $(this).data("name")
				});
			} else {
				$('.single_select').each(function() {
					if ($(this).prop("checked") == true) {
						email_data.push({
							email: $(this).data("email"),
							name: $(this).data('name')
						});
					}
				});
			}
			$.ajax({
				url: "send_mail.php",
				method: "POST",
				data: {
					email_data: email_data
				},
				beforeSend: function() {
					$('#' + id).html('Sending...');
					$('#' + id).addClass('btn-danger');
				},
				success: function(data) {
					if (data == 'send') {
						$('#' + id).text('Success');
						$('#' + id).removeClass('btn-danger');
						$('#' + id).removeClass('btn-light');
						$('#' + id).addClass('btn-success');
					} else {
						$('#' + id).text(data);
					}
					$('#' + id).attr('disabled', false);
				}
			})
		});
	});
</script>