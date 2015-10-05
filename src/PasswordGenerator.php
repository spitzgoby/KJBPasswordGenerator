<?php
namespace KJBPasswordGenerator;

  class PasswordGenerator
  {
    /******************
     *** PROPERTIES ***
     ******************/

     /**
      * An array of words from the King James Bible. This value is lazily
      * instantiated to avoid the expensive process of reading the file
      * until absolutely necessary.
      */
     static private $words = null;

     /**
      * @return An array of words from the King James Bible.
      */
     static private function getWords() {
       if (self::$words == null) {
         self::$words = file("../resources/kjb_stripped.txt");
       }
       return self::$words;
     }

    /***********************************
     *** PASSWORD GENERATION OPTIONS ***
     ***********************************/

    /**
     * Determines the number of words to use in the generated password.
     * The value for this key must be a positive integer or the default
     * value of 4 will be used.
     */
    const OPTION_WORD_COUNT = 'wordCount';
    /**
     * Determines whether the password should include a special symbol. The
     * value for this key can be either a boolean expression, in which case the
     * '@' symbol will be used, or a character literal to be added to the end
     * of the genereated password.
     */
    const OPTION_USE_SYMBOL = 'useSymbol';
    /**
     * Determines whether the password should include a numeric character. The
     * value for this key can be either a boolean expression, in which case a
     * random digit will be used, or a character literal to be added to the end
     * of the generated password.
     */
    const OPTION_USE_NUMBER = 'useNumber';
    /**
     * Determines whether the password should contain hyphens between words. The
     * value for this key should be a boolean value.
    */
    const OPTION_USE_HYPHENS = 'useHyphens';
    /**
     * Determines the seed value for the selection of random words. Set this
     * value if you want to have determinstic password creation. No seeding will
     * be done if this value is not set.
     */
    const OPTION_SEED_VALUE = 'seedValue'; // currently does nothing

    /**
     * By default the password will be 4 words long.
     */
    const DEFAULT_WORD_COUNT = 4;
    /**
     * By default symbols will not be added to the password.
     */
    const DEFAULT_USE_SYMBOL = false;
    /**
     * By default numbers will not be added to the password.
     */
    const DEFAULT_USE_NUMBER = false;
    /**
     * By default hypehns will be used in between words.
     */
    const DEFAULT_USE_HYPHENS = true;

    /***************************
     *** PASSWORD GENERATION ***
     ***************************/

     private static function validateOptionsAndSetDefaults(&$options) {
       if (empty($options)) {
         $options = [];
       }

       // validate word count or set to default
       if (!isset($options[self::OPTION_WORD_COUNT]) || !self::validateWordCount($options[self::OPTION_WORD_COUNT])) {
         $options[self::OPTION_WORD_COUNT] = self::DEFAULT_WORD_COUNT;
       }

       // validate use symbol option or set to default
       if (!isset($options[self::OPTION_USE_SYMBOL]) || !self::validateUseCharOption($options[self::OPTION_USE_SYMBOL])) {
         $options[self::OPTION_USE_SYMBOL] = self::DEFAULT_USE_SYMBOL;
       }

       // validate use number of set to default
       if (!isset($options[self::OPTION_USE_NUMBER]) || !self::validateUseCharOption($options[self::OPTION_USE_NUMBER])) {
         $options[self::OPTION_USE_NUMBER] = self::DEFAULT_USE_NUMBER;
       }

       // validate use hyphens or set to default
       if (!isset($options[self::OPTION_USE_HYPHENS]) || !self::validateUseHyphens($options[self::OPTION_USE_HYPHENS])) {
         $options[self::OPTION_USE_HYPHENS] = self::DEFAULT_USE_HYPHENS;
       }
     }

     /**
      * Validates the word count option passed to generate a password. A valid
      * word count is a positive integer.
      */
     private static function validateWordCount($wordCount) {
       return is_integer($wordCount) && $wordCount > 0;
     }

     /**
      * Validates options concerning adding additional characters to the
      * password. Valid values are booleans or single character strings.
      */
     private static function validateUseCharOption($useOption) {
       return (is_bool($useOption) || // boolean value
        (is_string($useOption) && strlen($useOption) == 1)); // single character string
     }

     /**
      * Validates the hyphenation option for generating a password. Valid values
      * are true or false.
      */
     private static function validateUseHyphens($useHyphens) {
       return is_bool($useHyphens);
     }

    /**
     * Generates a new, random password based on the options provided.
     *
     * @param $options An array of options for password generation. See the
     *                 class documentation for the keys and values that can be
     *                 used within the array.
     */
    static public function generatePassword($options) {
      echo "Generating password";
      self::validateOptionsAndSetDefaults($options);
      self::__generatePassword(self::$words,
        $options[self::OPTION_WORD_COUNT],
        $options[self::OPTION_USE_SYMBOL],
        $options[self::OPTION_USE_NUMBER],
        $options[self::OPTION_USE_HYPHENS]);
    }

    static private function __generatePassword($words, $wordCount, $addSymbol, $addNumber, $addHyphens) {
      $password = "";
      srand();
      for ($i = 0; $i < $wordCount; $i++) {
        $wordIndex = rand(0, count($words) - 1);
        $password = $password . trim($words[$wordIndex]);
        if ($addHyphens && $i < $wordCount - 1) {
          $password = $password . "-";
        }
      }

      if ($addSymbol) {
        $password = $password . "@";
      }

      if ($addNumber) {
        $password = $password . rand(0, 9);
      }

      return $password;
    }
  }
