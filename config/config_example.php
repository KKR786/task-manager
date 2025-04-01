<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');

// Facebook API Configuration
define('FB_PAGE_ID', 'your_facebook_page_id');
define('FB_GRAPH_PAGE_FEED_API', 'https://graph.facebook.com/v22.0/' . FB_PAGE_ID . '/feed');
define('FB_APP_ID', 'your_facebook_app_id');
define('FB_ACCESS_TOKEN', 'your_facebook_access_token');
define('FB_APP_SECRET', 'your_facebook_app_secret');

// Google API Configuration
define('GOOGLE_CLIENT_ID', 'your_google_client_id');
define('GOOGLE_CLIENT_SECRET', 'your_google_client_secret');
define('GOOGLE_REDIRECT_URI', 'http://yourdomain.com/oauth_callback.php');

// Base URL
define('BASE_URL', 'http://yourdomain.com');
?>
