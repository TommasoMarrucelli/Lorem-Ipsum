<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" type="text/css" href="mark1_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@700&display=swap" rel="stylesheet">


    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>
    <div id="main_cont">

    
        <div id="main_box">
            <div id="left_box" data-aos="fade-right">
                <div id="big_logo_cont">
                <?php echo file_get_contents("img/logo_animated_light.svg"); ?>
                    
                </div>
                <div class="brand_name">LOREM <p>I P S U M</p></div>
                <div id="toggle_cont">
                    <p id="sign_msg">Don't have an account yet?</p>
                        <button id= "toggle_sign_log_btn" class="left_btn" onclick="toggle_sign_log();">Sign Up<span></span><span></span><span></span><span></span></button>
				</div>
            </div>
            
            <div id="ereaser"><span></span><span></span><span></span></div>


            <div id="right_box" data-aos="fade-left">
        
                <header class="to_log_in info_box_head">
                    <h1>Log In</h1>
                    <p>Please, enter your details below to continue.</p>    
                </header>
                <main class="to_log_in">
                    <form id="log_form" action="mark1.php" method="post">

                    <input type="hidden" name="form_name" class="form_name" value="to_log">

                        <div class="form_group" data-icon= "mail_outline">
                            <input type="text" id="log_uname_or_mail" name="log_uname_or_mail" class="cust_input" pattern="\S+.*" placeholder=" "  required>
                            <span class="border_focus">E-mail or Username</span>    
                        </div>
                        <div id="log_uname_or_mail_error_msg" class="error_msg"> </div>

                        <div class="form_group" data-icon= "vpn_key">
                            <input type="password" id="log_pwd" name="log_pwd" class="cust_input" pattern="\S+.*" placeholder=" "  required>
                            <span class="border_focus">Password</span>    
                        </div>
                        <div id="log_pwd_error_msg" class="error_msg"> </div>

                        <button id="log_in_btn" class="rigth_btn">Log In<span></span><span></span><span></span><span></span></button>
                        <div id="log_success_msg" class="success_msg"></div>

                        <div id="log_problem_msg" class="error_msg"></div>
                        <div id="log_success_msg" class="success_msg"></div>
                    </form>
                </main>
                <footer class="to_log_in">
                    <p class="or_continue"> Or </p>
					<button class="rigth_btn">Continue with Google<span></span><span></span><span></span><span></span></button>
					<button class="rigth_btn">Continue with Facebook<span></span><span></span><span></span><span></span></button>
                </footer>


                <header class="to_sign_up hide info_box_head">
                    <h1>Register</h1>
                    <p>Please, enter your details below to continue.</p>    
                </header>
                <main class="to_sign_up hide">
                    <form id="sign_form" name="sign_form" action="mark1.php" method="post">
                        
                    <input type="hidden" name="form_name" class="form_name" value="to_sign" >

                        <div class="form_group" data-icon= "person">
                            <input id="sign_username" name="sign_username" type="text" class="cust_input" pattern="\S+.*" placeholder=" "  required>
                            <span class="border_focus">Username</span>    
                        </div>
                        <div id="sign_username_error_msg" class="error_msg"> </div>


                        <div class="form_group" data-icon= "mail_outline">
                            <input id="sign_mail" name="sign_mail" type="email" class="cust_input" pattern="\S+.*" placeholder=" "  required>
                            <span class="border_focus">E-mail</span>    
                        </div>
                        <div id="sign_mail_error_msg" class="error_msg"></div>


                        <div class="form_group" data-icon= "vpn_key">
                            <input id="sign_pwd" name="sign_pwd" type="password" class="cust_input" pattern="\S+.*" placeholder=" "  required>
                            <span class="border_focus">Password</span>    
                        </div>
                        <div id="sign_pwd_error_msg" class="error_msg"></div>


                        <div class="form_group" data-icon= "lock">
                            <input id="sign_repeat_pwd" name="sign_repeat_pwd" type="password" class="cust_input" pattern="\S+.*" placeholder=" "  required>
                            <span class="border_focus">Confirm Password</span>    
                        </div>
                        <div  id="sign_repeat_pwd_error_msg" class="error_msg"></div>
                
                        <button type="submit" id="sign_up_btn" name="sign_up_btn" class="rigth_btn">Sign Up<span></span><span></span><span></span><span></span></button>
                        <div id="sign_problem_msg" class="error_msg"></div>
                        <div id="sign_success_msg" class="success_msg"></div>
                    </form>
                </main>

            </div>
        </div>

    </div>
    <script>
        AOS.init();
    </script>
    <script src="new_login.js"></script>
</body>
</html>