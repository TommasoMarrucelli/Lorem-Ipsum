<?php

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


print10($results);

$footer_start = ($page_number > 0) ? '<section id= "page_box"><input type = "hidden" id="highlight_page" name = "highlight_page" value= "'.$page_number.'"><button type = "submit" form = "search_form" name = "page_number" onclick = "change_page('.($page_number - 1).')">Prev</button>' : '<section id= "page_box"><input type = "hidden" id="highlight_page" name = "highlight_page" value= "'.$page_number.'">';
$footer_end = ($page_number < $total_pages) ? '<button type = "submit" form = "search_form" name = "page_number" onclick = "change_page('.($page_number + 1).')">Next</button></section>' : '</section>';

$pages = [];

array_push($pages, $footer_start);

//check which set of 10 pages is the current (ex. pag 0 to pag 9 is slot 0, pag 10 to pag 19 is slot 1)
$page_slot = (strlen((string)$page_number)) > 1 ? substr( $page_number, 0, ((strlen((string)$page_number)-1))) : 0;

for($x = 0; $x <= 9; $x++) {
    $prefix = ($page_slot == 0) ? '' : $page_slot;
    $page_cont = '<button type = "submit" form = "search_form" id = "p'.$prefix.$x.'" name = "page_number" onclick = "change_page('.$prefix.$x.')">'.($prefix.$x + 1).'</button>';

    array_push($pages, $page_cont);
    }

array_push($pages, $footer_end);

echo(implode(" ",$pages));

}


if($_POST['form_function'] == 'rate_book'){

    session_start();
    //connect to database
    include 'includes/pdo_connect.php';

    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $rating = $_POST['rating'];

    $stmt = $dbh->prepare("SELECT book_rate FROM library WHERE user_id = ? AND book_id = ? ");
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

    

   
}












   

function print10($results){

    
    for($x = 0; $x < 9; $x++){

        $book_id = $results['items'][$x]['id'];
        $title = check_title_lenght($results, $x);
        $thumbnail = check_thumbnail($results, $x);
        $authors_list = list_author($results, $x); 
        $published_date = check_published_date($results, $x);
        $page_count = check_page_count($results, $x);  
        $categories_list = list_category($results, $x);
        $language = $results['items'][$x]['volumeInfo']['language'];
        $description = check_description($results, $x);

        echo('<article id="'.$book_id.'" class="book_box" data-aos="zoom-in" data-aos-duration="1500"><section class="book_title">'.$title.'</section><section class="book_thumbnail"><img src = "'.$thumbnail.'" alt = "N/A"></section><section class="book_details"><table><tr><td>Authors:</td><td>'.$authors_list.'</td></tr><tr><td>Published Date:</td><td>'.$published_date.'</td></tr><tr><td>Pages:</td><td>'.$page_count.'</td></tr><tr><td>Categories:</td><td>'.$categories_list.'</td></tr><tr><td>Language:</td><td>'.$language.'</td></tr></table></section><section class = "book_description"><div class = "scroll_content">'.$description.'</div></section><section class="book_rating"><form class="like_form" id = "like_form_'.$book_id.'"><input class = "book_id" type="hidden" name="book_id" value = "'.$book_id.'"> <span class="material-icons help_tips">help</span><span id="like_tooltip_'.$book_id.'" class="rating_tooltip">Add to your favourites</span><div class="custom_check_cont"><input  id="check_'.$book_id.'" name = "check_'.$book_id.'" type= "checkbox" value = "liked" class = "like_btn"><label for = "check_'.$book_id.'" class = "like_checkbox""><span class="material-icons like_icon">favorite</span></label></div></form><form class="rating_form" id="rating_form_'.$book_id.'"><span id="star_tooltip" class="rating_tooltip">Your rating</span><input class = "book_id" type="hidden" name="book_id" value = "'.$book_id.'">
        
        <input type = "checkbox" class="star_check" id = "star_check_0_'.$book_id.'" name = "star_check_0" value = "0"><label for = "star_check_0"  class="star" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_1_'.$book_id.'" name = "star_check_1" value = "1"><label for = "star_check_1"  class="star" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_2_'.$book_id.'" name = "star_check_2" value = "2"><label for = "star_check_2"  class="star" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_3_'.$book_id.'" name = "star_check_3" value = "3"><label for = "star_check_3"  class="star" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>

          <input type = "checkbox" class="star_check" id = "star_check_4_'.$book_id.'" name = "star_check_4" value = "4"><label for = "star_check_4"  class="star" onmousemove = "fill_star(event, this);" onmouseout = "empty_star(this)" onclick = "clicked_star(event, this)"></label>
          

          </form></section></article>');
    }
}

function check_title_lenght($results, $index){
    $title = $results['items'][$index]['volumeInfo']['title'];
    $title_lenght = strlen($title);
    if($title_lenght > 45){
        $new_title = substr($title, 0, 45);
        $title = $new_title.'...<button class = "show_title_button" value = "'.$index.'" onclick = show_complete_title(this);><span class="material-icons">expand_more</span></button><div id = "title_'.$index.'" class="complete_title">'.$title.'</div>';
    }
    return $title;
}

function list_author($results, $index){
    $author_exist = array_key_exists('authors', $results['items'][$index]['volumeInfo']) ? true : false;

    $authors_list = ($author_exist) ? implode( ", ", $results['items'][$index]['volumeInfo']['authors']): "N/A";
    $authors_list = (empty($authors_list)) ? "N/A" : $authors_list;
    return $authors_list;
}

function list_category($results, $index){
    $category_exist = array_key_exists('categories', $results['items'][$index]['volumeInfo']) ? true : false;

    $category_list = ($category_exist) ? implode( ", ", $results['items'][$index]['volumeInfo']['categories']): "N/A";
    $category_list = (empty($category_list)) ? "N/A" : $category_list;
    return $category_list;
}
function check_description($results, $index){
    $description_exist = array_key_exists('description', $results['items'][$index]['volumeInfo']) ? true : false;
    $description = ($description_exist) ? $results['items'][$index]['volumeInfo']['description'] : false;

    if (!$description) {
        $serch_info_exist = array_key_exists('searchInfo', $results['items'][$index]) ? true : false;
        $description = ($serch_info_exist) ? $results['items'][$index]['searchInfo'] : false;
        if($description){
            $snippet_exist = array_key_exists('textSnippet', $results['items'][$index]['searchInfo']) ? true : false;
            $description = ($snippet_exist) ? $results['items'][$index]['searchInfo']['textSnippet'] : false;
        } 
    }

    if(!$description){
        $description = "N/A";
    }
    return $description;
}
function check_published_date($results, $index){
    $date_exist = array_key_exists('publishedDate', $results['items'][$index]['volumeInfo']) ? true : false;
    $date = ($date_exist) ? $results['items'][$index]['volumeInfo']['publishedDate'] : 'N/A';
    $new_date = strpos($date, "T") ? substr($date, 0, strpos($date, "T")) : $date;
    return $new_date;
}

function check_thumbnail($results, $index){
    $images_exist = array_key_exists('imageLinks', $results['items'][$index]['volumeInfo']) ? true : false;
    $thumbnail_exist = "";
    if ($images_exist == true) {
        $thumbnail_exist = array_key_exists('thumbnail', $results['items'][$index]['volumeInfo']['imageLinks']) ? true : false;
    }
    $thumbnail = ($thumbnail_exist) ? $results['items'][$index]['volumeInfo']['imageLinks']['thumbnail'] : '';
    return $thumbnail;
}

function check_page_count($results, $index){
    $page_count_exist = array_key_exists('pageCount', $results['items'][$index]['volumeInfo']) ? true : false;
    $page_count = ($page_count_exist) ? $results['items'][$index]['volumeInfo']['pageCount'] : 'N/A';
    return $page_count;
}

?>