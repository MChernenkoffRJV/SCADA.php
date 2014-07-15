<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php
		echo "<title>" . $_GET["SAP_ID"] . "</title>"
	?>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <style>
    body {
        margin-top: 60px;
    }
    </style>
</head>

<body>
    <!-- 
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Start Bootstrap</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling --> <!--
			<div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#about">About</a>
                    </li>
                    <li><a href="#services">Services</a>
                    </li>
                    <li><a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse --> <!-- 
        </div>
        <!-- /.container --> <!--
    </nav>
	--> 

    <div class="container">
	<?php	
		global $mysqli;
		$mysqli = new mysqli("localhost", "root", "", "androidhive");
		$id = strtoupper($_GET["SAP_ID"]);

		$query = "SELECT * FROM `oitm` WHERE ItemCode = \"" . $id . "\"";
		$result = $mysqli->query($query) or die($mysqli->error._LINE_);

		$found = ($result->num_rows > 0);
		if (!$found) {
			echo "<h1>" . "ItemCode " . $id . " was not found" . "</hi>";	
		}
		else {
			$row = $result->fetch_assoc(); 
			echo "<h1>" . $id. "</hi>"; 
			$ItemName = stripslashes($row['ItemName']);	
			echo "<h2>" . $ItemName . "</h2>";
			show_item($row);
			
			// Suppliers
			$query = "SELECT DISTINCT cardname, t1.cardcode " . 
					 "FROM orders t0 join ocrd t1 on t0.cardcode = t1.cardcode " .
					 "where itemcode = \"" . $id . "\" " .
					 "order by cardname";
			//echo $query;
				
			//Iterate through the data
			$result = $mysqli->query($query) or die($mysqli->error._LINE_);
			if($result->num_rows > 0) {
				echo '<div class="row">';
					echo '<div class="col-xs-12">';
					echo '<h2>Suppliers</h2>';
					echo '<table class="table table-striped">';
					while($row = $result->fetch_assoc()) {
						show_supplier ($row);
					}
					echo '</table>';
					echo '</div show_supplier>';
				echo '</div>';
			}
		}
	?>
	<!-- <h1><a href="#"><?php echo $_GET['SAP_ID'] ?></a></h1> -->
	<?php
		function show_item (array $row) {
			$id = stripslashes($row['ItemCode']);	
			$LastPurPrc = number_format($row['LastPurPrc'], 2);
			$AvgPrice = number_format($row['AvgPrice'], 2);
			$OnHand = number_format($row['OnHand'], 2);
		?>
        <div class="row">
			<div class="col-xs-12">
				<h1></h1>	
			</div>
			<div class="col-xs-4">
				<?php 
					// the double urlencode is to allow %2f in the image file name e.g. SETCC10%2F2.jpg
					$image = "\\images\\" . urlencode(urlencode($_GET['SAP_ID'])) . ".jpg"; 
					echo "<img src=\"" . $image . "\" width='160' height='160'></img>";
					// echo $image;
				?>
			</div>
			<div class="col-xs-8">
					<?php 
					//if ($found) {
					/* mysqli
					$query = "SELECT * FROM `oitm+`";
					$result = $mysqli->query($query) or die($mysqli->error._LINE_);
					
					//Iterate through the data
					$result = $mysqli->query($query) or die($mysqli->error._LINE_);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
					*/
					echo 'Last Purchase Price: ' . $LastPurPrc . '</br>';
					//		$price = number_format($row['LastPurPrc'], 2); echo "$".$price;	
					echo 'Avg Price: ' . $AvgPrice . '</br>';
					//		$price = number_format($row['AvgPrice'], 2); echo "$".$price;
					echo 'SAP Qty: ' . $OnHand . '</br>';
					/*
						}
					}
					else {
						echo 'NO RESULTS';	
					}
					*/
					// }
					?>
            </div>
        </div>
		<?php
		} // end show_item
		?>		
        <div class="row">
			<div class="col-xs-12">
			<h2>Links</h2>
			</div>
		</div>
			<?php
				function show_supplier (array $row) {
							echo '<tr>';
							//Do we have a link for the supplier?
							$supplier = $row['cardcode'];
							$query2 = "SELECT link FROM ocrd_more WHERE cardcode = \"" . $supplier . "\"";
							//echo $query2;
							/* $result2 = $mysqli->query($query2) or die($mysqli->error._LINE_);
							if($result2->num_rows > 0) {
								$row2 = $result2->fetch_assoc();
								$link = $row2['link'];
								echo '<td><a href="'.$link.'">'.$row['cardname'].'</a></td>';
								//echo "Link: ". $link;
							}
							else {
							*/	echo '<td>'.$row['cardname'].'</td>';
							// }
							//echo '<td>'.$row['docnum'].'</td>';
							//echo '<td>'.$row['docdate'].'</td>';
							//$qty = number_format($row['quantity'], 2); echo '<td>'."$".$qty.'</td>';
							//$price = number_format($row['price'], 2); echo '<td>'."$".$price.'</td>';
							echo '</tr>';
					}
			?>
      <div class="row">
			<div class="col-xs-12">
			<h2>Orders</h2>
			<?php
				$query = "SELECT cardname, docnum, docdate, quantity, price " . 
						 "FROM orders t0 join ocrd t1 on t0.cardcode = t1.cardcode " .
						 "where itemcode = \"" . $id . "\" " .
						 "order by docdate desc";
				//echo $query;
				
				//Iterate through the data
					$result = $mysqli->query($query) or die($mysqli->error._LINE_);
					if($result->num_rows > 0) {
						echo '<table class="table table-striped">';
						while($row = $result->fetch_assoc()) {
							echo '<tr>';
							echo '<td>'.$row['cardname'].'</td>';
							echo '<td>'.$row['docnum'].'</td>';
							echo '<td>'.$row['docdate'].'</td>';
							$qty = number_format($row['quantity'], 2); echo '<td>'.$qty.'</td>';
							$price = number_format($row['price'], 2); echo '<td>'."$".$price.'</td>';
							echo '</tr>';
						}
						echo '</table>';
					}
					else {
						echo 'NO RESULTS';	
					}
			?>
			</div>
		</div>
	<?php
	// } // end show_item
	?>
	<?php 
    	//Close the connection
		mysqli_close($mysqli);
	?>
	</div>
    <!-- /.container -->

    <!-- JavaScript -->
    <script src="js/jquery-1.11.1.js"></script>
    <script src="js/bootstrap.js"></script>

</body>

</html>
