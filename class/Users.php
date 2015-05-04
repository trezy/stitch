<?php if ( !defined( 'SECURE' ) ) { exit; } else {
class Users extends Resource {
  public $collection = 'users';
  public $keyFields = array(
    '_id',
    'email'
  );





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





  public function get () {
    $keyFieldSet = false;

    foreach ( $this -> keyFields as $keyField ) {
      if ( isset( $this -> parameters[$keyField] ) ) {
        $keyFieldSet = true;
      };
    };

    if ( $keyFieldSet ) {
      $results = $this -> database -> findOne( $this -> parameters, $this -> fields );

    } else {
      $results = iterator_to_array( $this -> database -> find( $this -> parameters, $this -> fields ) );
    };

    /*------------------------------------*\
      Success
    \*------------------------------------*/
    if ( isset( $results ) ) {
      header('HTTP/1.1 200 OK', true, 200);
      $this -> printJSON( $results );

    /*------------------------------------*\
      Failure
    \*------------------------------------*/
    } else {
      header('HTTP/1.1 404 Not Found', true, 404);
    };
  }





  public function post () {
    if ( $duplicateFields = $this -> hasDuplicates() ) {
      header('HTTP/1.1 409 Conflict', true, 409);

      $isFirst = true;
      echo 'This is a duplicate entry. Please change the following fields: ';
      foreach ( $duplicateFields as $field ) {
        if ( !$isFirst ) {
          echo ', ';
        };

        echo $field;

        $isFirst = false;
      };

    } else {
      header('HTTP/1.1 201 Created', true, 201);
      $this -> database -> save( $this -> parameters );
    };
  }





  public function put () {
    $this -> post();
  }





  public function delete () {
    if ( isset ( $this -> parameters['_id'] ) ) {
      $resourceFound = false;

      foreach ( $this -> keyFields as $keyField ) {
        if ( $result = $this -> database -> findOne ( array( $keyField => $this -> parameters['_id'] ) ) ) {
          header('HTTP/1.1 204 No Content', true, 204);
          $this -> database -> remove( $this -> parameters );
          $resourceFound = true;
        };
      };

      if ( ! $resourceFound ) {
        header('HTTP/1.1 404 Not Found', true, 404);
      };

    } else {
      header('HTTP/1.1 400 Bad Request', true, 400);

      $isFirst = true;
      echo 'An identifier must be provided. The following fields are accepted: ';
      foreach ( $this -> keyFields as $keyField ) {
        if ( !$isFirst ) {
          echo ', ';
        };

        echo $keyField;

        $isFirst = false;
      };
    };
  }





  public function options () {}





  public function __construct ($parameters) {
    parent::__construct($parameters);
  }
}
}; ?>
