Yes this is yet another Diablo 3 damage calculator. I am currently not satisfied with the existing D3 calculators out there as of 10/27/2012. Even though I feel that I'm almost done with the game, I feel even stronger the need to make this web-app; so it can help make it easier to select new weapons and armor. Plus I got REALLY excited about being able to access raw Diablo 3 data through an official BatttleNet Web API. Since I have a lot of experience with web development and coding around APIs, this should be a breeze for me. So I feel I must do this, tis my duty. :)

Your battle.net ID is required since request to Battle.net web API are limited per day. If the system works, all requests should be logged, and some rules put in to place, will minimize abuse.


Requirements:
- PHP 5.4.x
- How to setup use of PHP's user.ini
- MySQL 5.x and a MySQL database account with select, insert, and update permissions.
	note: It is required to set the value DSN in the settings file.

	Notes: In the near future MySQL will not be a requirement. I haven't tested this with any other database server
	applications. However, it should be trivial for any PHP developer to modify the code to use another DB like
	Postgres.


Setup Instructions:

STEP 1:
-----------------
	You will need to rename "settings.php.txt" to "setttings.php", the sites bootstrap will pick it up.

	After you remove the .txt extension from the settings file, you will also need to make following required changes:

	note: In all cases, leave the double quotes when you replace the values.

	- Replace "your@email.adr" with a valid email address, any alerts you need to know about will be sent to that email.
	- Replace "your-domain.com" with your valid domain name.
	- Update "mysql:host=yourDatabaseServer;dbname=yourDbName;charset=UTF-8" with the appropriate values.
	- Replace "yourDbUser" with your actual dabase username.
	- Replace "yourDbPassword" with your database passwork.
	- Replace "yourDbName" with the database you want to use.

STEP 2:
-----------------
	You will have to manually execute the SQL script found in /sql/tables.sql on your MySQL server.
	I used MySQL Workbench 5.2 CE to do this. After you have successfully run the script and verified all the tables
	exist in your chosen database, you can delete the sql folder and all its contents.

STEP 3: Optional
-----------------
	You need to give media/images write permissions for PHP so the image-processor script will be able to write images
	locally to your site.

STEP 4:
-----------------
	You now need to setup the user.ini. A default user.ini file is provided in the project. Rename "user.ini.txt" to
	"user.ini", or what ever name your PHP configuration has setup.

	Note: To see what file name your PHP configuration uses, view your sites phpinfo(), and look for "user_ini.filename"
	setting. The default is ".user.ini". Also check the "user_ini.cache_ttl" setting, this tells you (in seconds), how
	often the file is refreshed in the webserver, the default for that is 300 (5 minutes) seconds.

STEP 5:
-----------------
	Try to load the app in a browser. If your are able to see your characters, then your good to go. Otherwise,
	you need to comment or file an issue on github.
