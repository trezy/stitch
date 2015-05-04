<?php if ( !defined( 'SECURE' ) ) { exit; } else {
  /*------------------------------------*\
    Called when attempting to load
    classes, allows us to ensure the
    class file will only be loaded when
    necessary.
  \*------------------------------------*/
  function __autoload( $class ) {
    $filename = 'class/' . $class . '.php';
    if ( file_exists( $filename ) ) {
      include_once( $filename );
    };
  };

  /*------------------------------------*\
    TODO: Describe function.
  \*------------------------------------*/
  function array_sort ( $array, $on, $order=SORT_ASC ) {
    $new_array = array();
    $sortable_array = array();

    if ( count( $array ) > 0 ) {
      foreach ( $array as $key => $value ) {
        if ( is_array( $value ) ) {
          foreach ( $value as $key2 => $value2 ) {
            if ( $key2 == $on ) {
              $sortable_array[$key] = $value2;
            };
          };
        } else { $sortable_array[$key] = $value; };
      };

      switch ( $order ) {
        case SORT_ASC:
          asort( $sortable_array );
          break;

        case SORT_DESC:
          arsort( $sortable_array );
          break;
      };

      foreach ( $sortable_array as $key => $value ) {
        $new_array[$key] = $array[$key];
      };
    };

    return $new_array;
  };

  /*------------------------------------*\
    Convert a string of key/value pairs
    with a single delimiter into an
    appropriately keyed array.
  \*------------------------------------*/
  function createKeyValuePairsFromString ( $string, $delimiter ) {
    $array = explode( $delimiter, $string );
    $chunkedArray = array_chunk( $array, 2 );

    foreach ( $chunkedArray as $array ) {
      $newArray[$array[0]] = $array[1];
    };

    return $newArray;
  };

  /*------------------------------------*\
    TODO: Describe function.
  \*------------------------------------*/
  function getResources ( $resourceDatabase, $filters ) {
    if ( $arguments[2] ) {
      if ( $resourceDatabase -> find( array( 'alias' ) ) -> count() > 0 ) {
        $filters['alias'] = $resourceNameOrId;
      } else if ( $_id = new MongoId( $resourceNameOrId ) && $resourceDatabase -> find( array( '_id' ) ) -> count() > 0 ) {
        $filters['_id'] = $resourceNameOrId;
      };
    };

    $fields = array(
      '_id',
      'alias',
      'datePublished',
      'content',
      'title',
      'status'
    );

    if ( $resources = $resourceDatabase -> find( $filters, $fields ) ) {
      return $resources;
    } else {
      return 'Resource not found.';
    };
  };

  /*------------------------------------*\
    TODO: Describe function.
  \*------------------------------------*/
  function formatDate ( $date ) {
    $format = 'j F Y';
    $dateArr = explode( ' ', date( $format, $date ) );
    $date =
      '<span class="date">' .
      '<span class="day">' . $dateArr[0] . '</span>' .
      '<span class="month">' . $dateArr[1] . '</span>' .
      '<span class="year">' . $dateArr[2] . '</span>' .
      '</span>';

    return $date;
  };
}; ?>
