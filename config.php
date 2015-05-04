<?php if ( !defined( 'SECURE' ) ) { exit; } else {
  // Set the default timezone
  date_default_timezone_set( 'America/Chicago' );





  /*------------------------------------*\
    CORS

    Set to your domain to prevent unauthorized access. If this isn't set then the API will be publicly accessible.
  \*------------------------------------*/

  // OPTIONAL
  // define( 'CORS_DOMAIN', '*', false );





  /*------------------------------------*\
    MongoDB connection info

    MDB_DATABASE is the only required variable here. The rest will either be omitted or use reasonable defaults if left unset.
  \*------------------------------------*/

  // REQUIRED
  define( 'MDB_DATABASE', 'test', false );

  // OPTIONAL
  // define( 'MDB_SERVER', 'localhost', false );
  // define( 'MDB_PORT', '27017', false );
  // define( 'MDB_AUTH', 'authenticationTable', false );
  // define( 'MDB_USERNAME', 'username', false );
  // define( 'MDB_PASSWORD', 'password', false );





  // API Data Arrays
  $externalApps = array(
    'facebook' => array(
      'key' => '',
      'secret' => ''
    ),
    'twitter' => array(
      'consumer key' => '',
      'consumer secret' => ''
    ),
    'youtube' => array(
      'key' => ''
    )
  );
}; ?>
