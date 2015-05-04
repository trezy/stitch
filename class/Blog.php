<?php if ( !defined( 'SECURE' ) ) { exit; } else {
class Blog extends Resource {
  public $collection = 'blog';

  public $fields = array(
    'alias',
    'datePublished',
    'content',
    'title',
    'status'
  );

  public function get () {
    // Check to see if the requested resource exists
    if ( $this -> database -> find( $this -> parameters ) -> count() > 0 ) {

      if ( $resources = $this -> database -> find( $this -> parameters, $this -> fields ) ) {
        $resources = iterator_to_array( $resources );

        if ($resourceIdentifier === 'all') {
          $resources = array_pop($resources);
        };

        switch ($resourceType) {
          case 'json':
          default:
            header('Content-Type: application/json');
            echo json_encode($resources);
            break;
        };

      } else {
        header('HTTP/1.1 404 Not Found', true, 404);
      };

    } else {
      header('HTTP/1.1 404 Not Found', true, 404);
    };
  }

  public function post () {
    /*------------------------------------*\
      Success
    \*------------------------------------*/
    header('HTTP/1.1 200 Created', true, 200);
  }

  public function put () {}

  public function delete () {}

  public function options () {}

  public function __construct ($parameters) {
    parent::__construct($parameters);
  }
}
}; ?>
