<?php
session_start();
$search_for = "https://www.googleapis.com/books/v1/volumes?q=";
$search_parameters = "&maxResults=10&startIndex=";

$query = '';


if($_POST['form_function'] == 'search_book'){

$book_queried = filter_var(trim($_POST['book_query']), FILTER_SANITIZE_STRING);
$page_number = filter_var(trim($_POST['page_number']), FILTER_SANITIZE_NUMBER_INT);
$_SESSION['page_number'] = $page_number;
$start_index = ($page_number > 0) ? ($page_number * 10) : 0;

if(empty($book_queried)){
    return;
}
else{
    $query = $search_for.$book_queried.$search_parameters.$start_index;
}

$Google_reply = file_get_contents($query);

$results = json_decode($Google_reply, true);

$total_results = $results['totalItems'];

$total_pages = $total_results / 10;
$key = $results['items'];
print10($results, $key, $total_results);


create_footer($page_number, $total_pages);
}



if($_POST['form_function'] == 'search_liked'){

    $book_queried = filter_var(trim($_POST['book_query']), FILTER_SANITIZE_STRING);
    $page_number = filter_var(trim($_POST['page_number']), FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['page_number'] = $page_number;
    $start_index = ($page_number > 0) ? ($page_number * 10) : 0;
    $results = array();
    $total_results;

    
    if(empty($book_queried)){
        
        foreach ($_SESSION['records'] as $key => $value) {

            if ($value['book_like'] == 1) {

          
                $book_id = $key;
                $search_for = 'https://www.googleapis.com/books/v1/volumes/';
                $query = $search_for.$book_id;
                $Google_reply = file_get_contents($query);
                $result_book = json_decode($Google_reply, true);
                $results[] = $result_book;
            }
        }

        
    }
    else{
        $query = $search_for.$book_queried;

        $Google_reply = file_get_contents($query);
    
        $all_results = json_decode($Google_reply, true);

        for($x = 0; $x < min(10, $all_results['totalItems']) ; $x++){
            
            $id = $all_results['items'][$x]['id'];
            $user_know_book = check_if_known_book($id, $_SESSION['records']);
     
            If($user_know_book && $_SESSION['records'][$id]['book_like'] == 1){
                $results[] = $all_results['items'][$x];
            }
        }
    
    }
    
    $total_results = count($results);
    $total_pages = $total_results / 10;
    

    $key = $results;
    print10($results, $key, $total_results);
    
    create_footer($page_number, $total_pages);
}

if($_POST['form_function'] == 'search_rated'){

    $book_queried = filter_var(trim($_POST['book_query']), FILTER_SANITIZE_STRING);
    $page_number = filter_var(trim($_POST['page_number']), FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['page_number'] = $page_number;
    $start_index = ($page_number > 0) ? ($page_number * 10) : 0;
    $results = array();
    $total_results;

    
    if(empty($book_queried)){
        
        foreach ($_SESSION['records'] as $key => $value) {

            if (!empty($value['book_rate'])) {

          
                $book_id = $key;
                $search_for = 'https://www.googleapis.com/books/v1/volumes/';
                $query = $search_for.$book_id;
                $Google_reply = file_get_contents($query);
                $result_book = json_decode($Google_reply, true);
                $results[] = $result_book;
            }
        }

        
    }
    else{
        $query = $search_for.$book_queried;

        $Google_reply = file_get_contents($query);
    
        $all_results = json_decode($Google_reply, true);

        for($x = 0; $x < min(10, $all_results['totalItems']) ; $x++){
            
            $id = $all_results['items'][$x]['id'];
            $user_know_book = check_if_known_book($id, $_SESSION['records']);
     
            If($user_know_book && !empty($_SESSION['records'][$id]['book_rate'])){
                $results[] = $all_results['items'][$x];
            }
        }
    
    }
    
    $total_results = count($results);
    $total_pages = $total_results / 10;
    

    $key = $results;
    print10($results, $key, $total_results);
    
    create_footer($page_number, $total_pages);
}




if($_POST['form_function'] == 'rate_book'){

    //connect to database
    include 'includes/pdo_connect.php';

    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $rating = $_POST['rating'];

    $stmt = $dbh->prepare("SELECT * FROM library WHERE user_id = ? AND book_id = ? ");
            $stmt->bindParam( 1, $user_id);
            $stmt->bindParam( 2, $book_id);
            $stmt->execute();
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            

            if($stmt->rowCount() == 0){
                $stmt = $dbh->prepare("INSERT INTO library (user_id, book_id, book_rate) VALUES (:user_id, :book_id, :book_rate)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':book_id', $book_id);
                $stmt->bindParam(':book_rate', $rating);
                $stmt->execute();
            }
            if($stmt->rowCount() == 1){
                $stmt = $dbh->prepare("UPDATE library SET book_rate = :book_rate WHERE user_id = :user_id AND book_id = :book_id");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':book_id', $book_id);
                $stmt->bindParam(':book_rate', $rating);
                $stmt->execute();
            }
    
            update_rated_book_session($_SESSION["records"], $book_id, $rating);
    
    
}

if($_POST['form_function'] == 'like_book'){

    //connect to database
    include 'includes/pdo_connect.php';

    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $like = $_POST['like'];

    $stmt = $dbh->prepare("SELECT * FROM library WHERE user_id = ? AND book_id = ? ");
            $stmt->bindParam( 1, $user_id);
            $stmt->bindParam( 2, $book_id);
            $stmt->execute();
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 0){
                $stmt = $dbh->prepare("INSERT INTO library (user_id, book_id, book_like) VALUES (:user_id, :book_id, :book_like)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':book_id', $book_id);
                $stmt->bindParam(':book_like', $like);
                $stmt->execute();
            }
            if($stmt->rowCount() == 1){
                $stmt = $dbh->prepare("UPDATE library SET book_like = :book_like WHERE user_id = :user_id AND book_id = :book_id");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':book_id', $book_id);
                $stmt->bindParam(':book_like', $like);
                $stmt->execute();
            }
    
        update_liked_book_session($_SESSION["records"], $book_id, $like);
        

}

if($_POST['form_function'] == 'log_out'){
    session_destroy();
    
}

//displays ten results per page
function print10($results, $key, $results_count){

    for($x = 0; $x < min(10, $results_count) ; $x++){
        
        $prefix = $key[$x]['volumeInfo'];
        $book_id = $key[$x]['id'];
        $title = check_title_lenght($prefix['title'], $x);
        $thumbnail = check_thumbnail($prefix, $x);
        $authors_list = list_author($prefix); 
        $published_date = check_published_date($prefix);
        $page_count = check_page_count( $prefix);  
        $categories_list = list_category($prefix);
        $language = $prefix['language'];
        $description = check_description($prefix, $book_id);
        $user_know_book = check_if_known_book($book_id, $_SESSION['records']);

        print_results($book_id, $title, $thumbnail, $authors_list, $published_date, $page_count, $categories_list, $language, $description, $user_know_book);

    }
}



function print_results($book_id, $title, $thumbnail, $authors_list, $published_date, $page_count, $categories_list, $language, $description, $user_know_book){
    $star1 ="";
        $star2 ="";
        $star3 ="";
        $star4 ="";
        $star5 ="";
        

        $book_rate = (!empty($user_know_book["book_rate"])) ? $user_know_book["book_rate"] : "";

        switch ($book_rate) {

            case 0.5:
                $star1 = "half_clicked";
                break;
            case 1:
                $star1 = "full_clicked";
                break;
            case 1.5:
                $star1 = "full_clicked";
                $star2 = "half_clicked";
                break;
            case 2:
                $star1 = "full_clicked";
                $star2 = "full_clicked";
                break;
            case 2.5:
                $star1 = "full_clicked";
                $star2 = "full_clicked";
                $star3 = "half_clicked";
                break;
            case 3:
                $star1 = "full_clicked";
                $star2 = "full_clicked";
                $star3 = "full_clicked";
                break;
            case 3.5:
                $star1 = "full_clicked";
                $star2 = "full_clicked";
                $star3 = "full_clicked";
                $star4 = "half_clicked";
                break;
            case 4:
                $star1 = "full_clicked";
                $star2 = "full_clicked";
                $star3 = "full_clicked";
                $star4 = "full_clicked";
                break;
            case 4.5:
                $star1 = "full_clicked";
                $star2 = "full_clicked";
                $star3 = "full_clicked";
                $star4 = "full_clicked";
                $star5 = "half_clicked";
                break;
            case 5:
                $star1 = "full_clicked";
                $star2 = "full_clicked";
                $star3 = "full_clicked";
                $star4 = "full_clicked";
                $star5 = "full_clicked";
                break;
            
        }

        $book_like = ($user_know_book["book_like"] == 1) ? "liked" : "";
        
        echo('<article id="'.$book_id.'" class="book_box" data-aos="zoom-in" data-aos-duration="1500"><section class="book_title">'.$title.'</section><section class="book_thumbnail"><img src = "'.$thumbnail.'" alt = "N/A"></section><section class="book_details"><table><tr><td>Authors:</td><td>'.$authors_list.'</td></tr><tr><td>Published Date:</td><td>'.$published_date.'</td></tr><tr><td>Pages:</td><td>'.$page_count.'</td></tr><tr><td>Categories:</td><td>'.$categories_list.'</td></tr><tr><td>Language:</td><td>'.$language.'</td></tr></table></section><section class = "book_description"><div class = "scroll_content">'.$description.'</div></section><section class="book_rating">
        
        <form class="like_form" id = "like_form_'.$book_id.'"><input class = "book_id" type="hidden" name="book_id" value = "'.$book_id.'"> <span class="material-icons help_tips">help</span><span id="like_tooltip_'.$book_id.'" class="rating_tooltip">Add to your favourites</span><div class="custom_check_cont"><input onclick = "like_book(event, this)" id="check_'.$book_id.'" name = "check_'.$book_id.'" type= "checkbox" value = "liked" class = "like_btn '.$book_like.'"><label for = "check_'.$book_id.'" class = "like_checkbox"><span class="material-icons like_icon">favorite</span></label></div></form>
        
        <form class="rating_form" id="rating_form_'.$book_id.'"><span id="star_tooltip" class="rating_tooltip">Your rating</span><input class = "book_id" type="hidden" name="book_id" value = "'.$book_id.'">
        
        <input type = "checkbox" class="star_check" id = "star_check_0_'.$book_id.'" name = "star_check_0" value = "0"><label for = "star_check_0"  class="star '.$star1.'" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_1_'.$book_id.'" name = "star_check_1" value = "1"><label for = "star_check_1"  class="star '.$star2.'" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_2_'.$book_id.'" name = "star_check_2" value = "2"><label for = "star_check_2"  class="star '.$star3.'" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_3_'.$book_id.'" name = "star_check_3" value = "3"><label for = "star_check_3"  class="star '.$star4.'" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_4_'.$book_id.'" name = "star_check_4" value = "4"><label for = "star_check_4"  class="star '.$star5.'" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>
          

          </form></section></article>');
}

function check_title_lenght($title, $index){
    $title_lenght = strlen($title);
    if($title_lenght > 45){
        $new_title = substr($title, 0, 45);
        $title = $new_title.'...<button class = "show_title_button" value = "'.$index.'" onclick = show_complete_title(this);><span class="material-icons">expand_more</span></button><div id = "title_'.$index.'" class="complete_title">'.$title.'</div>';
    }
    return $title;
}

function list_author($category){
    $author_exist = array_key_exists('authors', $category) ? true : false;

    $authors_list = ($author_exist) ? implode( ", ", $category['authors']): "N/A";
    $authors_list = (empty($authors_list)) ? "N/A" : $authors_list;
    return $authors_list;
}

function list_category($category){
    $category_exist = array_key_exists('categories', $category) ? true : false;

    $category_list = ($category_exist) ? implode( ", ", $category['categories']): "N/A";
    $category_list = (empty($category_list)) ? "N/A" : strtok($category_list, "/");
    return $category_list;
}
function check_description($category, $book_id){
    $description_exist = array_key_exists('description', $category) ? true : false;
    $description = ($description_exist) ? $category['description'] : false;

    if(!$description){
        $description = "N/A";
    }
    return $description;
}


function check_published_date($category){
    $date_exist = array_key_exists('publishedDate', $category) ? true : false;
    $date = ($date_exist) ? $category['publishedDate'] : 'N/A';
    $new_date = strpos($date, "T") ? substr($date, 0, strpos($date, "T")) : $date;
    return $new_date;
}

function check_thumbnail($img, $index){
    $images_exist = array_key_exists('imageLinks', $img) ? true : false;
    $thumbnail_exist = "";
    if ($images_exist == true) {
        $thumbnail_exist = array_key_exists('thumbnail', $img['imageLinks']) ? true : false;
    }
    $thumbnail = ($thumbnail_exist) ? $img['imageLinks']['thumbnail'] : '';
    return $thumbnail;
}

function check_page_count($category){
    $page_count_exist = array_key_exists('pageCount', $category) ? true : false;
    $page_count = ($page_count_exist) ? $category['pageCount'] : 'N/A';
    return $page_count;
}

//creates the footer containing the results pagination 
function create_footer($page_number, $total_pages)
{
    $footer_start = ($page_number > 0) && ($total_pages > 1) ? '<section id= "page_box"><input type = "hidden" id="highlight_page" name = "highlight_page" value= "' . $page_number . '"><button type = "submit" form = "search_form" name = "page_number" onclick = "change_results_page(' . ($page_number - 1) . ')">Prev</button>' : '<section id= "page_box"><input type = "hidden" id="highlight_page" name = "highlight_page" value= "' . $page_number . '">';
    
    $footer_end = ($page_number < $total_pages) && ($total_pages > 1) ? '<button type = "submit" form = "search_form" name = "page_number" onclick = "change_results_page(' . ($page_number + 1) . ')">Next</button></section>' : '</section>';


    $pages = [];

    array_push($pages, $footer_start);

    //check which set of 10 pages is the current (ex. pag 0 to pag 9 is slot 0, pag 10 to pag 19 is slot 1)
    $page_slot = (strlen((string)$page_number)) > 1 ? substr($page_number, 0, ((strlen((string)$page_number) - 1))) : 0;

    for ($x = 0; $x < min(10, $total_pages); $x++) {
        $prefix = ($page_slot == 0) ? '' : $page_slot;
        $page_cont = '<button type = "submit" form = "search_form" id = "p' . $prefix . $x . '" name = "page_number" onclick = "change_results_page(' . $prefix . $x . ')">' . ($prefix . $x + 1) . '</button>';

        array_push($pages, $page_cont);
    }

    array_push($pages, $footer_end);

    echo (implode(" ", $pages));
}




function check_if_known_book($book_id, $favourites){
    
    if (array_key_exists($book_id, $favourites)) {
        return $favourites[$book_id];
    }
 }
 
 function update_liked_book_session($favourites, $book_id, $book_like){
    if (array_key_exists($book_id, $favourites)) {
        
        $_SESSION['records'][$book_id]["book_like"] = $book_like; 
    }
    else{
        $_SESSION['records'][$book_id]["book_like"] = $book_like;
        $_SESSION['records'][$book_id]["book_rate"] = "";

    }

 }

 function update_rated_book_session($favourites, $book_id, $book_rate){

    if (array_key_exists($book_id, $favourites)) {
        $_SESSION['records'][$book_id]["book_rate"] = $book_rate;
    }
    else{
        $_SESSION['records'][$book_id]["book_like"] = "";
        $_SESSION['records'][$book_id]["book_rate"] = $book_rate;
    }
 
 }

 
 


?>