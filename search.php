<?php
require_once 'db_connect (1).php'; 

$search_term = '';
$filter_category = '';
$results = [];
$no_match = false;
$categories = [];
$category_result = $conn->query("SELECT DISTINCT category FROM Videos WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");

if ($category_result) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

if (isset($_GET['query']) || isset($_GET['filter'])) {
    
    $search_term = trim($_GET['query'] ?? ''); 
    $filter_category = trim($_GET['filter'] ?? ''); 

    $sql = "SELECT video_id, name, description, producer, category 
            FROM Videos 
            WHERE 1=1"; 
    
    $params = [];
    $types = '';

    if (!empty($search_term)) {
        $sql .= " AND (name LIKE ? OR description LIKE ?)";
        $like_search = "%" . $search_term . "%";
        $params[] = $like_search;
        $params[] = $like_search;
        $types .= 'ss';
    }

    if (!empty($filter_category)) {
        $sql .= " AND category = ?";
        $params[] = $filter_category;
        $types .= 's';
    }

    $sql .= " ORDER BY name ASC";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params); 
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    if ($result->num_rows === 0) {
        $no_match = true;//
        $fallback_sql = "SELECT video_id, name, description, producer, category 
        FROM Videos 
        ORDER BY name ASC";
        $fallback_result = $conn->query($fallback_sql);
        
        if ($fallback_result && $fallback_result->num_rows > 0) {
        while ($row = $fallback_result->fetch_assoc()) {
            $results[] = $row;}
        }
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
    <title>Search Page</title>
    <link rel="stylesheet" href="searchStyle.css">
</head>

<body>
    <header>
        <nav>
            <a href="HomePage2.php">Home</a> 
        </nav>
    </header>

    <div class="container">
        <h2>Search for Videos</h2>
        
        <form action="search.php" method="GET">
            <label for="search">Search:</label>
            <input type="text" id="search" name="query" placeholder="Type a topic or title..." 
                   required value="<?php echo htmlspecialchars($search_term); ?>">
            
            <label for="filter">Filter:</label>
            <select id="filter" name="filter">
                <option value="">None</option>
                <?php foreach ($categories as $cat): ?>
        <?php 
        $selected = ($filter_category == $cat) ? 'selected' : ''; 
        ?>
        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $selected; ?>>
            <?php echo htmlspecialchars(ucfirst($cat)); ?>
        </option>
    <?php endforeach; ?>
            </select>

            <button type="submit">Search</button>
        </form>

        <hr>
        <section class="search-results">
            <?php if (isset($_GET['query']) || isset($_GET['filter'])): ?>
                <?php if ($no_match): ?>
                    <h3>No videos matched your criteria. Try a different term or filter.<br>Available videos:</h3>
                    <?php else: ?>
                        <h3>Found <?php echo count($results); ?> Videos</h3>
                        <?php endif; ?>

                <?php foreach ($results as $video): ?>
                    <div class="video-item">
                        <a href="ShowDetails.php?id=<?php echo $video['video_id']; ?>">
                            <h4><?php echo htmlspecialchars($video['name']); ?></h4>
                        </a>
                        <p><strong>Producer:</strong> <?php echo htmlspecialchars($video['producer']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($video['category']); ?></p>
                        <p><?php echo substr(htmlspecialchars($video['description']), 0, 150) . '...'; ?></p>
                    </div>
                <?php endforeach; ?>
            
            <?php else: ?>
                <p>Enter a search term or select a filter to find videos.</p>
            <?php endif; ?>
        </section>

    </div>

    <footer>
        <p>&copy; 2025 AUNS</p>
    </footer>

    <script>
    document.getElementById("filter").addEventListener("change", function () {
    this.form.submit(); });

    <?php if ($no_match): ?> 
        alert("No videos matched your search. Please try a different keyword or category.");
    <?php endif; ?>

</script>


</body>
</html>

