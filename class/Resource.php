<?php if ( !defined( 'SECURE' ) ) { exit; } else {
abstract class Resource {
  protected static $httpMethods = array( 'GET', 'POST', 'HEAD', 'PUT', 'OPTIONS', 'DELETE', 'TRACE', 'CONNECT' );
  protected $database;
  protected $parameters;

  public $collection;
  public $fields = array();
  public $keyFields = array();





  public function __call ( $method, $arguments ) {
    header( 'HTTP/1.1 405 Method Not Allowed', true, 405 );
    header( 'Allow: ' . join($this->allowedHttpMethods(), ', ' ) );
  }





  public function __construct ( array $parameters ) {
    $this -> parameters = $parameters;
    $this -> database = $this -> connect();
  }





  protected function allowedHttpMethods () {
    $allowedMethods = array();
    $reflection = new ReflectionClass( $this );

    foreach ( $reflection -> getMethods( ReflectionMethod::IS_PUBLIC ) as $reflectionMethod ) {
      $myMethods[] = strtoupper( $rm -> name );
    };

    return array_intersect( self::$httpMethods, $allowedMethods );
  }





  protected function connect () {
    $mongo = 'mongodb://';

    if ( defined( 'MDB_USERNAME' ) ) {
      $mongo = $mongo . MDB_USERNAME . ':' . MDB_PASSWORD . '@';
    };

    if ( defined( 'MDB_SERVER' ) ) {
      $mongo = $mongo . MDB_SERVER;
    } else {
      $mongo = $mongo . 'localhost';
    };

    if ( defined( 'MDB_PORT' ) ) {
      $mongo = $mongo . ':' . MDB_PORT;
    };

    if ( defined( 'MDB_AUTH' ) ) {
      $mongo = $mongo . '/' . MDB_AUTH;
    };

    $mongo = new Mongo( $mongo );
    return $mongo -> {MDB_DATABASE} -> {$this -> collection};
  }





  protected function find () {
    return iterator_to_array( $this -> database -> find( $this -> parameters ) );
  }





  protected function findOne () {
    return $this -> database -> findOne( $this -> parameters );
  }





  public function hasDuplicates () {
    $duplicates = array();

    forEach( $this -> keyFields as $field ) {
      if ( isset( $this -> parameters[$field] ) && $this -> database -> findOne( array( $field => $this -> parameters[$field] ) ) ) {
        $duplicates[] = $field;
      };
    };

    if ( !count ( $duplicates ) ) {
      $duplicates = false;
    };

    return $duplicates;
  }





  protected function printJSON ( $results ) {
    header('Content-Type: application/json');
    echo json_encode( $results );
  }





  protected function remove () {
    return $this -> database -> remove( $this -> parameters );
  }





  protected function save () {
    return $this -> database -> save( $this -> parameters );
  }





  protected function update () {
    return $this -> save();
  }
}
}; ?>
