<?php

    $db = new mysqli("classdb.it.mtu.edu", "cjholmes", "buddy", "cjholmes");

    if ($db->connect_errno)
    {
?>
<script>
    console.log("Could not connect to Database");
</script>
<?php
    }
?>