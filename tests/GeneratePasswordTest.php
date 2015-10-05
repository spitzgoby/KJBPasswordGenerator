<?php

class GeneratePasswordTest extends PHPUnit_Framework_TestCase {
  public function testPasswordCanBeGenerated() {
    $password = KJBPasswordGenerator\PasswordGenerator::generatePassword(null);
    echo "\nPassword: " .$password ."\n";
    $this->assertNotNull($password);
  }
}
