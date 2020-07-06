

function toggle_sign_log(){


    let right_box = document.querySelector('#left_box');
    let ereaser = document.querySelector('#ereaser');
    let log_in = document.getElementsByClassName('to_log_in');
    let sign_up = document.getElementsByClassName('to_sign_up');
    let duck = document.querySelector('#duck');
    let big_logo = document.querySelector('#big_logo');
    let toggle_btn = document.querySelector('#toggle_sign_log_btn');
    
    toggle_btn.setAttribute('disabled', 'true');
    
    let btn_text = toggle_btn.innerHTML;
    let new_text = (btn_text == "Log In <span></span><span></span><span></span><span></span>") ? "Sign Up <span></span><span></span><span></span><span></span>" : "Log In <span></span><span></span><span></span><span></span>";

    toggle_btn.innerHTML = new_text;

    right_box.classList.add('shrink');

    setTimeout(() => {
        ereaser.classList.add('erease');
        duck.classList.add('tumbling');
        big_logo.classList.add('logo_move');
        right_box.classList.remove('shrink');

     }, 300);

    setTimeout(() => {

        let log_count;
        let sign_count;

        for (log_count = 0; log_count < log_in.length; log_count++) {
            log_in[log_count].classList.toggle('hide');
          }
        for (sign_count = 0; sign_count < sign_up.length; sign_count++) {
            sign_up[sign_count].classList.toggle('hide');
          }
        
        
     }, 1000);

    setTimeout(() => {
       ereaser.classList.remove('erease');
       duck.classList.remove('tumbling');
       big_logo.classList.remove('logo_move');
       toggle_btn.removeAttribute('disabled');
 
    }, 2000);
    
    
}



