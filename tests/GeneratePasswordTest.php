<?php

use KJBPasswordGenerator\PasswordGenerator as kjbpwg;

class GeneratePasswordTest extends PHPUnit_Framework_TestCase {
  /**
   * Counts the number of words in the password after breaking the string apart
   * according to its hyphenation.
   *
   * @param $password The password to be counted.
   * @return The number of words found in the password.
   */
  private function countWordsInHyphenatedPassword($password) {
    return count(explode("-", $password));
  }

  /**
   * Asserts that the given string only contains alphabetic or numeric
   * characters. The assertion fails if any other characters are found.
   *
   * @param $string The string to be tested for non-alphanumeric characters.
   * @param $message The message to display upon assertion failure.
   */
  private function assertAlphaNumeric($string, $message = "") {
    $this->assertTrue(ctype_alnum($string), $message);
  }

  /**
   * Asserts that the given string contains no numeric characters.
   *
   * @param $string The string to be tested for numeric characters.
   * @param $message The message to be displayed upon assertion failure.
   */
  private function assertNonNumeric($string, $message = "") {
    $this->assertEquals(0, preg_match("/[0-9]/", $string), $message);
  }

  /**
   * Asserts that the given string contains at least one numeric character.
   *
   * @param $string The string to be tested for existence of numeric characters.
   * @param $message The message to be displayed upon assertion failure.
   */
  private function assertContainsNumeric($string, $message = "") {
    $this->assertNotEquals(0, preg_match("/[0-9]/", $string), $message);
  }

  /***************************
   *** TEST DEFAULT VALUES ***
   ***************************/

  public function testPasswordCanBeGenerated() {
    $password = kjbpwg::generatePassword();
    $this->assertNotNull($password, "PasswordGenerator did not create a password\n");
  }

  public function testPasswordGeneratedWithDefaultWordCount() {
    $password = kjbpwg::generatePassword();
    $defaultWordCount = kjbpwg::DEFAULT_WORD_COUNT;
    $this->assertEquals($defaultWordCount, $this->countWordsInHyphenatedPassword($password), "PasswordGenerator created password with non-default number of words\n");
  }

  public function testPasswordGeneratedWithDefaultUseSymbol() {
    $password = kjbpwg::generatePassword();
    $strippedPassword = str_replace("-", "", $password);
    $this->assertAlphaNumeric($strippedPassword, "PasswordGenerator added symbol character(s) despite default use symbol option being false\n");
  }

  public function testPasswordGeneratedWithDefaultUseNumber() {
    $password = kjbpwg::generatePassword();
    $this->assertNonNumeric($password, "PasswordGenerator added numeric character(s) despite default use number option being false\n");
  }

  public function testPasswordGeneratedWithDefaultUseHyphens() {
    $password = kjbpwg::generatePassword();
    // Number of hyphens is always one less than number of words
    $this->assertEquals(kjbpwg::DEFAULT_WORD_COUNT - 1, substr_count($password, '-'), "PasswordGenerator did not add hyphen(s) despite default use hyphens option being true");
  }

  /**************************
   *** TEST USING OPTIONS ***
   **************************/

  //*** Test OPTION_WORD_COUNT values ***//
  public function testPasswordGeneratedWithNoWordCountOptionUsesDefaultValue() {
    $password = kjbpwg::generatePassword([]);
    $this->assertEquals(kjbpwg::DEFAULT_WORD_COUNT, $this->countWordsInHyphenatedPassword($password));
  }

  public function testPasswordGeneratedWithWordCountOptionValue() {
    $options = [kjbpwg::OPTION_WORD_COUNT => 5];
    $password = kjbpwg::generatePassword($options);
    $this->assertEquals(5, $this->countWordsInHyphenatedPassword($password));
  }

  public function testPasswordGeneratedWithNegativeWordCountOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_WORD_COUNT => -1];
    $password = kjbpwg::generatePassword($options);
    $this->assertEquals(kjbpwg::DEFAULT_WORD_COUNT, $this->countWordsInHyphenatedPassword($password));
  }

  public function testPasswordGeneratedWithStringWordCountOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_WORD_COUNT => 'cat'];
    $password = kjbpwg::generatePassword($options);
    $this->assertEquals(kjbpwg::DEFAULT_WORD_COUNT, $this->countWordsInHyphenatedPassword($password));
  }

  public function testPasswordGeneratedWithNullWordCountOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_WORD_COUNT => null];
    $password = kjbpwg::generatePassword($options);
    $this->assertEquals(kjbpwg::DEFAULT_WORD_COUNT, $this->countWordsInHyphenatedPassword($password));
  }

  //*** Test OPTION_USE_SYMBOL values ***//
  public function testPasswordGeneratedWithNoUseSymbolOptionUsesDefaultValue() {
    $password = kjbpwg::generatePassword([]);
    $strippedPassword = str_replace("-", "", $password);
    $this->assertAlphaNumeric($strippedPassword, "Password should not contain special symbols\n");
  }

  public function testPasswordGeneratedWithUseSymbolOptionValue() {
    $symbol = '#';
    $options = [kjbpwg::OPTION_USE_SYMBOL => $symbol];
    $password = kjbpwg::generatePassword($options);
    $this->assertNotEquals(0, substr_count($password, $symbol), "Password should contain {$symbol} symbol\n");
  }

  public function testPasswordGeneratedWithMultiCharacterUseSymbolOptionUsesFirstCharacter() {
    $multicharacterSymbol = "#$";
    $options = [kjbpwg::OPTION_USE_SYMBOL => $multicharacterSymbol];
    $password = kjbpwg::generatePassword($options);
  }

  public function testPasswordGeneratedWithTrueOptionUsesDefaultSymbolValue() {
    $options = [kjbpwg::OPTION_USE_SYMBOL => true];
    $password = kjbpwg::generatePassword($options);
    $this->assertNotEquals(0, substr_count($password, kjbpwg::DEFAULT_SYMBOL), "Password should contain {kjbpwg::DEFAULT_SYMBOL} symbol\n");
  }

  public function testPasswordGeneratedWithNumericUseSymbolOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_USE_SYMBOL => 7];
    $password = kjbpwg::generatePassword($options);
    $strippedPassword = str_replace("-", "", $password);
    $this->assertAlphaNumeric($strippedPassword, "Password should not contain special symbols\n");
  }

  public function testPasswordGeneratedWithNullUseSymbolOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_USE_SYMBOL => null];
    $password = kjbpwg::generatePassword($options);
    $strippedPassword = str_replace("-", "", $password);
    $this->assertAlphaNumeric($strippedPassword, "Password should not contain special symbols\n");
  }

  //*** Test OPTION_USE_NUMER values ***//
  public function testPasswordGeneratedWithNoUseNumberOptionUsesDefaultValue() {
    $password = kjbpwg::generatePassword([]);
    $this->assertNonNumeric($password, "Password should not contain numeric values\n");
  }

  public function testPasswordGeneratedWithUseNumberOptionValue() {
    $number = 4;
    $options = [kjbpwg::OPTION_USE_NUMBER => $number];
    $password = kjbpwg::generatePassword($options);
    $this->assertNotEquals(0, substr_count($password, strval($number)), "Password should contain {$number}\n");
  }

  public function testPasswordGeneratedWithTrueOptionAddsNumber() {
    $options = [kjbpwg::OPTION_USE_NUMBER => true];
    $password = kjbpwg::generatePassword($options);
    $this->assertContainsNumeric($password, "Password should contain a number\n");
  }

  public function testPasswordGeneratedWithStringUseNumberOptionUsesValue() {
    $number = '7';
    $options = [kjbpwg::OPTION_USE_NUMBER => $number];
    $password = kjbpwg::generatePassword($options);
    $this->assertNotEquals(0, substr_count($password, $number), "Password should contain {$number}\n");
  }

  public function testPasswordGeneratedWithNullUseNumberOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_USE_NUMBER => null];
    $password = kjbpwg::generatePassword($options);
    $this->assertNonNumeric($password, "Password should not contain numbers\n");
  }

  //*** Test OPTION_USE_HYPHENS values ***//
  public function testPasswordGeneratedWithNoUseHyphensOptionUsesDefaultValue() {
    $password = kjbpwg::generatePassword([]);
    $this->assertNotEquals(0, substr_count($password, '-'), "Password should contain hyphens\n");
  }

  public function testPasswordGeneratedWithUseHyphensOptionValue() {
    $options = [kjbpwg::OPTION_USE_HYPHENS => true];
    $password = kjbpwg::generatePassword($options);
    $this->assertNotEquals(0, substr_count($password, '-'), "Password should contain hyphens\n");
  }

  public function testPasswordGeneratedWithStringUseHyphensOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_USE_HYPHENS => 'cat'];
    $password = kjbpwg::generatePassword($options);
    $this->assertNotEquals(0, substr_count($password, '-'), "Password should contain hyphens\n");
  }

  public function testPasswordGeneratedWithNullUseHpyhensOptionUsesDefaultValue() {
    $options = [kjbpwg::OPTION_USE_HYPHENS => null];
    $password = kjbpwg::generatePassword($options);
    $this->assertNotEquals(0, substr_count($password, '-'), "Password should contain hyphens\n");
  }
}
