<?php

require_once "./includes/header.php";

?>

<div class="textboxContainer">
    <input type="text" class="searchInput" placeholder="Search for something">
</div>

<div class="results"></div>

<script>
    $(function() {

        var username = '<?= $userLoggedIn ?>';
        var timer;

        $(".searchInput").keyup(function() {
            clearTimeout(timer);

            timer = setTimeout(function() {
                var val = $(".searchInput").val();

                if (val != "") {
                    $.get("ajax/getSearchResults.php", {
                        term: val,
                        username: username
                    }, function(data) {
                        $(".results").html(data);
                    });
                } else {
                    $(".results").html("");
                }

            }, 500);
        });

    });
</script>