<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleMashael.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <title>Auns - Home</title>
    
</head>

<body>
    <div class="navbar">
        <div class="navbar-container">
            <div class="logo-container">
                <img class="logo-img" src="logo-auns.png" alt="Auns Logo">
            </div>
            <div class="menu-container">
                <ul class="menu-list">
                    <li class="menu-list-item active">Home</li>
                    
                </ul>
            </div>
            <div class="profile-container">
                <a href="search.php"><i class="fas fa-search nav-icon"></i></a>
                <a href="profile.html"><i class="fas fa-cog nav-icon"></i></a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content-container">
            <div class="featured-content"
     style="background-image: url('backgroundhp.png'); 
            background-position: center top; 
            background-repeat: no-repeat; 
            background-size: 100% 100%;
            height: 400px;">

</div>

            <div class="movie-list-container">
                <h1 class="movie-list-title">New Releases</h1>
                
                <div class="movie-list-wrapper">
    
    <i class="fas fa-chevron-left arrow arrow-left"></i>

    <div class="movie-list">

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "auns";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("error" . $conn->connect_error);
        }

        $sql = "SELECT * FROM Videos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $videoId = $row['video_id']; 
                $videoUrl = $row['link'];
                $embedUrl = str_replace("watch?v=", "embed/", $videoUrl);
                $embedUrl = str_replace("youtu.be/", "www.youtube.com/embed/", $embedUrl);
                $urlParts = explode("&", $embedUrl);
                $finalUrl = $urlParts[0];
                
                ?>

                <div class="movie-list-item">
                    <iframe class="movie-list-item-img" 
                            src="<?php echo $finalUrl; ?>" 
                            title="<?php echo $row['name']; ?>" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                    
                    <span class="movie-list-item-title"><?php echo $row['name']; ?></span>
                    
                    <a href="ShowDetails.php?id=<?php echo $videoId; ?>" style="text-decoration: none;">
                        <button class="movie-list-item-button">Watch</button>
                    </a>
                </div>

                <?php
            }
        } else {
            echo "<p style='color: white;'>no video until now.</p>";
        }
        
        $conn->close();
        ?>

        <div class="movie-list-item">
            <img class="movie-list-item-img" src="mansour7.png" alt="Mansour Episode 7">
            <span class="movie-list-item-title">mansour p7</span>
            <p class="movie-list-item-desc">Watch episode 7 of the Mansour series.</p>
            <a href="ShowDetailsmansour7.html" style="text-decoration: none;">
                <button class="movie-list-item-button">Watch</button>
            </a>
        </div>

        <div class="movie-list-item">
            <img class="movie-list-item-img" src="mansour6.png" alt="Mansour Episode 6">
            <span class="movie-list-item-title">mansour p6</span>
            <p class="movie-list-item-desc">Watch episode 6 of the Mansour series.</p>
            <a href="ShowDetailsmansour6.html" style="text-decoration: none;">
                <button class="movie-list-item-button">Watch</button>
            </a>
        </div>

        <div class="movie-list-item">
            <img class="movie-list-item-img" src="mansour3.png" alt="Mansour Episode 3">
            <span class="movie-list-item-title">mansour p3</span>
            <p class="movie-list-item-desc">Watch episode 3 of the Mansour series.</p>
            <a href="ShowDetailsmansour3.html" style="text-decoration: none;">
                <button class="movie-list-item-button">Watch</button>
            </a>
        </div>

        <div class="movie-list-item">
            <img class="movie-list-item-img" src="ZakiyaAlzakiyap2.png" alt="Zakiya Alzakiya Episode 2">
            <span class="movie-list-item-title">Zakiya Alzakiya p2</span>
            <p class="movie-list-item-desc">Watch episode 2 of the Zakiya series.</p>
            <a href="ShowDetailsZakiyaAlzakiyap2.html" style="text-decoration: none;">
                <button class="movie-list-item-button">Watch</button>
            </a>
        </div>

        <div class="movie-list-item">
            <img class="movie-list-item-img" src="KaslaanBarber.png" alt="Kaslaan at the Barber">
            <span class="movie-list-item-title">Kaslaan Barber</span>
            <p class="movie-list-item-desc">Watch the Kaslaan Barber episode.</p>
            <a href="ShowDetailsKaslaanBarber.html" style="text-decoration: none;">
                <button class="movie-list-item-button">Watch</button>
            </a>
        </div>

        <div class="movie-list-item">
            <img class="movie-list-item-img" src="KaslaanPayAttention.png" alt="Kaslaan Pay Attention">
            <span class="movie-list-item-title">Kaslaan Pay Attention</span>
            <p class="movie-list-item-desc">Watch the 'Pay Attention' episode.</p>
            <a href="ShowDetailsKaslaanPayAttentio.html" style="text-decoration: none;">
                <button class="movie-list-item-button">Watch</button>
            </a>
        </div> 

        <div class="movie-list-item">
            <img class="movie-list-item-img" src="Kaslaan1.png" alt="Kaslaan and the Lion">
            <span class="movie-list-item-title">Kaslaan 1</span>
            <p class="movie-list-item-desc">Watch Kaslaan episode 1.</p>
            <a href="ShowDetailsKaslaan1.html" style="text-decoration: none;">
                <button class="movie-list-item-button">Watch</button>
            </a>
        </div>
    </div>
    
    <i class="fas fa-chevron-right arrow arrow-right"></i>
</div>
                    
                    
            </div>
        </div>
    </div>


    <footer>
        <p>&copy; 2025 AUNS. All rights reserved.</p>
    </footer>

    <script>
        const arrowRight = document.querySelector(".arrow-right");
        const arrowLeft = document.querySelector(".arrow-left");
        const movieList = document.querySelector(".movie-list");
        const itemNumber = document.querySelectorAll(".movie-list-item").length;
        let clickCounter = 0;
        const itemWidth = 330; 
        arrowRight.addEventListener("click", () => {
            const ratio = Math.floor(window.innerWidth / itemWidth);
            if (itemNumber - (clickCounter + ratio) > 0) {
                clickCounter++;
                movieList.style.transform = `translateX(${-itemWidth * clickCounter}px)`;
            } else {
                movieList.style.transform = "translateX(0)";
                clickCounter = 0;
            }
        });

       
        arrowLeft.addEventListener("click", () => {
            if (clickCounter > 0) {
                clickCounter--;
                movieList.style.transform = `translateX(${-itemWidth * clickCounter}px)`;
            }
        });

    </script>
</body>
</html>

