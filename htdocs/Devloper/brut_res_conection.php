<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="\css\style_main_login.css">
    <title>CARES 3.0</title>

    <style>

        .texte{
            color: rgba(255,0,0,0.6);
            background: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            height: 120px;
   
        }
    </style>

</head>
<body>
 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <img src="/css/pictures/logo/cares_menu.png" >
        </div>
        <div class="nav-menu" id="navMenu">
            
            <ul>
                <li><a href="http://localhost:8083/" class="link active">Home</a></li>
                <li><a href="#about" class="link">About</a></li>
                <li><a href="#" class="link"></a></li>
                <li><a href="#" class="link"></a></li>
                <li><a href="#" class="link"></a></li>
            
            </ul>
        
        </div>
        <div class="nav-button">
            <button class="btn white-btn" id="loginBtn" onclick="login()">Sign In</button>
            
        </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
    </nav>

<!----------------------------- Form box ----------------------------------->    
    <div class="form-box active"> 
        
        <!------------------- login form -------------------------->
        <div class="login-container" id="login">
            <div class="top">
                <br>
                <header class="texte">Brute Resident Login <br> Interface pour teste </header>
                <br>
                <br>
             
            </div>
            <form id="login-form" action="brut_login.php" method="post">
                
                    
                    <div class="input-box">
                        <input type="text" class="input-field" name="username_or_email" placeholder="Username or Email">
                        <i class="bx bx-user"></i>
                    </div>
                    
                    <div class="input-box">
                        <input type="submit" class="submit" value="Sign In">
                    </div>
                    
               
            </form>
        </div>
        
        

    </div>
</div>   

<div style="background-color: rgba(39, 39, 39, 0.4); color:white;">
<div id="about" >
    <h2>About Us</h2>
    <p>Carnet du résident version Profiling</p>
</div>

<div id="contact" class="contact-section">
    <h2>Contact Us</h2>
    <p>Tel: +213697157947</p>
    <p>Mail: ouadkarima@outlook.com</p>

</div>
<br>
<center>
<footer>
    <p>Ouadah Karima - Copyright © 2024. All rights reserved.</p>
</footer>
</center>
</div>

<script>
   
   function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if(i.className === "nav-menu") {
        i.className += " responsive";
    } else {
        i.className = "nav-menu";
    }
   }
 
</script>

<script>

    var a = document.getElementById("loginBtn");
   
    var x = document.getElementById("login");


    function login() {
        x.style.left = "4px";
       // y.style.right = "-520px";
        a.className += " white-btn";
        //b.className = "btn";
        x.style.opacity = 1;
        //y.style.opacity = 0;
        document.querySelector(".form-box").classList.add("active"); // Ajout de la classe active
    }

    
</script>

</body>
</html>
