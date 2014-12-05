<?php

	namespace tests\tad\FunctionMocker;


	use tad\FunctionMocker\FunctionMocker;

	class FunctionMockerBatchReplaceTest extends \PHPUnit_Framework_TestCase {

		public function setUp() {
			FunctionMocker::setUp();
		}

		public function tearDown() {
			FunctionMocker::tearDown();
		}

		/**
		 * @test
		 * it should allow passing an array of function names and have them replaced the same way
		 */
		public function it_should_allow_passing_an_array_of_function_names_and_have_them_replaced_the_same_way() {
			$_functions = [ 'functionOne', 'functionTwo', 'functionThree' ];
			$functions = array_map( function ( $name ) {
				return __NAMESPACE__ . '\\' . $name;
			}, $_functions );

			FunctionMocker::replace( $functions, 'foo' );

			foreach ( $functions as $function ) {
				$this->assertEquals( 'foo', $function() );
			}

		}

		/**
		 * @test
		 * it should allow passing an array of non defined namespaced functions and have them batch replaced
		 */
		public function it_should_allow_passing_an_array_of_non_defined_namespaced_functions_and_have_them_batch_replaced() {
			$_functions = [ 'not_defined_one', 'not_defined_two', 'not_defined_three' ];
			$functions = array_map( function ( $name ) {
				return __NAMESPACE__ . '\\' . $name;
			}, $_functions );

			FunctionMocker::replace( $functions, 'foo' );

			foreach ( $functions as $function ) {
				$this->assertEquals( 'foo', $function() );
			}
		}

		/**
		 * @test
		 * it should allow passing an array of non defined non namespaced functions and have them replaced
		 */
		public function it_should_allow_passing_an_array_of_non_defined_non_namespaced_functions_and_have_them_replaced() {
			$functions = [ 'not_defined_one', 'not_defined_two', 'not_defined_three' ];

			FunctionMocker::replace( $functions, 'foo' );

			foreach ( $functions as $function ) {
				$this->assertEquals( 'foo', $function() );
			}
		}

		/**
		 * @test
		 * it should allow batch replacing static methods
		 */
		public function it_should_allow_batch_replacing_static_methods() {
			$_functions = [ 'staticOne', 'staticTwo', 'staticThree' ];
			$functions = array_map( function ( $name ) {
				return __NAMESPACE__ . '\\FooBazClass::' . $name;
			}, $_functions );

			FunctionMocker::replace( $functions, 'foo' );

			$this->assertEquals( 'foo', FooBazClass::staticOne() );
			$this->assertEquals( 'foo', FooBazClass::staticTwo() );
			$this->assertEquals( 'foo', FooBazClass::staticThree() );
		}

		/**
		 * @test
		 * it should allow batch replacement of instance methods
		 */
		public function it_should_allow_batch_replacement_of_instance_methods() {
			$_functions = [ 'instanceOne', 'instanceTwo', 'instanceThree' ];
			$functions = array_map( function ( $name ) {
				return __NAMESPACE__ . '\\BazClass::' . $name;
			}, $_functions );

			$replacement = FunctionMocker::replace( $functions, 'foo' );

			$this->assertEquals( 'foo', $replacement->instanceOne() );
			$this->assertEquals( 'foo', $replacement->instanceTwo() );
			$replacement->instanceTwo();
			$this->assertEquals( 'foo', $replacement->instanceThree() );

			$replacement->wasCalledOnce( 'instanceOne' );
			$replacement->wasCalledTimes( 2, 'instanceTwo' );
			$replacement->wasCalledOnce( 'instanceThree' );
		}

		/**
		 * @test
		 * it should allow getting an array of replaced functions to spy
		 */
		public function it_should_allow_getting_an_array_of_replaced_functions_to_spy() {
			$_functions = [ 'functionOne', 'functionTwo', 'functionThree' ];
			$functions = array_map( function ( $name ) {
				return __NAMESPACE__ . '\\' . $name;
			}, $_functions );

			$replacedFunctions = FunctionMocker::replace( $functions, 'foo' );

			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\FunctionCallVerifier', $replacedFunctions['functionOne'] );
			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\FunctionCallVerifier', $replacedFunctions['functionTwo'] );
			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\FunctionCallVerifier', $replacedFunctions['functionThree'] );
		}

		/**
		 * @test
		 * it should return an array of replaced static methods to spy
		 */
		public function it_should_return_an_array_of_replaced_static_methods_to_spy() {
			$_functions = [ 'staticOne', 'staticTwo', 'staticThree' ];
			$functions = array_map( function ( $name ) {
				return __NAMESPACE__ . '\\FooBazClass::' . $name;
			}, $_functions );

			$replacedFunctions = FunctionMocker::replace( $functions, 'foo' );

			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\StaticMethodCallVerifier', $replacedFunctions['staticOne'] );
			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\StaticMethodCallVerifier', $replacedFunctions['staticTwo'] );
			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\StaticMethodCallVerifier', $replacedFunctions['staticThree'] );
		}

		/**
		 * @test
		 * it should allow getting a function name indexed list of functions when batch replacing
		 */
		public function it_should_allow_getting_a_function_name_indexed_list_of_functions_when_batch_replacing() {
			$_functions = [ 'functionOne', 'functionTwo', 'functionThree' ];
			$functions = array_map( function ( $name ) {
				return __NAMESPACE__ . '\\' . $name;
			}, $_functions );

			$replacedFunctions = FunctionMocker::replace( $functions, 'foo' );

			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\FunctionCallVerifier', $replacedFunctions['functionOne'] );
			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\FunctionCallVerifier', $replacedFunctions['functionTwo'] );
			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\FunctionCallVerifier', $replacedFunctions['functionThree'] );
		}
	}


	function functionOne() {
		return 1;
	}

	function functionTwo() {
		return 2;
	}

	function functionThree() {
		return 3;
	}


	class BazClass {

		function instanceOne() {

		}

		function instanceTwo() {

		}

		function instanceThree() {

		}
	}


	class FooBazClass {

		static function staticOne() {

		}

		static function staticTwo() {

		}

		static function staticThree() {

		}

	}
