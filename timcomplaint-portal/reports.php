<?php
//PUT THIS HEADER ON TOP OF EACH UNIQUE PAGE
session_start();
if(isset($_SESSION['username'])){

?>
<html>

<head>
    <title>TimComplaint Platform - Reports</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.min.css" integrity="sha256-WgFzD1SACMRatATw58Fxd2xjHxwTdOqB48W5h+ZGLHA=" crossorigin="anonymous" />
</head>

<body>
    
    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                    aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
                <a class="navbar-brand" href="#">TimComplaint</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li class="active"><a href="#">Reports</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        if (isset($_SESSION['username'])) {
                    ?>
                        <li class="active"><a href="logout.php">Logout</a></li>
                    <?php
                        } else {
                    ?>
                        <li class="active"><a href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
    <br><br><br>
    <div class="container">
        <h1>Content</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" id="category">
                        <option selected="selected" value="">All</option>
                        <option value="1">Social assistance</option>
                        <option value="2">Warehouse waste</option>
                        <option value="3">Unauthorized building</option>
                        <option value="5">Public lighting</option>
                        <option value="6">Parking</option>
                        <option value="13">Salubrity</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Date:</label>
                    <div class="input-daterange input-group" id="datepicker">
                        <span class="input-group-addon">From</span>
                        <input id="date_from" type="text" class="input-sm form-control" name="start" />
                        <span class="input-group-addon">to</span>
                        <input id="date_to" type="text" class="input-sm form-control" name="end" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chartDiv">
                    <canvas id="myChart" width="150" height="150"></canvas>
                </div>
            </div>
        </div>
        
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js" integrity="sha256-TueWqYu0G+lYIimeIcMI8x1m14QH/DQVt4s9m/uuhPw=" crossorigin="anonymous"></script>
    <script src="./js/index_admin.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js" integrity="sha256-SiHXR50l06UwJvHhFY4e5vzwq75vEHH+8fFNpkXePr0=" crossorigin="anonymous"></script>
    <script src="./js/reports.js"></script>
</body>
</html>
<?php
} else {
    session_destroy();
    header("location:index.php");
}
?>
