<?php if ( !defined( 'SECURE' ) ) { exit; } else {
class Users extends Resource {
  public $collection = 'users';
  public $indexFields = array(
    '_id',
    'email'
  );





  public function get () {
    if ( count( array_intersect_key( $this -> parameters, $this -> indexFields ) ) > 0 ) {
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
    $resourceFound = false;

    if ( isset ( $this -> parameters['_id'] ) ) {
      if ( $result = $this -> findOne() ) {
        header( 'HTTP/1.1 204 No Content', true, 204 );
        $this -> remove();
        $resourceFound = true;
      };

    } else if ( isset ( $this -> parameters['id'] ) ) {
      foreach ( $this -> indexFields as $indexField ) {
        if ( $result = $this -> findOne( $indexField ) ) {
          header( 'HTTP/1.1 204 No Content', true, 204 );
          //$this -> remove();
          $resourceFound = true;
        };
      };

    } else {
      header( 'HTTP/1.1 400 Bad Request', true, 400 );
      echo 'An identifier must be provided. The following fields are accepted: ' . join( $this -> indexFields );
    };

    if ( ! $resourceFound ) {
      header( 'HTTP/1.1 404 Not Found', true, 404 );
    };
  }





  public function options () {}





  public function __construct ($parameters) {
    parent::__construct($parameters);
  }
}
}; ?>
