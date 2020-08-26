<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lorem Ipsum Books</title>

    <link rel="stylesheet" type="text/css" href="home_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@700&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>

<body>
<div id="loading_img_box"><div id="loading_img"></div></div>
    <div id="main_cont">
        <div id="nav" data-aos="fade-right" data-aos-duration="1500">
            <div class="page_link" id="home_page_link"><span class="material-icons">home</span><span class="page_name">Home</span></div>
            <div class="page_link" id="liked_page_link"><span class="material-icons">favorite</span><span class="page_name">Books You Like</span></div>
            <div class="page_link" id="rated_page_link"><span class="material-icons">grade</span><span class="page_name">Books You Rated</span></div>
            <div class="page_link" id="log_out_link"><span class="material-icons hello_hand">pan_tool</span ><span id="log_out">Hi <?php echo ($_SESSION['username']); ?>! </span><span class="page_name">Log Out</span></div>
        </div>
        <main id="home_page">
            <header id="search_box" data-aos="zoom-in" data-aos-duration="1500">
                <section id="search_box_msg">Find a Book!</section>
                <form id="search_form" name="search_form" action="home1.1.php" method="POST">
                    <input type="hidden" id="form_function" name="form_function" value="search_book">
                    <input type="hidden" id="page_number" name="page_number" value="0">
                    <input type="text" id="book_query" name="book_query">
                    <button type="submit" id="submit_book" name="submit_book" onclick = "change_results_page(0)">Submit<span></span><span></span><span></span><span></span></button>
                </form>
            </header>
        </main>
        
    </div>
    <script src="home.js"></script>
    <script>
        set_page_navigation();
        listen_to_search();
    </script>
    <script>
        AOS.init();
    </script>
</body>

</html>