# Petrol
### Database Fuel

##### A framework for parsing files and filling databases.

[![Build Status](https://travis-ci.org/zachleigh/petrol.svg?branch=master)](https://travis-ci.org/zachleigh/petrol)
[![Latest Stable Version](https://poser.pugx.org/zachleigh/petrol/version.svg)](//packagist.org/packages/zachleigh/petrol)
[![License](https://poser.pugx.org/zachleigh/petrol/license.svg)](//packagist.org/packages/zachleigh/petrol)

### Contents

##### [Quick Example](#quick-example)
  * [Standard Version](#standard-example)
  * [Laravel Version](#laravel-example)

##### [Installation](#installation-1)
##### [Examples](#examples-1)
##### [Command Library](#commands-1)
##### [Database Notes](#database-notes-1)

## Quick Example
#### Standard Example
Let's parse a file and fill a Mysql table with **one line of code**.

Our file, **simple.txt**.
```xml
Bob Smith / bob@example.com / 3974 South Belvie St.
Jean Samson / jean@example.com / 456 North Main
George Higgins / george@example.com / 9844 East South Ave.
Mike Victors / mike@example.com / 987 Cheese Street
Betty Lou Victors / betty@example.com / 987 North Colorado Bvd.
```
We've got names, emails, and address, seperated by spaces and slashes.

Our database table, **simple_table**.

| id       | name     | email    | address  |
|:--------:|:--------:|:--------:|:--------:|

A column for each item in **simple.txt** plus an auto-incrementing id column.

Install Petrol.
```cmd
composer require zachleigh/petrol
```

Navigate to vendor/zachleigh/Petrol in the console and make a .env file.
```cmd
./petrol make env
```

Move **simple.txt** into Petrol/src/Files/.

Make a new filler file.
```cmd
./petrol new simple_table --file=simple.txt
```

Sweet.  We now have a FillSimpleTable file in Petrol/src/Fillers/.

Open it up.
```php
namespace Petrol\Fillers;

use Petrol\Core\Database\Connection;
use Petrol\Core\Helpers\Traits\Parser;
use Petrol\Core\Helpers\Traits\User;
use Petrol\Core\Helpers\Traits\XmlParser;

class FillSimpleTable extends Filler
{
    use Parser, User;

    /**
     * Database connection class.
     *
     * @var Petrol\Core\Database\Connection
     */
    protected $connection;

    /**
     * Database filling method. If 'auto', Petrol will automatically insert one row
     * every line. If 'manual', insertRow($data) must be called on $this->connection
     * to insert a row. 'dump' will dump results to console instead of filling db.
     *
     * @var string ['auto, 'manual', 'dump']
     */
    protected $fill = 'auto';

    /**
     * The file to be parsed by the Filler. File must be in Petrol/src/Files/.
     *
     * @var string
     */
    protected $file = 'simple.txt';

    /**
     * The database table to be filled. Table must be created before filling.
     *
     * @var string
     */
    protected $table = 'simple_table';

    /**
     * Database table columns excluding id.
     *
     * @var array
     */
    protected $columns = [
        //
    ];

    /**
     * Variables to be declared before loop begins. These variables will be stored
     * on the object and can be accessed with $this->key.
     *
     * @var array
     */
    protected $variables = [
        //
    ];

    /**
     * Construct.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    /**
     * Parse the file. Petrol will go through $file line by line and send each line
     * to this parse method.
     *
     * @param string $line [individual lines from file]
     *
     * @return array $data  [array of columns => values for line]
     */
    protected function parse($line)
    {
        // parse file
        //
        // return array;
    }
}
```

Enter the database columns in the $columns array. (We don't need the id column.)
```php
    protected $columns = [
        'name',
        'email',
        'address'
    ];
```

Enter your line parsing code in the parse method.
```php
    protected function parse($line)
    {
        return array_combine($this->columns, $this->cleanExplode('/', $line));
    }
```

Fill your table.
```cmd
./petrol fill simple_table
```

Done.  Reward yourself with a drink of your choice.
(A more detailed version of this tutorial can be found [here](#simple-table).)

#### Laravel Example
Let's parse a file and fill a Mysql table with **one line of code** in a Laravel application.

Our file, **simple.txt**.
```xml
Bob Smith / bob@example.com / 3974 South Belvie St.
Jean Samson / jean@example.com / 456 North Main
George Higgins / george@example.com / 9844 East South Ave.
Mike Victors / mike@example.com / 987 Cheese Street
Betty Lou Victors / betty@example.com / 987 North Colorado Bvd.
```
We've got names, emails, and address, seperated by spaces and slashes.

Our database table, **simple_table**.

| id       | name     | email    | address  |
|:--------:|:--------:|:--------:|:--------:|

A column for each item in **simple.txt** plus an auto-incrementing id column.

Install Petrol in your application.
```cmd
composer require zachleigh/petrol
```

Register the Petrol service provider in app/Providers/AppServiceProvider.php.
```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('Petrol\Core\Providers\PetrolServiceProvider');
    }
}
```

Run `php artisan` and make sure the Petrol commands are listed.

Make the Petrol directory in the app/ directory.
```cmd
php artisan vendor:publish
```

You now have a Petrol directory in /app with Files and Fillers directories in it.

Move **simple.txt** into the Files directory.

Make a new filler file.
```cmd
php artisan petrol:new simple_table --file=simple.txt
```

Sweet.  We now have a FillSimpleTable file in app/Petrol/Fillers/.

Open it up.
```php
namespace App\Petrol\Fillers;

use Petrol\Core\Database\Connection;
use Petrol\Core\Helpers\Traits\Parser;
use Petrol\Core\Helpers\Traits\User;
use Petrol\Core\Helpers\Traits\XmlParser;

class FillSimpleTable extends Filler
{
    use Parser, User;

    /**
     * Database connection class.
     *
     * @var Petrol\Core\Database\Connection
     */
    protected $connection;

    /**
     * Database filling method. If 'auto', Petrol will automatically insert one row
     * every line. If 'manual', insertRow($data) must be called on $this->connection
     * to insert a row. 'dump' will dump results to console instead of filling db.
     *
     * @var string ['auto, 'manual', 'dump']
     */
    protected $fill = 'auto';

    /**
     * The file to be parsed by the Filler. File must be in Petrol/src/Files/.
     *
     * @var string
     */
    protected $file = 'simple.txt';

    /**
     * The database table to be filled. Table must be created before filling.
     *
     * @var string
     */
    protected $table = 'simple_table';

    /**
     * Database table columns excluding id.
     *
     * @var array
     */
    protected $columns = [
        //
    ];

    /**
     * Variables to be declared before loop begins. These variables will be stored
     * on the object and can be accessed with $this->key.
     *
     * @var array
     */
    protected $variables = [
        //
    ];

    /**
     * Construct.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    /**
     * Parse the file. Petrol will go through $file line by line and send each line
     * to this parse method.
     *
     * @param string $line [individual lines from file]
     *
     * @return array $data  [array of columns => values for line]
     */
    protected function parse($line)
    {
        // parse file
        //
        // return array
    }
}
```

Enter the database columns in the $columns array. (We don't need the id column.)
```php
    protected $columns = [
        'name',
        'email',
        'address'
    ];
```

Enter your line parsing code in the parse method.
```php
    protected function parse($line)
    {
        return array_combine($this->columns, $this->cleanExplode('/', $line));
    }
```

Fill your table.
```cmd
php artisan petrol:fill simple_table
```

Done.  Reward yourself with a drink of your choice.
(A more detailed version of this tutorial can be found [here](#simple-table).)

## Installation

#### Requirements
 * ###### PHP 7.1 or higher
Linux users can find PHP releases in their distribution repositories.
For other operating systems, visit the [php installation guide](http://php.net/manual/en/install.php) for instructions.

 * ###### composer
Check the [composer documentation](https://getcomposer.org/doc/00-intro.md) for installation instructions.


#### Install

If requirements are met, you can install the package in two ways.

###### Download
Download [here](https://github.com/zachleigh/petrol/releases), cd into the Petrol directory and run

```cmd
composer install
```

Finished.  How easy was that?

###### Through composer
```cmd
composer require zachleigh/petrol
```
If you install through composer, the program will be in vendor/zachleigh/petrol

#### A Little Setup
###### Executables
Once you have Petrol installed, you may want to make the 'petrol' file executable so it can be run without having to type php before the command.
Not executable:
```cmd
php petrol argument
```
Executable:
```cmd
./petrol argument
```

###### Environment Setup
You will need to create a .env file and fill in the appropriate information.  You can do this manually by copying the .env.example file to .env or by using the built-in command line tool.
```cmd
./petrol make env
```

###### Config File
One more step, then you're ready to go.  Open up config.php and make sure that 'database' is set to the database of your choice.  Currently, only mysql is supported out of the box.

## Examples
  * [Simple Table](#simple-table)
  * [XML Table](#xml-table)
  * [XML Table JSON Array](#xml-table-json-array)

#### Simple Table
Let's fill up a simple database.

For this brief tutorial, we will be filling the **simple_table** Mysql database table with info from the **simple.txt** file.
**simple.txt**
```xml
Bob Smith / bob@example.com / 3974 South Belvie St.
Jean Samson / jean@example.com / 456 North Main
George Higgins / george@example.com / 9844 East South Ave.
Mike Victors / mike@example.com / 987 Cheese Street
Betty Lou Victors / betty@example.com / 987 North Colorado Bvd.
```
We have slash seperated names, email addresses, and physical addresses that need to get into our database.

##### Step 1
Before we do anything else, we need to put our data file, **simple.txt**, in Petrol/src/Files/.

##### Step 2
Once our file is where it needs to be, we can make a new Filler with the 'new' command.  The first argument of the 'new' command is the name of the table, **simple_table** in our case.  The option --file is the name of the file to use, in this case **simple.txt**.
```cmd
./petrol new simple_table --file=simple.txt
```

The Filler will be in Petrol/src/Fillers/ and will be called **FillCamelCasedTableName**, in our case **FillSimpleTable**.    It will look something like this.
```php
namespace Petrol\Fillers;

use Petrol\Core\Database\Connection;
use Petrol\Core\Helpers\Traits\User;
use Petrol\Core\Helpers\Traits\Parser;

class FillSimpleTable extends Filler
{
    use Parser, User;

    /**
     * Database connection class.
     *
     * @var Petrol\Core\Database\Connection
     */
    protected $connection;

    /**
     * Database filling method. If 'auto', Petrol will automatically insert one row
     * every line. If 'manual', insertRow($data) must be called on $this->connection
     * to insert a row. 'dump' will dump results to console instead of filling db.
     *
     * @var string ['auto, 'manual', 'dump']
     */
    protected $fill = 'auto';

    /**
     * The file to be parsed by the Filler. File must be in Petrol/src/Files/.
     *
     * @var string
     */
    protected $file = 'simple.txt';

    /**
     * The database table to be filled. Table must be created before filling.
     *
     * @var string
     */
    protected $table = 'simple_table';

    /**
     * Database table columns excluding id.
     *
     * @var array
     */
    protected $columns = [
        //
    ];

    /**
     * Variables to be declared before loop begins. These variables will be stored
     * on the object and can be accessed with $this->key.
     *
     * @var array
     */
    protected $variables = [
        //
    ];

    /**
     * Construct.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    /**
     * Parse the file. Petrol will go through $file line by line and send each line
     * to this parse method.
     *
     * @param string $line [individual lines from file]
     *
     * @return array $data  [array of columns => values for line]
     */
    protected function parse($line)
    {
        // parse file
        //
        // return $data;
    }
}
```
Let's take a look at it.

* Our file and table names are saved on the object as $file and $table.  You shouldn't have to do anything else with these.
* Our filler uses the traits Parser and User. Parser contains general methods to help you parse files and User is where you can put your own functions.
* Our filler is already attached to our database through the $connection object on the class.
* You can choose to auto fill your database (default), fill it manually, or dump it to the console for debugging.
* There is a $columns array where we must enter our database column names.
* There is a $variables array where we can enter variables to be set before looping through the file lines.  These will be set on the object and can be accessed with $this->variable.
* There is a parse method where we will be given the file's lines one at a time.

##### Step 3
We now need to set our database columns in the protected $columns array.  Our sample file, **simple.txt**, contains names, emails, and address so our database should have **name**, **email**, and **address** columns. We will put these in the $columns array.
```php
    protected $columns = [
        'name',
        'email',
        'address'
    ];
```

Note that the column names need to match the columns in the mysql table exactly, excluding an auto-incrementing id column.  In this case, our mysql table looks like this:

| id       | name     | email    | address  |
|:--------:|:--------:|:--------:|:--------:|

##### Step 4
Ok, let's write our parsing logic.
Petrol will iterate over $file line-by-line and for each line, it will run the protected method parse().  In parse, we have access to $line, one individual line from the file. We can write our line parsing logic there.  The parse() method must return an array representing one row of the database.  In other words, we must return a $key => $value pair array where each $key equals a column name (defined in $columns) and its $value equals the value we wish to assign to that column.  In our example, we want to seperate the name, email, and address fields in the file and then assign them to name, email, and address keys in the returned array.
```php
    protected function parse($line)
    {
        return array_combine($this->columns, $this->cleanExplode('/', $line));
    }
```
Let's look at this a little deeper.  First, we pass $line and '/' (the symbol we want to break the line with) to cleanExplode.  cleanExplode is a method in the Parser trait. It is similar to the standard php explode function, except that it also trims white space from each item in the returned array.  We then use array_combine to match the column keys with the values returned from cleanExplode. The resulting data structure looks like this.
```php
Array
(
    [name] => Bob Smith
    [email] => bob@example.com
    [address] => 3974 South Belvie St.
)
Array
(
    [name] => Jean Samson
    [email] => jean@example.com
    [address] => 456 North Main
)
Array
(
    [name] => George Higgins
    [email] => george@example.com
    [address] => 9844 East South Ave.
)
Array
(
    [name] => Mike Victors
    [email] => mike@example.com
    [address] => 987 Cheese Street
)
Array
(
    [name] => Betty Lou Victors
    [email] => betty@example.com
    [address] => 987 North Colorado Bvd.
)

```
Notice that the keys in each array equal the $column variable items.  This is a must.  You've been warned.

##### Step 5
We're almost done. After saving the Filler file, we simply run the fill command.  The command requires one argument, the database table name.  In our case this is **simple_table**.
```cmd
./petrol fill simple_table
```

And we are done.  You just parsed a file and filled a Mysql table with one line of code.

If you're getting errors, you can use the **--errors** flag to dump any Mysql PDO errors you may be getting.  You can also set $fill to 'dump' to dump to the console instead of filling your database.

#### XML Table

In this short tutorial, we'll be filling a table using an XML file.  We will be using the **books.xml** file and filling the **books** table in our database.
**books.xml**
```xml
<?xml version="1.0"?>
<catalog>
   <book id="bk101">
      <author>Gambardella, Matthew</author>
      <title>XML Developer's Guide</title>
      <genre>Computer</genre>
      <price>44.95</price>
      <publish_date>2000-10-01</publish_date>
      <description>An in-depth look at creating applications
      with XML.</description>
   </book>
   <book id="bk102">
      <author>Ralls, Kim</author>
      <title>Midnight Rain</title>
      <genre>Fantasy</genre>
      <price>5.95</price>
      <publish_date>2000-12-16</publish_date>
      <description>A former architect battles corporate zombies,
      an evil sorceress, and her own childhood to become queen
      of the world.</description>
   </book>
   <book id="bk103">
      <author>Corets, Eva</author>
      <title>Maeve Ascendant</title>
      <genre>Fantasy</genre>
      <price>5.95</price>
      <publish_date>2000-11-17</publish_date>
      <description>After the collapse of a nanotechnology
      society in England, the young survivors lay the
      foundation for a new society.</description>
   </book>
   <book id="bk104">
      <author>Corets, Eva</author>
      <title>Oberon's Legacy</title>
      <genre>Fantasy</genre>
      <price>5.95</price>
      <publish_date>2001-03-10</publish_date>
      <description>In post-apocalypse England, the mysterious
      agent known only as Oberon helps to create a new life
      for the inhabitants of London. Sequel to Maeve
      Ascendant.</description>
   </book>
   <book id="bk105">
      <author>Corets, Eva</author>
      <title>The Sundered Grail</title>
      <genre>Fantasy</genre>
      <price>5.95</price>
      <publish_date>2001-09-10</publish_date>
      <description>The two daughters of Maeve, half-sisters,
      battle one another for control of England. Sequel to
      Oberon's Legacy.</description>
   </book>
   <book id="bk106">
      <author>Randall, Cynthia</author>
      <title>Lover Birds</title>
      <genre>Romance</genre>
      <price>4.95</price>
      <publish_date>2000-09-02</publish_date>
      <description>When Carla meets Paul at an ornithology
      conference, tempers fly as feathers get ruffled.</description>
   </book>
   <book id="bk107">
      <author>Thurman, Paula</author>
      <title>Splish Splash</title>
      <genre>Romance</genre>
      <price>4.95</price>
      <publish_date>2000-11-02</publish_date>
      <description>A deep sea diver finds true love twenty
      thousand leagues beneath the sea.</description>
   </book>
   <book id="bk108">
      <author>Knorr, Stefan</author>
      <title>Creepy Crawlies</title>
      <genre>Horror</genre>
      <price>4.95</price>
      <publish_date>2000-12-06</publish_date>
      <description>An anthology of horror stories about roaches,
      centipedes, scorpions  and other insects.</description>
   </book>
   <book id="bk109">
      <author>Kress, Peter</author>
      <title>Paradox Lost</title>
      <genre>Science Fiction</genre>
      <price>6.95</price>
      <publish_date>2000-11-02</publish_date>
      <description>After an inadvertant trip through a Heisenberg
      Uncertainty Device, James Salway discovers the problems
      of being quantum.</description>
   </book>
   <book id="bk110">
      <author>O'Brien, Tim</author>
      <title>Microsoft .NET: The Programming Bible</title>
      <genre>Computer</genre>
      <price>36.95</price>
      <publish_date>2000-12-09</publish_date>
      <description>Microsoft's .NET initiative is explored in
      detail in this deep programmer's reference.</description>
   </book>
   <book id="bk111">
      <author>O'Brien, Tim</author>
      <title>MSXML3: A Comprehensive Guide</title>
      <genre>Computer</genre>
      <price>36.95</price>
      <publish_date>2000-12-01</publish_date>
      <description>The Microsoft MSXML3 parser is covered in
      detail, with attention to XML DOM interfaces, XSLT processing,
      SAX and more.</description>
   </book>
   <book id="bk112">
      <author>Galos, Mike</author>
      <title>Visual Studio 7: A Comprehensive Guide</title>
      <genre>Computer</genre>
      <price>49.95</price>
      <publish_date>2001-04-16</publish_date>
      <description>Microsoft Visual Studio 7 is explored in depth,
      looking at how Visual Basic, Visual C++, C#, and ASP+ are
      integrated into a comprehensive development
      environment.</description>
   </book>
</catalog>
```

We have 12 books, each with an id stored as an attribute on the book tag, an author, a title, a genre, a price, a publish date, and a description.  In this tutorial, we will be filling a table that has one column for each field.  Our database will look like this:

| id       | book-id  | author   | title    | genre    | price    | publish-date  | description |
|:--------:|:--------:|:--------:|:--------:|:--------:|:--------:|:-------------:|:-----------:|

##### Step 1
Move **books.xml** into Petrol/src/Files.

##### Step 2
Make an new Filler with the new command.
```cmd
./petrol new books --file=books.xml
```

##### Step 3
In the newly created FillBooks file, enter the database columns in the $columns array.
```php
    protected $columns = [
        'book-id',
        'author',
        'title',
        'genre',
        'price',
        'publish_date',
        'description'
    ];
```

##### Step 4
Next, we need to write our parsing logic.  Because this is an XML file, we can use the XmlParser helper trait. At the top of the FillBooks file, add XmlParser to the list of traits.
```php
class FillBooksColumns extends Filler
{
    use Parser, User, XmlParser;
    ...
```

We will also need to fill our table manually, so set $fill to 'manual'.
```php
protected $fill = 'manual';
```

Now we can use all the methods available in XmlParser to help us.
```php
    protected function parse($line)
    {
        $this->setRootTag('catalog');

        $array = $this->xmlToArrays($line, 'book');

        if (!is_null($array)) {
            $book_id = $this->getAttributeFromArray($array, 'id');

            $data = $this->arrayToData($array, $this->columns);

            $data['book_id'] = $book_id;

            print_r($data);

            $this->connection->insertRow($data);
        }
    }
```

Let's have a look at this.  First, when using the the XmlParser helpers, we need to set the root XMNL tag with setRootTag(). This identifies when the XML to be read actually starts and makes parsing easier. Because we want to get an attribute (book_id) as well, we are going to have to do a two step parse.  First, we will convert each book tree into an array with xmlToArrays().  This method requires $line and the root tag of the tree you want to convert to an array, in our case 'book.'  This will return null unless the closing book tag has been reached, in which case it will return an array. Because the xmlToArrays method returns null unless the closing tag has been reached, we need to check for null before proceeding.

Once we get our array, we can get the 'book_id' attribute off the array with the getAttributeFromArray method.  (Note that this method is still in development and needs to be tested more.) Once we have the attribute, we can convert our array to a data array with arrayToData, which requires the array we generated before as well as $columns.  This method will return a $key => $value pair array where $key is a column name and $value is a value from the XML file.

We then simply place 'book_id' on the returned data array and manually insert the row with $this->connection->insertRow.

If we didnt have to get the 'book_id' attribute, this would have been even easier.  Instead of converting the XML to an array and then converting the array to returnable data, we could have simply converted the XMl directly to a data array.
```php
    protected function parse($line)
    {
        $this->setRootTag('catalog');

        $data = $this->xmlToData($line, 'book', $this->columns);

        if (!is_null($data)) {
            $this->connection->insertRow($data);
        }
    }
```

##### Step 5
All we have to do now is fill our table.
```cmd
./petrol fill books
```
Finished.

#### XML Table JSON Array

In some situations, it is better to enter the XML data into a database as a JSON array.  This isn't always desired because it makes searching the database more difficult and requires decoding of the returned values, but for some situations, this is a good way to go about handling XML data.

This very brief tutorial is similar to the previous tutorial, except our database table and our parsing function will be different.
Our database structure:

| id       | book_id  | info     |
|:--------:|:--------:|:--------:|

So in our Filler file, our columns array will look like this:
```php
    protected $columns = [
        'book_id',
        'info'
    ];
```

Our parsing function will look like this:
```php
    protected function parse($line)
    {
        $this->setRootTag('catalog');

        $array = $this->xmlToArrays($line, 'book');

        if (!is_null($array)) {
            $data['book_id'] = $this->getAttributeFromArray($array, 'id');

            $data['info'] = json_encode($array);

            $this->connection->insertRow($data);
        }
    }
```
Rather than convert the array returned from xmlToArrays into an array that can be fed to our Mysql statement, we will instead json encode it.  When you get the database value on the other end, you can simply use json_decode to get back the original structure of the array.  Its not for every situation, but in some cases its the best solution.

## Command Library
  * [fill](#fill)
  * [make](#make)
  * [new](#new)

### fill

Fill a database table using the Filler made with the **new** command.  table_name needs to match name of database table used when creating the Filler.
```cmd
./petrol fill table_name [--options]
```
##### Options
###### errors
Dump Mysql PDO errors
```cmd
./petrol fill table_name --errors
```

###### quiet
Quiet all user input prompts.
```cmd
./petrol fille table_name --quiet
```

### make
Make something
```cmd
./petrol make thing_to_make
```
##### Things you can make
###### env
Make a .env file with database credentials
```cmd
./petrol make env
```

### new
Make a new Filler to parse a file and fill a database table.  table_name needs to match the database table name.
```cmd
./petrol new table_name [--options]
```
##### Options
###### file
Set the file to be used by the Filler.
```cmd
./petrol new table_name --file=example_file.txt
```
###### path
Set the new Filler save location.  (For testing purposes only.)

## Database Notes

##### Requirements

Your database must meet the following requirements:
* Tables must have an auto-incrementing 'id' column
* Column names must exactly match the expected field names.

##### Other database types

Currently, only Mysql is supported.  The database connection uses php PDO drivers that can be changed out fairly easily.  Currently, PDO supports 12 database types.  Check the [driver list](http://php.net/manual/en/pdo.drivers.php) for more information.  If you wish to make an adapter for one of these database types, adapter name rules must be followed.
* Cuprid: **CupridDatabase**
* FreeTDS / Microsoft SQL Server / Sybase: **DblibDatabase**
* Firebird: **FirebirdDatabase**
* IBM DB2: **IbmDatabase**
* IBM Informix Dynamic Server: **InformixDatabase**
* MySQL: **MysqlDatabase**
* Oracle Call Interface: **OciDatabase**
* ODBC v3 (IBM DB2, unixODBC and win32 ODBC): **OdbcDatabase**
* PostgreSQL: **PgsqlDatabase**
* SQLite 3 and SQLite 2: **SqliteDatabase**
* Microsoft SQL Server / SQL Azure: **SqlsrvDatabase**
* 4d: **FourD** (A class naming rule exception exists for this, but it is untested)

The adapter class should be in its own file in src/Core/Database/Databases/ and must implement DatabaseInterface. If you make a new adapter, please let me know so I can include it in the main program.  If you dont know how to write a new adapter, let me know and I'll do it if time permits.

Besides making an adapter, you will also have to make a new array for the database type in 'connections' in config.php.

