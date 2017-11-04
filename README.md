# PHP-CBS - Cinema Booking System

## Running the application in linux

There are several benefits to running this application in a 
linux environment, some of the benefits are: coloured error
 messages, dynamic terminal width detection, ability to run
 the bash script instead of invoking php every time.
 
#### Bash script

This can be invoked like so
```
./app.sh
```

## Using the User Interface


You are able to load a text based user interface to navigate
different parts of the booking system.

To run the application in this mode, you should pass the parameter `ui`

```
php ./app.php ui
```
or

```
./app.sh ui
```

## Commands Available

 - `ui` - Loads the User interface
 - `addmovie` - Adds a movie
 - `listmovies` - List all movies
 - `delmovie` - Deletes a movie
 - `addbooking` - Adds a booking
 - `listbookings` - Lists all bookings
 - `delbooking` - Deletes a booking
 
 
 
 | Command | Example |
 | --- | :--- |
 | addmovie | addmovie "The Avengers" "next wednesday" 10:30 |
 | listmovies | |
 | delmovie | delmovie "Movie ID" |
 | addbooking | addbooking "Movie ID" "Customer Name" "Seats Required" |
 | listbookings | listbookings "Movie ID" (ID optional) |
 | delbooking | delbooking "Movie ID" |
 | ui | |
 | help | |