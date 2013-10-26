Diablo 3 Assistant (D3A) is an application where any Diablo 3 player can access their characters via the Battle.Net
Web-API. This application attempts to give you real features that will help improve your game-play and enjoyment of
Diablo 3 (especially in Hardcore mode), such as:
 - Legend to indicate survivability/highest playable level, based on character stats.
 - Allow try on various in-game items without having to purchase them (some assembly may required.)
 - Easily compare your build with builds others have shared with a few clicks.
 - Legend to indicate which equipped items are holding you back from reaching higher levels.
 - Auto notifier (check-box) will email you when you are on a level you can't handle (requires application to be open
   during play.)
 - Item suggestions based on character class and level.

Try this application at: http://d3.kshabazz.net

One possible use:
If you want to try an item on before you purchase it (auction or trade), you can use the D3A Item-Forge. In the item
forge you can re-construct the item, stat-by-stat, then equipped. It will also be save for later, so you can pull it up
at any time (either through search or adding it to your favorite items list.). Your stats will be updated and you be
able to see if it improved, unimproved, or no real difference will be achieved.

Requirements:
- Battle.Net ID with Diablo 3 characters.
	note: Your battle.net ID is required since request to Battle.net web API are limited per day. If the system works, all requests should be logged, and some rules put in to place, will minimize abuse.
- PHP 5.4.x
- PHP user.ini
- For Apache ".htaccess" for IIS "IIRF" installed, for URL rewriting.
- writeable media folder
- MySQL 5.x and a MySQL database account with select, insert, and update permissions.
	note: It is required to set the value DSN in the settings file.

	Notes: In the near future MySQL will not be a requirement. I haven't tested this with any other database server
	applications. However, it should be trivial for any PHP developer to modify the code to use another DB like
	Postgres.


Set-up Instructions:

STEP 1:
-----------------
	You will need to rename "D3/settings.php.txt" to "D3/setttings.php", the sites bootstrap will pick it up.

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
	Rename "sample.htaccess" to ".htaccess". This is need for the images to work.
	Note: Make sure the media/images folder is writeable by PHP in order to save the images from Battle.Net locally.
		  This will greatly speed up loading time, since the images will be local.

STEP 6:
-----------------
	Try to load the app in a browser. If your are able to see your characters, then your good to go. Otherwise,
	you need to comment or file an issue on github.