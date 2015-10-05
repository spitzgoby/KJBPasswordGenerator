<?php

class GeneratePasswordTest extends PHPUnit_Framework_TestCase {
  public function testPasswordCanBeGenerated() {
    echo "Testing password";
    $password = KJBPasswordGenerator\PasswordGenerator::generatePassword(null);
    echo "Finished generating pasword";
    echo $password;
    $this->assertNotNull($password);
  }
}
