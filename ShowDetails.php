<?php
require_once 'db_connect (1).php'; 

$title = "Video Not Found";
$description = "The requested video could not be found.";
$video_link = "";
$producer = "";
$category = "";
$rating = "";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $video_id = $_GET['id'];
    
    $sql = "SELECT name, description, link, producer, category, rating FROM Videos WHERE video_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $video_data = $result->fetch_assoc();
        
        $title = htmlspecialchars($video_data['name']);
        $description = htmlspecialchars($video_data['description']);
        
        $video_link = htmlspecialchars($video_data['link']); 
        $producer = htmlspecialchars($video_data['producer']);
        $category = htmlspecialchars($video_data['category']);
        $rating = htmlspecialchars($video_data['rating']);
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Video Details</title>
  <link rel="stylesheet" href="showStyle.css">
  
</head>

<body>
  <header>
    <nav>
      <a href="homepage2.php">Home</a>
      <a href="search.php">Search</a>
    </nav>
  </header>

  <main>

    <div class="container">
    <section class="video-details">
      
      <h1><?php echo $title; ?></h1>

      <div class="video-player-container">
        <iframe width="640" height="360" 
            src="<?php echo $video_link; ?>" 
            title="<?php echo $title; ?>" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            referrerpolicy="strict-origin-when-cross-origin" 
            allowfullscreen>
        </iframe>
      </div>

      <h2>Video description</h2>
      <p><?php echo $description; ?></p>

      <ul>
        <li><strong>Topic:</strong> <?php echo $category; ?></li> 
        <li><strong>Age Group/Rating:</strong> <?php echo $rating; ?></li> 
        <li><strong>Channel:</strong> <?php echo $producer; ?></li>
      </ul>

      <a href="search.php" class="button-link">← Back to Search</a>
    </section>
    </div>
  </main>
  
  <footer>
    <p>&copy; 2025 AUNS</p>
  </footer>

  </body>
</html>

