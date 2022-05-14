<?php
    require_once(dirname(__FILE__) . '/auth.php');

    echo 'This text is a secret content. Only successfully authenticated users can see it. Put <code>?logout=1</code> in the end of URL to logout from the session.';
