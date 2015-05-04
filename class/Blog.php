<?php if ( !defined( 'SECURE' ) ) { exit; } else {
class Blog extends Resource {
  public $collection = 'blogs';

  public function get () {
    if ( isset( $resourceIdentifier ) ) {
      $results = $this -> database -> findOne( $this -> parameters, $this -> fields );
    } else {
      $results = $this -> database -> find( $this -> parameters, $this -> fields );
    };

    /*------------------------------------*\
      Success
    \*------------------------------------*/
    if ( isset( $results ) ) {
      header('HTTP/1.1 200 OK', true, 200);
      $this -> printJSON( iterator_to_array( $results ) );

    /*------------------------------------*\
      Failure
    \*------------------------------------*/
    } else {
      header('HTTP/1.1 404 Not Found', true, 404);
    };
  }

  public function post () {}

  public function put () {}

  public function delete () {}

  public function options () {}

  public function __construct ($parameters) {
    parent::__construct($parameters);
  }
}
}; ?>
