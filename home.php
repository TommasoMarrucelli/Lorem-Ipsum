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
    <div id="main_cont">
        <div id="nav" data-aos="fade-right" data-aos-duration="1500">
            <a href="">1</a>
            <a href="">1</a>
            <a href="">1</a>
            <a href="">Hi <?php echo($_SESSION['username']);?>!</a>
</div>
        <main>
            <header id="search_box" data-aos="zoom-in" data-aos-duration="1500">
                <section id="search_box_msg">Find your Book!</section>   
                <form id="search_form" name="search_form" action="home1.1.php" method="POST">
                    <input type="hidden" name="form_function" value="search_book">
                    <input type="hidden" id= "page_number" name="page_number" value="0">
                    <input type="text" id="book_query" name="book_query">
                    <button type="submit" id="submit_book" name="submit_book">Submit<span></span><span></span><span></span><span></span></button>
                </form>
            </header>

            
        </main>

    </div>
    
    <script>
        AOS.init();
    </script>
    <script src="home.js"></script>
</body>
</html>