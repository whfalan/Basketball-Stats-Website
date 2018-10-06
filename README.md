# Data-website
Installation of the application:
-Install XAMPP(There maybe alternatives to this step)
	Deals with running the Apache server, MySQL, PHP, and also configures phpmyadmin.

-Run the MySQL code provided under the database folder.

-Check that the Database is being recognized. One way to do this is by going to the phpmyadmin page and checking one the left hand side to see if ‘nbastats’ shows up. You might need to refresh. If it does not, ensure that the MySQL port matches the port XAMPP is using. Since we were using MySQL Workbench prior, the default port was taken up and we had to change the config files to use a new one. However, this may not be necessary.

-Navigate to where xampp was installed and go to the htdocs folder. Here, make another folder and put the contents of the provided code under the website folder there. For me, my code location is at: C:\xampp\htdocs\series\dynamic\AtomCMS.

-Now open the localhost link that corresponds to the path where you stored the code. For example, for the above path that I used, my link is http://localhost/series/dynamic/AtomCMS/index.php.

-Given that the Apache server is running (have to start it on XAMPP) and MySQL is setup correctly, the website should load correctly.




-------------------- Functionalities implemented: ---------------------------------





Display a Table
Launch the application via the localhost link in the browser
Select the “Edit” tab
Type in one of the tables: “Game”, “GameStats”, “Player”, “Coach”, “Team”, “Season”, “Stadium”, “Contract”
Press Enter or click on Submit
Expected - the Data page should open up with the requested table displayed

    Search a Table
Display a table (follow instructions above for displaying if needed)
Click on the black search bar under the column names and type in something you want to search in the table.
Expected - the table will show the rows that contain the searched term highlighted

    Sort a Table’s columns
Display a table (follow instructions above for displaying if needed)
Click on the column name you want to sort by lexicographically. Click again to go descending.
Expected - the table will show the rows in correct sorted order based on column selected. 

Add in a game
Launch the application
Select the “Edit” tab
Type in “Game”
Add in the game information
Click on submit
Expected - add the game into the database, if each team entered has > 5 players, has_enough_players trigger is called

Add in / Edit a team
Launch the application
Select the “Edit” tab
Type in “Team”
Add in the team information
Click on submit
Expected - add the team into the database, if it has not been added already, edits the team if it is already in the database

Add in / Edit a stadium
Launch the application
Select the “Edit” tab
Type in “Stadium”
Add in the stadium information
Click on submit
Expected - add the stadium into the database, if it has not been added already, edits the stadium if it is already in the database

Add in / Edit a player
Launch the application
Select the “Edit” tab
Type in “Player”
Add in the Player information
Click on submit
Expected - add the Player into the database, if it has not been added already, edits the player if it is already in the database

Add in / Edit a coach
Launch the application
Select the “Edit” tab
Type in “Coach”
Add in the Coach information
Click on submit
Expected - add the coach into the database, if it has not been added already, edits the coach if it is already in the database

Add in / Edit a Contract
Launch the application
Select the “Edit” tab
Type in “Contract”
Add in the contract information
Click on submit
Expected - add the contract into the database, if it has not been added already, edits the contract if it is already in the database


    Delete an entry
Launch the application
Select the “Edit” tab
Type in intended table name
Click on submit
Click and hold on the row that is no longer wanted
Expected - The row should disappear from the table


Find out a team’s roster
Launch the application
Go to the main page, and fill in a team name for the input field (a placeholder value is put in for each of the questions)
Click on submit
Expected Value - All the team members on a given team. If the team is invalid or there are no players on the team provided, it will simply say ‘No players on this team.’

    Find out how many points were scored by the away team for a game:
Launch the application
Go to the main page and click submit on the question ‘Find out how many points were scored by the away team for game#:’
Put in a valid gameID (look at placeholder if a value is needed).
Click on submit
Expected Value - The sum of the points by the away team for the game. If there is no game by that ID, no value will be shown.

Find out how many players are free
Launch the application
Go to the main page and click submit on the question ‘Find out which players are free’
Click on submit
Expected Value - All the players that have no team. If there are no players free, it will simply say ‘None are free at the moment.
