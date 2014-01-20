<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{{ pageTitle }} - by Khalifah Shabazz</title>
		<link rel="stylesheet" type="text/css" href="/css/site.css" />
	</head>
	<body>
		<form id="battlenet-get-profile" action="/get-profile.php" method="get">
			<fieldset>
				<div class="field">
					<label class="label">Enter your Battle.Net ID</label>
					<input class="input" type="text" name="battleNetId" value="{{battleNetId}}" size="50" />
				</div>
				<input type="submit" value="Get Heroes" />
			</fieldset>
		</form>
	</body>
</html>