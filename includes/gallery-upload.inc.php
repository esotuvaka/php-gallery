<?php
if (isset($_POST['submit'])) {
	$newFileName = $_POST['filename'];

	//if name of file is left blank, will set the name to "gallery"
	// Will refactor in future to make it a random name/id
	if (empty($newFileName)) {
		$newFileName = 'gallery';
	} else {
		$newFileName = strtolower(str_replace(' ', '-', $newFileName));
	}

	$imageTitle = $_POST['filetitle'];
	$imageDesc = $_POST['filedesc'];

	$file = $_FILES['file'];

	$fileName = $file['name'];
	$fileType = $file['type'];
	$fileTempName = $file['tmp_name'];
	$fileError = $file['error'];
	$fileSize = $file['size'];

	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = ['jpg', 'jpeg', 'png'];

	if (in_array($fileActualExt, $allowed)) {
		if ($fileError === 0) {
			if ($fileSize < 200000) {
				$imageFullName =
					$newFileName . '.' . uniqid('', true) . '.' . $fileActualExt;
				$fileDestination = '../img/gallery/' . $imageFullName;

				include_once 'dbh.inc.php';

				if (empty($imageTitle) || empty($imageDesc)) {
					header('Location: ../gallery.php?upload=empty');
					exit();
				} else {
					$sql = 'SELECT * FROM gallery;';
					$statement = mysqli_stmt_init($connection);
					if (!mysqli_stmt_prepare($statement, $sql)) {
						echo 'SQL statement failed';
					} else {
						mysqli_stmt_execute($statement);
						$result = mysqli_stmt_get_result($statement);
						$rowCount = mysqli_num_rows($result);
						$setImageOrder = $rowCount + 1;

						//defaults the data upload to blank since using prepared statements
						$sql =
							'INSERT INTO gallery (titleGallery, descGallery, imgFullNameGallery, orderGallery) VALUES (?, ?, ?, ?);';
						if (!mysqli_stmt_prepare($statement, $sql)) {
							echo 'SQL statement failed!';
						} else {
							// Sends an SQL statement that replaces the "?" placeholders with the prepared variables
							mysqli_stmt_bind_param(
								$statement,
								'ssss',
								$imageTitle,
								$imageDesc,
								$imageFullName,
								$setImageOrder
							);
							mysqli_stmt_execute($statement);

							move_uploaded_file($fileTempName, $fileDestination);

							header('Location: ../gallery.php?upload=success');
						}
					}
				}
			} else {
				echo 'File is too large';
				exit();
			}
		} else {
			echo 'Error!';
			exit();
		}
	} else {
		echo 'Please upload a jpg or png file type';
	}
}
?>
