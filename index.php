<?php
    require __DIR__.'/vendor/autoload.php';
    session_start();

    require_once 'pages/includes/helpers.php';
    require_once 'pages/includes/head.html';
?>

<body>
    <?php require_once 'pages/includes/header.html'; ?>
    
    <?php
        
        // get the current page from the query string
        // if the page query string is not set, then use store page by default
        //  in case the home page is requested, then we default to store.html
        $file = $_GET["page"] ?? "store";
        
        if(requiresLogin($file) && !$isLoggedIn){//login if the file requires the user to be logged in
            header('Location: '.url('/?page=login'));
            die();
        }
        // check if the page requires login and if we are logged in
        else if(requiresLogin($file) && $isLoggedIn){
            $file = 'pages/'.$file.'.html';
            
            // check if the file exists, if it doesn't then redirect to the home page
            if(file_exists($file))
                require($file); //load the requested file
            else {

                header('Location: '.url('/'));
                die();
            }
        }
        else{// if the file does not require login
            if(isset($_GET['page'])){ // and the page query string has been set then load the requested page
                $file = 'pages/'.($_GET['page'] ?? "").'.html';

                // check first if the file exists, if not then redirect to the home page
                if(file_exists($file))
                    require($file);
                else {
                    header('Location: '.url('/'));
                    die();
                }
            }
        }
    ?>
</body>

</html>