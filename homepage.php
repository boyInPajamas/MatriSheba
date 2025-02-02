<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css"
    >

    <style>
        *
        {
            margin: 0px;
        }

        nav
        {
            background-color: transparent;
        }

        main
        {
            background-color: #FFFDF0;
            /* background-image: url('img/background02.jpg'); */
        }

        .mainSection 
        {
            position: relative;
        }
        
        .centerTitle
        {
            font-size: 15rem;
            text-shadow: 
                2px 2px 10px rgba(0, 0, 0, 0.068),  /* Bottom-right shadow */
                -2px 2px 10px rgba(0, 0, 0, 0.075), /* Bottom-left shadow */
                2px -2px 10px rgba(0, 0, 0, 0.075), /* Top-right shadow */
                -2px -2px 10px rgba(0, 0, 0, 0.082);
        }

        .cTitle
        {
            font-size: 7rem;
            font-weight: bold;
            color: #8f9acc;
        }

        .subtitleGang
        {
            color: #657bdb;
        }
        
        .sky
        {
            width: 100vw;
            height: 70vh;
            object-fit: cover;
            border-radius: 0px 0px 70px 70px;
            display: block;
        }

        .overlay 
        {
            position: absolute; 
            top: 0;
            left: 0;
            width: 100%; 
            height: 100%; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            /* background: rgba(0, 0, 0, 0.4);  */
        }

        .contain
        {
            width: 100vw;
            height: 80vh;
            /* background-color: aquamarine; */
            margin: 0px;
            /* border-radius: 15px;
            border: 5px rgb(207, 164, 135) solid; */
        }

        .grid,.cell
        {
            height: 100%;
        }

        .imgWithContain
        {
            width: 60%;
            height: 90%;
            border-radius: 30px;
            object-fit: cover;
        }

        .imgWithContainContain:nth-last-child(1)
        {
            width: 80%;
        }

        .borderGang
        {
            border: 15px rgb(255, 255, 255) solid;
            box-shadow: rgba(0,0,0, 0.2) 0px 0px 10px;
        }

        .learnMore
        {
            width: 15%;
            color: #657bdb;
            font-size: 1.25rem;
            border-radius: 35px;
            padding: 20px 80px;
            border: 3px #ababe2c2 solid;
        }

        .middleContain
        {
            padding-left: 180px;
            padding-right: 80px;
        }

        footer
        {
            margin-top: 40px;
            background-color: #d8eaf3;
            height: 20vh;
            border-radius: 40px 40px 0px 0px;
            border-top: white 14px solid;
            box-shadow: rgba(0,0,0, 0.052) 0px -5px 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="navbar-item has-text-weight-bold is-size-4 mx-4">
                MATRISHEBA
            </div>
        </div>
        <div class="navbar-menu">
            <div class="navbar-start">
                <a href="" class="navbar-item">Home</a>
                <a href="" class="navbar-item">About</a>
                <a href="care.php" class="navbar-item">Pregnancy Report</a>
            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                    <button class="button" onclick="window.location.href='log.php'">Log in</button>
                    <button class="button" onclick="window.location.href='register.php'">Sign up</button>
                </div>
            </div>
        </div>

    </nav>
    <main>
        <div class="mainSection mb-6">
            <img src="img/sky.jpg" class="sky">
            <div class="overlay">
                <div class="title has-text-white has-text-centered centerTitle">MATRISHEBA</div>
                <div class="subtitle is-size-2 has-text-white has-text-centered has-text-weight-bold">Your one-step solution</div>
              </div>
            </div>
        </div>

        <div class="contain fixed-grid">
            <div class="grid ">
                <div class="cell is-flex is-justify-content-center is-align-items-center">
                    <img src="img/withsea.jpg" class="imgWithContain borderGang">
                </div>
                <div class="cell is-flex is-flex-direction-column is-justify-content-center is-align-items-left pr-6">
                    <p class="cTitle">User Type</p>
                    <p class="is-size-5 my-3 mb-5 subtitleGang">Our website offers three types of users: normal, doctor and volunteers. To learn more:</p>
                    <button class="button learnMore">Learn more</button>
                </div>
            </div>
        </div>
        
        <div class="contain fixed-grid">
            <div class="grid ">
                <div class="cell is-flex is-flex-direction-column is-justify-content-center is-align-items-left middleContain">
                    <p class="cTitle">Community</p>
                    <p class="is-size-5 my-3 mb-5 subtitleGang">Connect with other mothers and share information while maintaining your privacy. To learn more:</p>
                    <button class="button learnMore">Learn more</button>
                </div>
                <div class="cell is-flex is-justify-content-center is-align-items-center">
                    <img src="img/withsea02.jpg" class="imgWithContain borderGang">
                </div>
            </div>
        </div>
        
        <div class="contain fixed-grid">
            <div class="grid ">
                <div class="cell is-flex is-justify-content-center is-align-items-center">
                    <img src="img/post.jpg" class="imgWithContain borderGang">
                </div>
                <div class="cell is-flex is-flex-direction-column is-justify-content-center is-align-items-left pr-6">
                    <p class="cTitle">Healthcare</p>
                    <p class="is-size-5 my-3 mb-5 subtitleGang">Find your healthcare professionals and seek help from them. To learn more:</p>
                    <button class="button learnMore">Learn more</button>
                </div>
            </div>
        </div>

        <!-- <div class="contain">pookie</div>
        
        <div class="contain">hehe</div> -->
    </main>
    <footer>

    </footer>

</body>
</html>