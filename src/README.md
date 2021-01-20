
// local deployment proceedures for this applicatio

// clone the application form github

git clone linktogithubrepo.com/ projectName

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


