<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header('Location: loginAdmin.php'); 
    exit;
}
require "db_connect (1).php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Management Dashboard</title>
    <link rel="stylesheet" href="css.css"> 
</head>
<body>

<div class="container">
    <button class="logout-btn" onclick="logoutUser()">Log out</button>
    <h1>Video Management Dashboard</h1>
    <p>Welcome! Manage your video files below.</p>

    <div class="controls">
        <button onclick="showSection('add-video')">Add New Video</button>
        <button onclick="showSection('video-list')">View All Videos</button>
    </div>

    <hr>

    <div id="video-list">
        <h2>Your Videos List</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Link</th>
                    <th>Producer</th>
                    <th>Age Rating</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM videos ORDER BY video_id DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['video_id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><a href="<?= $row['link'] ?>" target="_blank">Watch</a></td>
                    <td><?= $row['producer'] ?></td>
                    <td><?= $row['rating'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td>
                        <button onclick='showEditForm(<?= $row["video_id"] ?>, "<?= $row["name"] ?>")'>Edit</button>
                        <button onclick="confirmDelete(<?= $row['video_id'] ?>, '<?= $row["name"] ?>')">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
        </table>
    </div>

    <div id="add-video" class="form-section" style="display: none;">
        <h2>Add New Video</h2>
        <form action="submit_AddVideo.php" method="POST">
            <label for="add-name">Video Name:</label>
            <input type="text" id="add-name" name="name" required><br><br>

            <label for="add-description">Description:</label>
            <textarea id="add-description" name="description" rows="3" required></textarea><br><br>

            <label for="add-link">Video Link (URL):</label>
            <input type="url" id="add-link" name="link" required><br><br>

            <label for="add-producer">Production Company:</label>
            <input type="text" id="add-producer" name="producer" required><br><br>

            <label for="add-rating">Age Rating:</label>
            <select id="add-rating" name="rating" required>
                <option value="">-- Select Rating --</option>
                <option value="+6 months">+6 months</option>
                <option value="+1 year">+1 year</option>
                <option value="+3 years">+3 years</option>
                <option value="+6 years">+6 years</option>
                <option value="+12 years">+12 years</option>
            </select><br><br>

            <label for="add-category">Category:</label>
            <select id="add-category" name="category" required>
                <option value="">-- Select Category --</option>
                <option value="islam">Islam</option>
                <option value="cartoon">Cartoon</option>
                <option value="movie">Movie</option>
                <option value="games">Games</option>
                <option value="Song">Song</option>
                <option value="Autism and Down">Autism and Down</option>
            </select><br><br>

            <button type="submit">Create Video</button>
        </form>
    </div>

    <div id="edit-video" class="form-section" style="display: none;">
        <h2>Edit Video <span id="editing-video-name"></span></h2>
        <form id="edit-form" action="process_video.php" method="POST">
            <input type="hidden" name="action" value="update">
            
            <input type="hidden" id="edit-video-id" name="video_id" value="">
            
            <p>Modify the details below:</p>
            
            <label for="edit-name">Video Name:</label>
            <input type="text" id="edit-name" name="name"><br><br>

            <label for="edit-description">Description:</label>
            <textarea id="edit-description" name="description" rows="3"></textarea><br><br>

            <label for="edit-link">Video Link (URL):</label>
            <input type="url" id="edit-link" name="link"><br><br>

            <label for="edit-producer">Production Company:</label>
            <input type="text" id="edit-producer" name="producer"><br><br>

            <label for="edit-rating">Age Rating:</label>
            <select id="edit-rating" name="rating">
                <option value="">-- Select Rating --</option>
                <option value="+6 months">+6 months</option>
                <option value="+1 year">+1 year</option>
                <option value="+3 years">+3 years</option>
                <option value="+6 years">+6 years</option>
                <option value="+12 years">+12 years</option>
            </select><br><br>

            <label for="edit-category">Category:</label>
            <select id="edit-category" name="category">
                <option value="">-- Select Category --</option>
                <option value="islam">Islam</option>
                <option value="cartoon">Cartoon</option>
                <option value="movie">Movie</option>
                <option value="games">Games</option>
                <option value="Song">Song</option>
                <option value="Autism and Down">Autism and Down</option>
            </select><br><br>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function showSection(sectionId) {
        document.getElementById('video-list').style.display = 'none';
        document.getElementById('add-video').style.display = 'none';
        document.getElementById('edit-video').style.display = 'none';
        
        document.getElementById(sectionId).style.display = 'block';
    }

    function showEditForm(videoId, videoName) {
    showSection('edit-video');
    document.getElementById('edit-video-id').value = videoId;
    document.getElementById('editing-video-name').textContent = `(ID: ${videoId} - ${videoName})`;

    fetch(`fetch_video.php?id=${videoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('edit-name').value = data.name;
                document.getElementById('edit-description').value = data.description;
                document.getElementById('edit-link').value = data.link;
                document.getElementById('edit-producer').value = data.producer;
                document.getElementById('edit-rating').value = data.rating; 
                document.getElementById('edit-category').value = data.category;
            })
            .catch(error => console.error('Error fetching video details:', error));
    }

    function confirmDelete(videoId, videoName) {
        if (confirm(`Are you sure you want to delete the video: "${videoName}" (ID: ${videoId})?`)) {
            fetch('process_video.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete&video_id=${videoId}`
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                window.location.reload(); 
            })
            .catch(error => console.error('Error deleting video:', error));
        }
    }
    
    showSection('video-list');
    function logoutUser() {
        window.location.href = 'logout.php';
    }
</script>

</body>

<footer>
    <p>&copy; 2025 AUNS</p>
</footer>

</html>