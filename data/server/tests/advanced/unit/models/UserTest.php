<?php
require_once dirname(__FILE__).'/../../../../advanced/models/Messages.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/User.class.php';

class UserTest extends PHPUnit_Framework_TestCase {

	public function testValidUserCreate() {
		$validTest = array ('user_id' => 1,
				'username' => 'mwatney',
				'password' => 'v4l1dP@ssW0rd!'
		);
		$validUser = new User($validTest);
		
		$this->assertTrue(is_a($validUser, 'User'),
				'It should create a User object when valid input is provided');
		$this->assertEquals(0, $validUser->getErrorCount(),
				'The User object should be error-free');
	}
	
	public function testInvalidUsernameBadChar() {
		$invalidTest = array ('user_id' => 1,
				'username' => 'nope`',
				'password' => 'v4l1dP@ssW0rd!'
		);
		$invalidUser = new User($invalidTest);
		
		$this->assertEquals(1, $invalidUser->getErrorCount(),
				'The User object should have exactly 1 error');
		$this->assertTrue(!empty($invalidUser->getError('username')),
				'The User should have a username error');
	}
	
	public function testInvalidUsernameTooShort() {
		$invalidTest = array ('user_id' => 1,
				'username' => 'nope',
				'password' => 'v4l1dP@ssW0rd!'
		);
		$invalidUser = new User($invalidTest);
		
		$this->assertEquals(1, $invalidUser->getErrorCount(),
				'The User object should have exactly 1 error');
		$this->assertTrue(!empty($invalidUser->getError('username')),
				'The User should have a username error');
	}
	
	public function testInvalidUsernameTooLong() {
		$invalidTest = array ('user_id' => 1,
				'username' => 'nopenopenopenopenope',
				'password' => 'v4l1dP@ssW0rd!'
		);
		$invalidUser = new User($invalidTest);
		
		$this->assertEquals(1, $invalidUser->getErrorCount(),
				'The User object should have exactly 1 error');
		$this->assertTrue(!empty($invalidUser->getError('username')),
				'The User should have a username error');
	}
	
	public function testInvalidPasswordBadChar() {
		$invalidTest = array ('user_id' => 1,
				'username' => 'mwatney',
				'password' => 'in`v4l1dP@ssW0rd!'
		);
		$invalidUser = new User($invalidTest);
		
		$this->assertEquals(1, $invalidUser->getErrorCount(),
				'The User object should have exactly 1 error');
		$this->assertTrue(!empty($invalidUser->getError('password')),
				'The User should have a password error');
	}
	
	public function testInvalidPasswordTooShort() {
		$invalidTest = array ('user_id' => 1,
				'username' => 'mwatney',
				'password' => 'tootiny'
		);
		$invalidUser = new User($invalidTest);
		
		$this->assertEquals(1, $invalidUser->getErrorCount(),
				'The User object should have exactly 1 error');
		$this->assertTrue(!empty($invalidUser->getError('password')),
				'The User should have a password error');
	}
	
	public function testInvalidPasswordTooLong() {
		$invalidTest = array ('user_id' => 1,
				'username' => 'mwatney',
				'password' => 'thisisasuperlongpasswordthatshouldfail'
		);
		$invalidUser = new User($invalidTest);
		
		$this->assertEquals(1, $invalidUser->getErrorCount(),
				'The User object should have exactly 1 error');
		$this->assertTrue(!empty($invalidUser->getError('password')),
				'The User should have a password error');
	}
}
?>
