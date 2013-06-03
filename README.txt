Diablo 3 Assistant (D3A) is an application where any Diablo 3 player can access their characters via the Battle.Net Web-API. This app can assist a player with the build of their character in various ways. For example, if you want to try an item on before you purchase it, you can use the D3A Item-Forge, to replicate an item, and then try out the item on the hero page. This app tries to keep the D3 game feel, rather than just being another calculator. And strongly plays off of the official D3 profile page. This app tries to give you real features that will help improve your game play and enjoyment of Diablo 3, such as:

	- Try-before you buy, with the Item forge – Replicate items in the Item Forge, then go to the hero page to try them on. See if your stats improve or not.
	- Character survivability for different levels and acts – on the hero page, select a level and act, then a green/yellow/red status will indicate if you’re ready for it (coming soon).
	- More from suggestions.
	- Auto notifier - A checkbox on the hero page that let's you know when it's time to go shopping, bacause your next mob could be your last.
	- Suggest items that are best for your type of character build.

Try this app out at: http://d3.kshabazz.net

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


Setup Instructions:

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