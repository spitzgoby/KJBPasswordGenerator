KJB Password Generator
======================

About
-----
The KJB Password Generator uses words from the King James Bible to generate an
[xkcd](https://xkcd.com/936/) style password.

Installation
------------
### Using [Composer](https://getcomposer.org/) ###
1. Add `spitzgoby/kjbpw` to `composer.json` requirements section
```json
"require": {
     "spitzgoby/kjbpw"
}
```
2. Run `composer install`

Usage
-----
### Basic ###
The password generator can be invoked statically using default options  
```php
// Use password generator with default options
use KJBPasswordGenerator\PasswordGenerator as kjbpwg;
$password = kjbpwg::generatePassword();
echo $password;

// Sample Output: grey-ministering-shoelatchet
```
- - -
### Options ###
Alternatively, an array of options can be passed to alter the generation
```php
// Use password generator with word count and symbol options
use KJBPasswordGenerator\PasswordGenerator as kjbpwg;
$options = [kjbpwg::OPTION_WORD_COUNT => 3,
            kjbpwg::OPTION_USE_SYMBOL => '!'];
$password = kjbpwg::generatePassword($options);
echo $password;

// Sample Output: Shelemiah-father-Sibbechai!
```
- - -
The `$options` array should be composed of valid key/value pairs as defined by
the `KJBPasswordGenerator\PasswordGenerator::OPTION_{option_name}` constants. Any
unrecognized keys or invalid values will be ignored and replaced by the
defaults.  

Available option keys include:
* `OPTION_WORD_COUNT` => Default is `4`   
  Determines the number of random words to be pulled from the King James Bible.
* `OPTION_USE_SYMBOL` => Default is `false`  
  Can be a boolean or string value.
  by the default.
    * boolean: `DEFAULT_SYMBOL` (currently an @ symbol) value will be appended to the
      password.
    * string: the given string will be appended to the end of the password.
* `OPTION_USE_NUMBER` => Default is `false`  
  Can be a boolean, integer, or string value.
    * boolean: A random integer between 0-9 will be appended to the password.
    * integer: The given integer will be appended to the password.
    * string: The string must contain only decimal digits. If so it will be
      appended to the end of the password.
* `OPTION_USE_HYPHENS` => Default is `true`   
  Should only be a boolean value. This determines whether the randomly selected
  words should be separated by hyphens to improve readability and memorability.
