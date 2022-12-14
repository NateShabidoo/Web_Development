// This page displays user's saved charts, using each chart's unique name to retrieve the image file path from the database
// also this page allows users to download the .png chart or delete (delete removes the image from server folder and unique id from database)
// Fyi - the nav bar takes up a lot of the first part of this page 
// The chart display portion starts at line 209
// I've added comments at the beginning of the 'chart retrieval/output' portion

<?php
session_start();
require 'database/config.php';
ob_start();
$username = $_SESSION['username'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
while ($row = mysqli_fetch_array($query)) {  
	$userid= $row['user_ID']; 
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Charts</title>

        <link href="style/main.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="style/normalize.css">
        
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous"/>

<!-- ChartJs library and plugins-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@0.7.7"></script>

        <!-- For Saving Files -->
        <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.2/dist/FileSaver.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.2.0/papaparse.min.js"></script>
        
        <!-- For Making Spreadsheets -->
        <script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
        <link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />

        <script src="https://jsuites.net/v4/jsuites.js"></script>
        <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
        <!-- References to our own javascript files-->
        <script src="parser.js"></script>
        <script src="charts.js?version=1.0"></script>
        <script src="example-charts.js"></script>
</style>
</head>
<body>
     <nav class="navbar navbar-light bg-light navbar-expand-sm" id="navbar">
            <div class="container-fluid" id="navbar-container">

                <a class="navbar-brand text-primary" href="index.php" style="display: flex; align-items: center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="83" height="64" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                        <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
                    </svg>

                    <span style="color: black; font-size:35px; text-align: center; padding-left: 3%;">SimpleChartsRI</span>
                </a>

                <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="main.php" style="color:black;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="resourcesRedirect.php" style="color:black;">Resources</a>
                        </li>
                        <?php $username = $_SESSION['username'];
						$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
							while ($row = mysqli_fetch_array($query)) {
							$status= $row['status'];
							if($status == "Teacher" || $status == "Other"){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="guest-speaker.php" style="color:black;">Find Speaker</a>
                        </li><?php	}} ?>
						<?php $username = $_SESSION['username'];
						$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
							while ($row = mysqli_fetch_array($query)) {
							$status= $row['status'];
							if($status == "Teacher" || $status == "Other"){ ?>
							    <li class="nav-item">
                            <a class="nav-link" href="be-speaker.php" style="color:black;">Become Speaker</a>
                        </li> <?php	}} ?>
                        <li class="nav-item">
                            <a class="nav-link" href="news.php" style="color:black;">News</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="project.php" style="color:black;">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="rate-us.php" style="color:black;">Rate Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" id="helpIcon" data-toggle="modal" data-target="#docModal">Help</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php" style="color:black;">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php" style="color:black;">Contact</a>
                        </li>
                        <?php if ($_SESSION['username']==NULL){ ?>
                            <!-- Haven't logged in --> 
                            <li class="nav-item">
                            <a class="nav-link" href="login.php" style="color:black;">Login</a>
                            </li> <?php } ?>
                        <?php $username = $_SESSION['username'];
						$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
							while ($row = mysqli_fetch_array($query)) {
							$status= $row['status'];
							if($status == "Teacher" || $status == "Other" || $status == "Student"){ ?>
							<li class="nav-item">
                            <a class="nav-link active" href="mycharts.php" style="color:black;" name="logout">My Charts</a>
                             </li>
                            <li class="nav-item">
                            <a class="nav-link" href="logout.php" style="color:black;" name="logout">Logout</a>
                             </li> <?php }} ?>
                    </ul>
                </div>
                </div>
                </nav>
     <!-- MODAL -->
        <div class="modal fade" id="docModal" role="dialog">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Guide</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        
                    </div>
                    <div class="modal-body" style="font-size: 20px;">
                        <h3><b>Formatting</b></h3>
                        <p>Our tool currently accepts csv files. Ensure all excel files are saved with this extension (.csv). If there are numbers in your file that include commas, these commas must be removed in Excel before uploading to the site. For instance "1,000,000" should be reformatted to "1000000". Additionally, other special characters should also be removed.</p>
                        <p>The first column in your file should be used for headers, or ordered in some way (date/time). The rows in the first column will be displayed in the same order as the original file. After the first column, the following columns are used as y-values where each column is its own dataset.
                        </p>
                        
                        <p>The 'Creating Data' option provides two ways to create your data. There's a table view as well as a text view. Both views enable you to copy & paste / drag & drop from a spreadsheet application like Excel.</p>
                        <p>The table view requires you to right-click on the table to bring up a menu for adding/removing rows and columns. This table works mostly like Excel tables. Headers (the first row) must be renamed through right-clicking them, since double-clicking it changes the ordering. Tables cannot contain any unused columns or rows, so it's best to delete anything additional. While the chart may still show, doing so will result in a chart that doesn't accurately reflect your data to the fullest capacity.</p>
                        <p>The text view supports both csv format and tsv format. Since there's no need to right-click a menu and rename the headers, it may be easier using this view if you are just copying & pasting / dragging & dropping data from Excel without making any other changes.</p>

                        <h3 class="mt-2"><b>Customizing Charts</b></h3>
                        
                        <p>In addition to titling the chart and naming the x & y axis, further customization options are also available.</p>

                        <h5><b>Choose a Color Scheme</b></h5>
                        <p>Selecting a color scheme will apply a color palette to your datasets, where every dataset is represented by a different color. In the picture below, the blocks for any given style represent the order in which the colors will be applied. If the number of datasets is greater than the number of colored blocks for any given style, then the color scheme will loop and reuse the same colors.</p>
                        
                        <img src="additional-files/color-styles-img.png" style="height: 307; width: 245px;">
                            
                        <h5 class=" mt-3"><b>Creating Mixed Charts</b></h5>
                        <p >After choosing an initial chart type and reaching the chart screen, use the two dropdown menus pictured below to choose specific datasets and change their corresponding chart type.</p>
                        
                        <img src="additional-files/dataset1.png" style="height:150px; width:334px;">
                        
                        <p class=" mt-3">Furthermore, if you want to change the chart type for every single dataset at once, use the dropdown menu pictured below for convenience.</p>
                        
                        <img src="additional-files/change-all-datasets.png" style="height:79px; width:328px;">
                         
                        <h3 class=" mt-3"><b>Interacting with Charts</b></h3>
                        
                        <p >For cluttered charts, there are three key ways to focus in on certain features.</p>
                        
                        <h5 ><b>View Specific Values</b></h5>
                        <p >Hovering over any piece of data on a chart will bring up a tooltip that displays the specific values for that data.</p>
                        <img src="additional-files/chart-tooltip.png" style="height:228px; width: 466px;">
                        
                        <h5 class=" mt-3"><b>Zooming & Panning</b></h5>
                        <p >Use the scrollwheel on your mouse to zoom into a chart. Click & drag on the chart to pan. Click the 'Reset Zoom' button to revert the chart back to its original state. As a side note, these features work best on smaller datasets. Zooming is limited on charts with more than a few hundred pieces of data.</p>
                        
                        <h5 class=" mt-3"><b>Focus on a Dataset</b></h5>
                        
                        <p >Sometimes, overlapping data can make it difficult to see the entirety of any given dataset even when different colors are applied.</p>
                        <p >Look at the chart below.</p>
                        <img src="additional-files/many-datasets-example.png">
                        <p class=" mt-3">This chart uses a subset of data from a Narragansett Bay Fish Trawl Survey. While the original file contained more than twenty datasets and this one only has three, it can still be difficult to see any one dataset clearly. </p>
                        
                        <p ><b>Clicking on a dataset in the legend</b> will hide/show that dataset. For files with many datasets, this can be useful for uncluttering the chart. The picture below uses that same data as the chart above. But now, one dataset is hidden from view.</p>
                        
                        <img src="additional-files/many-datasets-remove-dataset-example.png" style="height: 394px; width: 360px;">
                        
                        <p class=" mt-3"><b>Hovering over a dataset</b> in the legend is also an effective way to focus on one specific dataset. Doing so will turn all other datasets on the chart transparent, thus bringing one dataset into focus. For cluttered data, this feature can be effective for visualizing one dataset, while still being able to see the overall trend of other datasets.</p>
                        
                        <img src="additional-files/many-datasets-highlight-example.png" style="height: 393px; width: 358px;">

                        <h3 class=" mt-3"><b>Additional Features</b></h3>
                        <p >If the rows of your columns are greater than 150, your chart's styling will be altered to help prevent clutter. For example, the points in a scatter chart will be smaller, and the lines in a line chart will be thinner.</p>
                        <p >If the header names used for the x-axis are too long, they will be truncated. This prevents long x-axis names from crunching the chart. If a header is truncated, it will end with an ellipsis to show it has been cut off. You can still see the original name by hovering over the point itself.</p>
                         

                    </div>
                        
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

	<div id="main-wrapper">
    
<!--This is teh start of the chart display code-->
    <center>
        <?php $username = $_SESSION['username'];
						$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
							while ($row = mysqli_fetch_array($query)) {
							$status= $row['status'];
							if($status == "Teacher" || $status == "Other" || $status == "Student"){ ?>
                            <h2 id="welcome" ><?php echo $_SESSION['username'] ?>'s Charts</h2>
                         <?php	}} ?>
				</center>
				</br>
	<?php   
	$chartid = array();
	$deleteButton = array();
	$file_name = array();
	$downloadButton = array();
	
	$query = "select * from add_chart where usersid='$userid'";
    $result=mysqli_query($conn, $query);
    $x = 0;
    $w = 200;

        while($row = mysqli_fetch_assoc($result)) {
        $x = $x+1;
        $w = $w+1;

        $chart_instance = $row['chart_id']; 
        $deleteButton[$x] = $x;
        $file_name[$x] = trim($chart_instance, "saved_charts/");
        $downloadButton[$w] = $w;
        ?>
       <div>
	   <center>
		  <img src="<?php echo $row['chart_id'];?>" style="width:1020px"></br>
		 <form class="myform" action="mycharts.php" method="post" id="mycharts_form">
         <input name="<?php echo $downloadButton[$w];?>" type="submit" id="download_btn" value= "Download Chart"/>
		 <input name="<?php echo $deleteButton[$x];?>" type="submit" id="delete_btn" value= "Delete Chart"/>
		 
	    </form>
	    </center>
    </div>
<?php       $chartid[] = $chart_instance; 

 } 
       if (sizeof($chartid)==0) {
		 $print = "<h2> You Have No Saved Charts...</h2>";
		 echo $print;

	}?>
	</div>
		<?php

        //BUTTONS FOR CHART 1

		if(isset($_POST[$downloadButton[201]])) {
            $image = $file_name[1];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
}

		if(isset($_POST[$deleteButton[1]])) {

            $query = "delete from add_chart where chart_id='$chartid[0]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 1 deleted successfully")</script>';
						header('location: mycharts.php');
				}
		
            $file_to_delete = $file_name[1];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	    }}}
		
    // BUTTONS FOR CHART 2

			if(isset($_POST[$downloadButton[202]])) {
            
            $image = $file_name[2];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
        }
		
			if(isset($_POST[$deleteButton[2]])) {

            $query = "delete from add_chart where chart_id='$chartid[1]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 2 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[2];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
		   // BUTTONS FOR CHART 3
		   
			if(isset($_POST[$downloadButton[203]])) {
            
            $image = $file_name[3];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[3]])) {

            $query = "delete from add_chart where chart_id='$chartid[2]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 3 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[3];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	        }}}
		
		// BUTTONS FOR CHART 4
	
			if(isset($_POST[$downloadButton[204]])) {
            
            $image = $file_name[4];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
	
			if(isset($_POST[$deleteButton[4]])) {
            $query = "delete from add_chart where chart_id='$chartid[3]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 4 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[4];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	        }}}

		// BUTTONS FOR CHART 5	
		
			if(isset($_POST[$downloadButton[205]])) {
            
            $image = $file_name[5];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[5]])) {
            $query = "delete from add_chart where chart_id='$chartid[4]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 5 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[5];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
        	}}}
		
			// BUTTONS FOR CHART 6	
		
			if(isset($_POST[$downloadButton[206]])) {
            
            $image = $file_name[6];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[6]])) {
            $query = "delete from add_chart where chart_id='$chartid[5]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 6 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[6];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	        }}}
		
				// BUTTONS FOR CHART 7	
		
			if(isset($_POST[$downloadButton[207]])) {
            
            $image = $file_name[7];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
	
	    	if(isset($_POST[$deleteButton[7]])) {
            $query = "delete from add_chart where chart_id='$chartid[6]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 7 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[7];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	        }}}
		
				// BUTTONS FOR CHART 8	
		
			if(isset($_POST[$downloadButton[208]])) {
            
            $image = $file_name[8];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[8]])) {
            $query = "delete from add_chart where chart_id='$chartid[7]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 8 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[8];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	        }}}
		
				// BUTTONS FOR CHART 9	
		
			if(isset($_POST[$downloadButton[209]])) {
            
            $image = $file_name[9];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[9]])) {
            $query = "delete from add_chart where chart_id='$chartid[8]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 9 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[9];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	        }}}
		
				// BUTTONS FOR CHART 10	
		
			if(isset($_POST[$downloadButton[210]])) {
            
            $image = $file_name[10];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[10]])) {
            $query = "delete from add_chart where chart_id='$chartid[9]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 10 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[10];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
        	}}}

		// BUTTONS FOR CHART 11	
		
			if(isset($_POST[$downloadButton[211]])) {
            
            $image = $file_name[11];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
        }

			if(isset($_POST[$deleteButton[11]])) {
            $query = "delete from add_chart where chart_id='$chartid[10]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 11 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[11];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
				// BUTTONS FOR CHART 12
		
			if(isset($_POST[$downloadButton[212]])) {
            
            $image = $file_name[12];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
        }
		
			if(isset($_POST[$deleteButton[12]])) {
            $query = "delete from add_chart where chart_id='$chartid[11]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 12 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[12];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
				// BUTTONS FOR CHART 13	
		
			if(isset($_POST[$downloadButton[213]])) {
            
            $image = $file_name[13];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
        }
		
			if(isset($_POST[$deleteButton[13]])) {
            $query = "delete from add_chart where chart_id='$chartid[12]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 13 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[13];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
				// BUTTONS FOR CHART 14	
		
			if(isset($_POST[$downloadButton[214]])) {
            
            $image = $file_name[14];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
		    if(isset($_POST[$deleteButton[14]])) {
            $query = "delete from add_chart where chart_id='$chartid[13]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 14 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[14];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
				// BUTTONS FOR CHART 15	
		
			if(isset($_POST[$downloadButton[215]])) {
            
            $image = $file_name[15];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
        }
		
			if(isset($_POST[$deleteButton[15]])) {
            $query = "delete from add_chart where chart_id='$chartid[14]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 15 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[15];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
				// BUTTONS FOR CHART 16
		
			if(isset($_POST[$downloadButton[216]])) {
            
            $image = $file_name[16];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[16]])) {
            $query = "delete from add_chart where chart_id='$chartid[15]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 16 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[16];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
				// BUTTONS FOR CHART 17	
		
			if(isset($_POST[$downloadButton[217]])) {
            
            $image = $file_name[17];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
	
			if(isset($_POST[$deleteButton[17]])) {
            $query = "delete from add_chart where chart_id='$chartid[16]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 17 deleted successfully")</script>';
						header('location: mycharts.php');
				}
				
			$file_to_delete = $file_name[17];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
				// BUTTONS FOR CHART 18	
		
			if(isset($_POST[$downloadButton[218]])) {
            
            $image = $file_name[18];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[18]])) {
            $query = "delete from add_chart where chart_id='$chartid[17]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 18 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[18];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
				// BUTTONS FOR CHART 19	
		
			if(isset($_POST[$downloadButton[219]])) {
            
            $image = $file_name[19];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
		
			if(isset($_POST[$deleteButton[19]])) {
            $query = "delete from add_chart where chart_id='$chartid[18]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 19 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[19];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
		
				// BUTTONS FOR CHART 20
		
			if(isset($_POST[$downloadButton[220]])) {
            
            $image = $file_name[20];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
            
			if(isset($_POST[$deleteButton[20]])) {
            $query = "delete from add_chart where chart_id='$chartid[19]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 20 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[20];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
	
					// BUTTONS FOR CHART 21
		
			if(isset($_POST[$downloadButton[221]])) {
            
            $image = $file_name[21];
            $location_with_image_name = "https://www.simplechartsri.com/saved_charts/".$image;
            $filehere= "saved_charts/".$image;
            
            $url = "https://www.simplechartsri.com/saved_charts/".$image;
            
            header('Content-disposition: attachment; filename=image.png');
            header('Content-type: image/png');
            ob_get_clean();
            readfile('saved_charts/'.$image);
            ob_end_flush();
            }
            
			if(isset($_POST[$deleteButton[21]])) {
            $query = "delete from add_chart where chart_id='$chartid[20]'";
					$query_run=mysqli_query($conn,$query);
					if($query_run)
					{
					    
						echo '<script type="text/javascript">alert("Chart 21 deleted successfully")</script>';
						header('location: mycharts.php');
				}
			$file_to_delete = $file_name[21];
            $location_with_image_name = "saved_charts/".$file_to_delete;
            if(file_exists($location_with_image_name)){
	        $delete_image  = unlink($location_with_image_name);
	        if($delete_image){
		        echo "delete success";
	        }else{
	        echo "delete not success";
	}}}
	?>

</body>
</html>
