# PHP-CBS - Cinema Booking System

The application can be run on any operating system that has PHP & MySQL installed.

There are several benefits to running this application in a 
linux environment, some of the benefits are: coloured error
 messages, dynamic terminal width detection, ability to run
 the bash script instead of invoking php every time.
 
#### Bash script (Linux only)

This can be invoked like so
```
./app.sh
```

Example usage of the bash script are

```
./app.sh addmovie "Terminator 2" thursday 10:30
```

## Using the User Interface

![ui example](https://raw.githubusercontent.com/ChubbyNinja/php-cbs/master/ui-example.png "Example UI")


You are able to load a text based user interface to navigate
different parts of the booking system.

To run the application in this mode, you should pass the parameter `ui`

```
./app.sh ui
```
or

```
php ./app.php ui
```

## Commands Available

 - `ui` - Loads the User interface
 - `addmovie` - Adds a movie
 - `listmovies` - List all movies
 - `delmovie` - Deletes a movie
 - `addbooking` - Adds a booking
 - `listbookings` - Lists all bookings
 - `delbooking` - Deletes a booking
 
 
 ### ui
 
 **Usage** 
 
 ```
 ./app.sh ui
 ``` 
 or
 ```
 php ./app.php ui
 ```
 
 This loads the user interface
 
 ---
 
 ### addmovie
 
 **Usage** 
 
 ```
 ./app.sh addmovie <movie> <date> <time>
 ```
 or
 ```
 php ./app.php addmovie <movie> <date> <time>
 ```
 
 Adds movie to the list of available movies
 
 **Examples** 
 
 ```
 ./app.sh addmovie "The Avengers" "next wednesday" 12:30
 ./app.sh addmovie "The Avengers 2" friday 15:30
 ./app.sh addmovie "The Avengers 3" 25/11/2017 15:30
 ```
 
  ---
  
 ### listmovies
 
  **Usage** 
  
  ```
  ./app.sh listmovies
  ``` 
  or
  ```
  php ./app.php listmovies
  ```
 
 Lists all movies in date/time order
 
   ---
   
 ### delmovie
 
  **Usage** 
  
  ```
  ./app.sh delmovie MovieID
  ``` 
  or
  ```
  php ./app.php delmovie MovieID
  ```
 Deletes the movie
 
  ---
  
 ### addbooking
 
  **Usage** 
  
  ```
  ./app.sh addbooking <MovieID> <Customer Name> <Seats Required>
  ``` 
  or
  ```
  php ./app.php addbooking <MovieID> <Customer Name> <Seats Required>
  ```
  
  Adds a booking and allocates the amount of seats required (if available).
  
   
 **Examples** 
 
 ```
 ./app.sh addbooking 4 "John Smith" 1
 ./app.sh addbooking 4 "James, Jim, Jesse" 3
 ```
 Example 1 will allocate 1 seat to John Smith, and example 2 will allocate 3 seats.
 
   ---
   
 ### listbookings
 
 
  **Usage** 
  
  ```
  ./app.sh listbookings <MovieID>
  ``` 
  or
  ```
  php ./app.php listbookings <MovieID>
  ```
  
  Lists the bookings against a specific movie, `<MovieID>` is an optional parameter, if omitted will list all bookings.
  
   ---
   
  ### delbooking
  
  
  **Usage** 
  
  ```
  ./app.sh delbooking BookingID
  ``` 
  or
  ```
  php ./app.php delbooking BookingID
  ```
  
 Deletes the booking
   
  ---
  
  ### help
 

  
  **Usage** 
  
  ```
  ./app.sh help
  ``` 
  or
  ```
  php ./app.php help
  ```
  
 Lists all available commands.
 
