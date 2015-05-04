<?php if ( !defined( 'SECURE' ) ) { exit; } else {
class Blogs extends Resource {
  public $collection = 'blogs';
  public $keyFields = array( 'title' );





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
    if ( isset( $this -> parameters['_id'] ) || isset( $this -> parameters['alias'] ) ) {
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
    $this -> parameters['alias'] = sanitizeString( $this -> parameters['title'] );

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
    $missingKeyFields = array();

    foreach ( $this -> keyFields as $keyField ) {
      if ( !isset ( $this -> parameters[$keyField] ) ) {
        $missingKeyFields[] = $keyField;
      };
    };

    print_r($missingKeyFields);

    if ( count ( $missingKeyFields ) > 0 ) {
      header('HTTP/1.1 400 Bad Request', true, 400);

      $isFirst = true;
      echo 'This request is missing the following required fields: ';
      foreach ( $missingKeyFields as $field ) {
        if ( !$isFirst ) {
          echo ', ';
        };

        echo $field;

        $isFirst = false;
      };

    } else {
      header('HTTP/1.1 204 No Content', true, 204);
      $this -> database -> remove( $this -> parameters );
    };
  }





  public function options () {}





  public function __construct ($parameters) {
    parent::__construct($parameters);
  }
}
}; ?>