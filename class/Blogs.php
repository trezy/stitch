<?php if ( !defined( 'SECURE' ) ) { exit; } else {
class Blogs extends Resource {
  public $collection = 'blogs';
  public $keyFields = array( 'title' );





  public function hasDuplicates () {
    $duplicates = array();

    forEach( $this -> keyFields as $field ) {
      $thisField = $this -> parameters[$field];

      if ( isset( $this -> parameters[$field] ) && $this -> database -> findOne( array( $field => $thisField ) ) ) {
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





  public function put () {}





  public function delete () {}





  public function options () {}





  public function __construct ($parameters) {
    parent::__construct($parameters);
  }
}
}; ?>
