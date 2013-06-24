<?php
	require_once("read.php");
	
	$fieldJSON = str_replace( "\n", "", Read::FileContents("./settings/fields.json") );
	$fields = json_decode( $fieldJSON );
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Category Editor</title>

		<link rel="stylesheet" href="./css/common.css">

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	</head>
	<body>
		<script type="text/javascript">

			var fieldTemplates = JSON.parse("<?php echo addslashes($fieldJSON); ?>");

			var CategoryFields = {};

		</script>
		
		<div class="categoryBuilder">
			
			<div class="formBrick">
				<input type="text" name="category-name" id='category-name' placeholder='Category Name' />
			</div>		
			<ul id="categoryForm">
				
				

			</ul>
		</div>
	
		<div class="toolbar">
			<!-- This will contain all the tools and shit that we need to create a category -->
			<div class="fields">
				
				<?php foreach ($fields as $field): ?>

					<div class="fieldButton">
						<?php echo $field->plaintext_name; ?>
					</div>

				<?php endforeach; ?>

			</div>
			
		</div>

	</body>
</html>