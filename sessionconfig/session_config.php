<?php
// Set the session timeout to 15 minutes
ini_set('session.gc_maxlifetime', 900);

//ensure the session cookie lifetime matches the session timeout
ini_set('session.cookie_lifetime', 900);
session_start();

?>