
// local deployment proceedures for this application

// clone the application form github

git clone https://github.com/samtechelectronics/assessment.git

// CD into the project folder
cd p
//install composer dependencies using

composer install

//set up .env file

cp .env.example .env

//set the database name in the .env file

//Generate an app encryption key

php artisan key:generate

//migrate tables to the database

php artisan migrate

// sed the database with initial (dummy) data
// this command would take upto 60 minutes to complete as we are uploading a large data set

php artisan db:seed

// start application

php artisan serve



// Design thought 

The application was designed with scalability in mind.
Making it easy to modify the system at anytime.
A seperate game version table was created to make game version modification easy
for the game play it is grouped into single or multiple based on users playing the game.
this is acompanied by a seperation game play players table that accounts for the players in each game.

the script to populate the tables was all implemented in the database seeder of the the application to make deplayment easier.

