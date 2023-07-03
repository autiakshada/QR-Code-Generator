<?php
session_start();
session_destroy();
?>
<script>
alert("You have been successfully logged out.");
window.location.href = "login2-index.html";
</script>
