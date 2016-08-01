<?php
/**
 * Function to localize our site
 * @param $site The Site object
 */
return function(Steampunked\Site $site) {

    // Set the time zone
    date_default_timezone_set('America/Detroit');

    $site->setEmail('barajas7@cse.msu.edu');
    $site->setRoot('/~barajas7/project3');
    $site->dbConfigure('mysql:host=mysql-user.cse.msu.edu;dbname=barajas7',
        'barajas7',       // Database user
        'cd32decd32de',     // Database password
        'p3_');            // Table prefix
};
