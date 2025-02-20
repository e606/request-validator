# PHP Request Validator
[![Build Status](https://travis-ci.org/progsmile/request-validator.svg?branch=master)](http://travis-ci.org/progsmile/request-validator)  [![Total Downloads](https://poser.pugx.org/progsmile/request-validator/d/total)](https://packagist.org/packages/progsmile/request-validator) [![License](https://poser.pugx.org/progsmile/request-validator/license.svg)](https://packagist.org/packages/progsmile/request-validator) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/918ec166-799d-4ac1-a2c9-13d4cb8dafd4/mini.png)](https://insight.sensiolabs.com/projects/918ec166-799d-4ac1-a2c9-13d4cb8dafd4)
Make your apps validation easily (inspired by Laravel validators)

## Getting Started

:small_orange_diamond: [Please read our Feature Guide](https://github.com/progsmile/request-validator/blob/master/docs/Validator%20Guide.md)  you will probably like Validator :heart:


:small_orange_diamond: [All rules descriptions here](https://github.com/progsmile/request-validator/blob/master/docs/Rules-Guide.md)


:small_orange_diamond: [Easy installation](https://github.com/progsmile/request-validator#installation)

## Quick start :rocket:

```php
// Add namespace
use \Progsmile\Validator\Validator as V;

// Pass data to Validator, put rules and messages (also has error messages by default)
$v = V::make($_POST, [

    //group validation fields
    'firstname, lastname' => 'required|alpha|min:2',         //alphabetic support
    'lastname'            => 'max:18',                       //string max length
    'email'               => 'email|unique:users',           //email uniqueness in table users
    'id'                  => 'numeric|exists:users',         //such ID exists in table users
    'age'                 => 'min:16|numeric',               //numeric min
    'info[country]'       => 'required|alpha',               //EVEN ARRAY VALIDATION! Extremly crazy)
    'roll[0], roll[1]'    => 'numeric|between:1, 100',       //groupping arrays
    'date'                => 'dateFormat:(m-Y.d H:i)',       //custom date time format
    'profileImg'          => 'image',                        //image
    'phoneMask'           => 'phoneMask:(+38(###)###-##-##)',//custom phone mask validator
    'randNum'             => 'between:1, 10|numeric',        //value between for strings and numbers
    'ip'                  => 'ip',                           //ipv4 or ipv6
    'password'            => 'required|min:6',               //required fields
    'password_repeat'     => 'same:password',                //same validator
    'json'                => 'json',                         //json format
    'site'                => 'url',                          //url format
    'cash10, cash25'      => 'in:1, 2, 5, 10, 20, 50',       //in array
    'elevatorFloor'       => 'notIn:13'                      //not in array
], [
   'info[country].alpha' => 'Only letters please',           //Add message to array
   'email.required'      => 'Field :field: is required',     //Add custom messages
   'email.email'         => 'Email has bad format',          //:field:, :value:, :param: placeholders
   'email.unique'        => 'This email :value: is not unique',
   'elevatorFloor.notIn' => 'Oops',
]);

$v->passes() or $v->fails() //check validation result

$v->lastname->fails() or $v->lastname->passes() //check field validness


$v->first() //get first error message

$v->first('lastname') or $v->firstname->first() //get first error for `firstname`


$v->firsts() //return first error message from each field


$v->messages() or $v->messages('password') //get all messages (with param for concrete field)

$v->password->messages(); //get all `password` messages


$v->raw() //get 2d array with fields and messages


$v->add('someField', 'Something is wrong with this'); //appending messages
```

## Installation

### Installing via Composer

If you already have composer:
```composer require progsmile/request-validator=dev-master```
___

Install [Composer](http://getcomposer.org) in a common location or in your project:

```sh
$ curl -s http://getcomposer.org/installer | php
```

Create the `composer.json` file as follows:

```json
{
    "require": {
        "progsmile/request-validator": "@dev"
    }
}
```

Run the Composer installer:

```sh
$ php composer.phar install
```

### Available rules


##### [See all rules here](https://github.com/progsmile/request-validator/blob/master/docs/Rules-Guide.md) :100:



### Connect with PDO or use built-in Data Providers (just for unique and exists rules)

```php
// Connect once - use everywhere

use Progsmile\Validator\Validator as V;
use Progsmile\Validator\DbProviders\PhalconORM; //Phalcon
use Progsmile\Validator\DbProviders\Wpdb;       //Wordpress

V::setDataProvider(PhalconORM::class);

//or
V::setDataProvider(Wpdb::class);

//or
V::setupPDO('mysql:host=localhost;dbname=valid', 'root', '123');

//or
$pdo = $this->getPdoInstance(); //should be instance of PDO class

V::setPDO($pdo);


$v = V::make($this->request->getPost(), [
    'email'           => 'required|email|unique:users' //users - table name
    'password'        => 'min:6',
    'password_repeat' => 'same:password',
    'json'            => 'json'
]);

```

### Formatting - the best way to auto-reformat the returned array into your own style

The `$validator->format()`, by default, the messages will be formatted to html `<ul><li></li>...</ul>` element.

You can create your own class to format the array `$validator->messages()` into a well formed result.

```php
use Progsmile\Validator\Contracts\Format\FormatInterface;

class MarkdownFormatter implements FormatInterface
{
    public function reformat($messages)
    {
        $ret = '#### Error Found';

        foreach ($messages as $field => $message) {

            foreach ($message as $content) {

                $ret .= "- [x] ".$content."**\n"
            }
        }

        return $ret;
    }
}
```

Then in to use this call, you must do this way:

```php
$v = V::make(
    // ... some code here...
);

echo $v->format(MarkdownFormatter::class);
```

## Contributing :octocat:

Dear contributors , the project is just started and it is not stable yet, we love to have your fork requests.

## Testing

This testing suite uses [Travis CI](https://travis-ci.org/) for each run. Every commit pushed to this repository will queue a build into the continuous integration service and will run all tests to ensure that everything is going well and the project is stable.

The testing suite can be run on your own machine. The main dependency is [PHPUnit](https://github.com/sebastianbergmann/phpunit) which can be installed using [Composer](http://getcomposer.org):

```sh
# run this command from project root
$ composer install --dev --prefer-source
```

A MySQL database is also required for several tests. Follow these instructions to create the database:

```sh
echo 'create database valid charset=utf8mb4 collate=utf8mb4_unicode_ci;' | mysql -u root
cat tests/schema.sql | mysql valid -u root
```

For these tests we use the user `root` without a password. You may need to change this in `tests/TestHelper.php` file.

Once the database is created, run the tests on a terminal:

```sh
vendor/bin/phpunit --configuration phpunit.xml --coverage-text
```

For additional information see [PHPUnit The Command-Line Test Runner](http://phpunit.de/manual/current/en/textui.html).

## License

PHP Request Validator is open-sourced software licensed under the [GNU GPL](LICENSE).
© 2016 Denis Klimenko
