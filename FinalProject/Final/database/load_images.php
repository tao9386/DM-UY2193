<?php
    include './connect.php';

    $city = $_GET['city'];
    $province = $_GET['province'];
    $start = intval($_GET['start']);
    $limit = intval($_GET['limit']);

    // Fetch images with a limit and offset
    $imagesQuery = "SELECT * FROM travel_image WHERE province = ? AND city = ? LIMIT ?, ?";
    $imageStmt = $conn->prepare($imagesQuery);
    $imageStmt->bind_param("ssii", $province, $city, $start, $limit);
    $imageStmt->execute();
    $imagesResult = $imageStmt->get_result();

    if ($imagesResult->num_rows > 0) {
        while ($imageRow = $imagesResult->fetch_assoc()) {
            echo "<div class='photo-item' data-bs-toggle='modal' data-bs-target='#imageModal' data-original-image='" . htmlspecialchars($imageRow["orig_link"]) . "'>";
            echo "<img src='" . htmlspecialchars($imageRow["comp_link"]) . "' alt='Image description' />";
            echo "</div>";
        }
    } else {
        echo "No images available for " . htmlspecialchars($city);
    }
?>
