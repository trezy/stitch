<?php if ( !defined( 'SECURE' ) ) { exit; } else {
class Users extends Resource {
  public $collection = 'users';
  public $keyFields = array(
    '_id',
    'email'
  );





  public function get () {
    foreach ( $this -> keyFields as $keyField ) {
      if ( isset( $this -> parameters[$keyField] ) ) {
        $keyFieldSet = true;
      };
    };

    if ( count( array_intersect_key( $this -> parameters, $this -> keyFields ) ) > 0 ) {
      $results = $this -> findOne();

    } else {
      $results = $this -> find();
    };

    /*------------------------------------*\
      Success
    \*------------------------------------*/
    if ( isset( $results ) ) {
      header( 'HTTP/1.1 200 OK', true, 200 );
      $this -> printJSON( $results );

    /*------------------------------------*\
      Failure
    \*------------------------------------*/
    } else {
      header( 'HTTP/1.1 404 Not Found', true, 404 );
    };
  }





  public function post () {
    if ( $duplicateFields = $this -> hasDuplicates() ) {
      header( 'HTTP/1.1 409 Conflict', true, 409 );
      echo 'This is a duplicate entry. Please change the following fields: ' . join( $duplicateFields, ', ' );

    } else {
      header( 'HTTP/1.1 201 Created', true, 201 );
      $this -> save();
    };
  }





  public function put () {
    $this -> post();
  }





  public function delete () {
    if ( isset ( $this -> parameters['_id'] ) ) {
      $resourceFound = false;

      foreach ( $this -> keyFields as $keyField ) {
        if ( $result = $this -> findOne() ) {
          header( 'HTTP/1.1 204 No Content', true, 204 );
          $this -> remove();
          $resourceFound = true;
        };
      };

      if ( ! $resourceFound ) {
        header( 'HTTP/1.1 404 Not Found', true, 404 );
      };

    } else {
      header( 'HTTP/1.1 400 Bad Request', true, 400 );
      echo 'An identifier must be provided. The following fields are accepted: ' . join( $this -> keyFields );
    };
  }





  public function options () {}





  public function __construct ($parameters) {
    parent::__construct($parameters);
  }
}
}; ?>
