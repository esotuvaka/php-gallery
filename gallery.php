
<!-- ADD PHP LOGIN EVENTUALLY -->
<?php $_SESSION['username'] = 'Admin'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gallery</title>
</head>
<body>
    <header></header>
    <main>
        <section class="gallery-links">
            <div class="wrapper"> 
                <h2>Gallery</h2>

                <div class="gallery-container">
                    <?php
                    include_once 'includes/dbh.inc.php';

                    $sql = 'SELECT * FROM gallery ORDER BY orderGallery DESC;';
                    $statement = mysqli_stmt_init($connection);

                    if (!mysqli_stmt_prepare($statement, $sql)) {
                    	echo 'SQL statement failed!';
                    } else {
                    	mysqli_stmt_execute($statement);
                    	$result = mysqli_stmt_get_result($statement);

                    	while ($row = mysqli_fetch_assoc($result)) {
                    		echo '<a href="">
                                <div style="background-image: url(img/gallery/' .
                    			$row['imgFullNameGallery'] .
                    			');"></div>
                                <h3>' .
                    			$row['titleGallery'] .
                    			'</h3>
                                <p>' .
                    			$row['descGallery'] .
                    			'</p>
                            </a>';
                    	}
                    }
                    ?>
                    
                </div>

                <?php if (isset($_SESSION['username'])) {
                	echo ' <div class="gallery-upload">
                    <form action="includes/gallery-upload.inc.php" method="post" enctype="multipart/form-data">
                        <input type="text" name="filename" placeholder="File name...">
                        <input type="text" name="filetitle" placeholder="Image title...">
                        <input type="text" name="filedesc" placeholder="Image description...">
                        <input type="file" name="file">
                        <button type="submit" name="submit">Upload</button>
                    </form>
                </div>';
                } ?> 
            </div>
        </section>
    </main>
    <footer></footer>
</body>
</html>