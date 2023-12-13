<!DOCTYPE html>
<!-- 
* This project utilizes the Isotope library by Metafizzy for dynamic grid layouts.
* Isotope is a JavaScript library for dynamic and intelligent grid layouts.
* Author: David DeSandro / Metafizzy
* Website: https://isotope.metafizzy.co/
* License: Used under the GPL license for non-commercial purposes.
 -->
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Alaska</title>
    <meta name = "description" content = "This is description tag.">
    <meta keywards, content = "Travel Alaska"/>
    <meta name = "author", content = "your name here">
    <!-- Load Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
    <!-- Load Bootstrap Script -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
            rel="stylesheet" 
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
            crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
            crossorigin="anonymous"></script>

    
    <!-- Load jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <!-- Load imagesLoaded -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js"></script>

    <!-- Load Isotope Script -->
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    <link rel = "stylesheet" href = "../css/travel/travel.css"/> 


    <!-- Script to Capitalize First Letter-->
    <script>
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }

    </script>

    <!-- Initialize Isotope for the newly loaded images -->
    <script>
        var cityGlobal;  // Global variable to hold the current city name
        var province = '';
        var loadedImages = 0;  // Counter for loaded images
        var isLoading = false;  // Flag to prevent multiple simultaneous loads
        var allImagesLoaded = false;  // New flag to indicate all images are loaded

        function loadCityImages(buttonElement, initialLoad = true) {

            if (isLoading) return;
            isLoading = true;

            var city = buttonElement.getAttribute('data-city-name');

            // Reset loadedImages if a new city is selected
            if (cityGlobal !== city) {
                loadedImages = 0;
                allImagesLoaded = false;
                cityGlobal = city;

                $('#current-city-name').text(capitalizeFirstLetter(city));

                // Clear container and destroy Isotope instance
                var $container = $('#image-container');
                $container.empty();
                if ($container.data('isotope')) {
                    $container.isotope('destroy');
                }
            }

            var loadCount = 20;  // Number of images to load
            if (!initialLoad) {
                loadedImages += loadCount;  // Increment loaded images counter
            }

            $.ajax({
                url: '../database/load_images.php',
                type: 'GET',
                data: { 'city': city, 'province': province, 'start': loadedImages, 'limit': loadCount },
                success: function(response) {
                    var $container = $('#image-container');
                    
                    // Check if the response includes "No images available"
                    if (response.includes("No images available for")) {
                        allImagesLoaded = true;
                    } else {
                        var $newItems = $(response);

                        // If it's the initial load, replace the container's content
                        if (initialLoad) {
                            $container.html(response);
                            $container.isotope({
                                itemSelector: '.photo-item',
                                masonry: {
                                    columnWidth: '.photo-item'
                                }
                            });
                        } else {
                            // For subsequent loads, append the new images and use 'appended' method
                            $container.append($newItems).isotope('appended', $newItems);
                        }

                        // Ensure layout is correct after images are loaded
                        $container.imagesLoaded(function() {
                            $container.isotope('layout');
                        });
                    }

                    isLoading = false;
                }

            });
        }

        $(window).scroll(function() {
            if (!allImagesLoaded && $(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                loadCityImages({getAttribute: () => cityGlobal}, false);
            }
        });
    </script>

    <script>
        $(document).ready(function(){
            province = $('h1').text().toLowerCase();

            $('.custom-city-button').on('click', function() {
                loadCityImages(this, true);
            });
        });
    </script>

    <!-- Script to load image into modal -->
    <script>
        $(document).ready(function(){
            // Event delegation for dynamically loaded content
            $('#image-container').on('click', '.photo-item', function(){
                var originalSrc = $(this).data('original-image');
                var compressedSrc = $(this).find('img').attr('src');

                $('#modal-image').attr('src', compressedSrc);
                $('#original-image-link').attr('href', originalSrc);
            });
        });
    </script>
  </head>
  <body>
    <nav class="navbar-header">
        <div class="navbar-container">
            <a href="../fun_fact.html">Back to Fun Fact</a>
            <div class = "navbar-header-menu">
                <a href = "../travel_map.html">Travel Map</a>
            </div>
        </div>
    </nav>
    <!-- City List -->
    <h1>Alaska</h1>
    <div class="city-list">
        <?php
            include '../database/connect.php';
            $province = 'alaska';
            $citiesQuery = "SELECT DISTINCT city FROM travel_image WHERE province = ?";
            $stmt = $conn->prepare($citiesQuery);
            $stmt->bind_param("s", $province);
            $stmt->execute();
            $citiesResult = $stmt->get_result();
            while ($cityRow = $citiesResult->fetch_assoc()) {
                $city = htmlspecialchars($cityRow['city']);
                $displayCityName = htmlspecialchars(ucfirst(strtolower($cityRow['city'])));
                echo "<button class='custom-city-button' data-city-name='{$city}' onclick='loadCityImages(this)'>{$displayCityName}</button>";
            }
        ?>
    </div>

    <h2 id="current-city-name"></h2>
    
    <div id="image-container" class="photo-gallery"></div>


    <div class = "modal fade" id = "imageModal" tabindex="-1" >
        <div class = "modal-dialog">
            <div class = "modal-content">
                <div class = "modal-body">
                    <img src = "" id = "modal-image" class = "img-fluid"/>
                </div>
                <div class = "modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href = "" id = "original-image-link" target="_blank" class="btn btn-primary">Original Photo</a>
                </div>
            </div>
        </div>
    </div>

                  
  </body>
</html>