
let search_form = document.getElementById('search_form');

search_form.addEventListener('submit', function(e){

    e.preventDefault();

    delete_previous_results();

    

    let formData = new FormData(this);
   document.getElementById('loading_img_box').style.display = "flex";    


    fetch('home1.1.php', {
        method : 'post',
        body : formData
    }).then(function (response){
        return response.text();
    }).then(function(text){
        
        result = text;
        
       
        document.querySelector('main').insertAdjacentHTML( 'beforeend', text );

        highlight_page();
        document.getElementById('loading_img_box').style.display = "none";
        show_tooltip();

    }).catch(function(error){
        console.log(error);
    })
})



function delete_previous_results(){

    let previous_results = document.querySelectorAll('.book_box');
    let previous_pages = document.querySelector('#page_box');

    for (i = 0; i < previous_results.length; i++) {
        previous_results[i].remove();
      }
    
      if(previous_pages){previous_pages.remove();}
}





function change_page(page_number){
    document.getElementById('page_number').setAttribute("value", page_number);
}


function highlight_page(){
   let page_to_highlight= document.querySelector('#highlight_page').getAttribute("value");

   let page = document.getElementById("p"+page_to_highlight);
   
   page.classList.add("highlight_page");
}


function show_complete_title(button){

   let title_box_id = 'title_'+ button.value;

   let title_box = document.getElementById(title_box_id);

   title_box.classList.add("show_title");
   
   setTimeout(function(){window.addEventListener('click', function hide_title(e){
    if (!title_box.contains(e.target)){
        title_box.classList.remove("show_title");
        this.removeEventListener('click', hide_title);
    }

  });}, 100);
}

function what_side_star(e, element){

        let mouseSide;
        let rect = e.target.getBoundingClientRect();
        let click_x = e.clientX - rect.left;
        let element_widht = element.offsetWidth;

        if (click_x < element_widht / 2) {
            mouseSide = 'left';
        } else {
            mouseSide = 'right';
        }  
        
        return mouseSide;
    }

function fill_star(e, element){

    let all_stars = element.parentNode.querySelectorAll("label");

    for (i = 0; i < element.previousSibling.value; i++){

        if(all_stars[i].classList.contains("half_clicked")){

            all_stars[i].classList.add("hovered");
        }
            all_stars[i].classList.remove("half");
            all_stars[i].classList.add("full");   
    }

    let mouseSide = what_side_star(e, element);
    if(!element.classList.contains("half_clicked") && !element.classList.contains("full_clicked")){
        if(mouseSide == 'left'){
            element.classList.contains('half') ? element.classList.remove('full') : element.classList.add('half');
        }
        if(mouseSide == 'right'){
            element.classList.contains('full') ? element.classList.remove('half') : element.classList.add('full');
        }
    }
    if(element.classList.contains("half_clicked")){
        element.classList.add('hovered');
    }

}
function empty_star(element){

    let all_stars = element.parentNode.querySelectorAll("label");

    for (i = 0; i < element.previousSibling.value; i++){
        all_stars[i].classList.remove("full");
        all_stars[i].classList.remove("half");
        all_stars[i].classList.remove('hovered');
    }
    
        element.classList.remove('full');
        element.classList.remove('half');
        element.classList.remove('hovered');
}

function clicked_star(event, element){

    
    let all_stars = element.parentNode.querySelectorAll("label");
    let star_value = element.previousSibling.value;
    let all_book_id = element.parentNode.querySelectorAll('.book_id');
    let book_id = all_book_id[0].value;

    for(i = 0; i < all_stars.length; i++){
        all_stars[i].classList.remove("full");
        all_stars[i].classList.remove("half");
        all_stars[i].classList.remove("full_clicked");
        all_stars[i].classList.remove("half_clicked");
    }
    
    for (i = 0; i < star_value; i++){
        all_stars[i].classList.add("full_clicked");
    }

    

    let mouseSide = what_side_star(event, element);
    
    if(mouseSide == 'left'){
        element.classList.contains('half_clicked') ? element.classList.remove('full_clicked') : element.classList.add('half_clicked');
        star_value = parseInt(star_value) + 0.5;
    }
    if(mouseSide == 'right'){
        element.classList.contains('full_clicked') ? element.classList.remove('half_clicked') : element.classList.add('full_clicked');
        star_value = parseInt(star_value) + 1;
    }

    event.preventDefault();


    let formData = new FormData();

    formData.append("form_function", 'rate_book');
    formData.append("rating", star_value);
    formData.append("book_id", book_id);

    

    fetch('home1.1.php', {
        method : 'post',
        body : formData
    }).then(function (response){
        return response.text();
    }).then(function(text){
        
        console.log(text);

    }).catch(function(error){
        console.log(error);
    })


}

function like_book(event, element){

    let checkbox_id = element.id;
    let book_id = checkbox_id.substring(6);
    
    element.classList.toggle("liked");

    let like = (element.classList.contains("liked")) ? 1 : 0;

    event.preventDefault();


    let formData = new FormData();

    formData.append("form_function", 'like_book');
    formData.append("like", like);
    formData.append("book_id", book_id);

    
    fetch('home1.1.php', {
        method : 'post',
        body : formData
    }).then(function (response){
        return response.text();
    }).then(function(text){
        
        console.log(text);

    }).catch(function(error){
        console.log(error);
    })


}

function show_tooltip(){
   let help_btn =  document.querySelectorAll('.help_tips');
   

   for (x = 0; x < help_btn.length; x++){

    let this_book_box = help_btn[x].closest(".book_rating");
    let tooltips = this_book_box.querySelectorAll('.rating_tooltip');

        help_btn[x].addEventListener('mouseover', function(e){
            for (i = 0; i < tooltips.length; i++){
                tooltips[i].classList.add("show_tooltip");
            }
        })
        help_btn[x].addEventListener('mouseout', function(e){
            for (i = 0; i < tooltips.length; i++){
                tooltips[i].classList.remove("show_tooltip");
        }
        })
    }
}
