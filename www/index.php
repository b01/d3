<!DOCTYPE html>
<html>
	<head>
		<title>D3 Assistant by Khalifah Shabazz</title>
	</head>
	<body>
		<form id="battlenet-get-profile" action="/get-profile.php" method="post">
			<fieldset>
				<div class="field">
					<label class="label">Enter your Battle.Net ID</label>
					<input class="input" type="text" name="battleNetId" value="{{battleNetId}}" />
				</div>
				<input type="submit" value="Get Heroes" />
			</fieldset>
		</form>
	</body>
</html>