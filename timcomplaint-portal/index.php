<?php
//PUT THIS HEADER ON TOP OF EACH UNIQUE PAGE
session_start();
?>
<html>

<head>
    <title>TimComplaint Platform</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">
    <link href="./css/style.css" rel="stylesheet">
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
                    <li class="active"><a href="index.php">Home</a></li>
                    
                    <?php
                        if (isset($_SESSION['username'])) {
                    ?>
                        <li><a href="reports.php">Reports</a></li>
                    <?php
                        }
                    ?>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                    
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

    <div id="map"></div>
    
    <div id="loginModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Login</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div align="center" class="col-lg-8" style="float: none; margin: 0 auto;">
                            <form class="form-signin" name="form1" method="post" action="checklogin.php">
                                <input name="myusername" id="myusername" type="text" class="form-control" placeholder="Username" autofocus> <br/>
                                <input name="mypassword" id="mypassword" type="password" class="form-control" placeholder="Password"><br/>
                                <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Login</button>

                                <div id="message"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>

    <?php
        if (isset($_SESSION['username'])) {
    ?>
        <div id="editModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit complaint</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div align="center" class="col-lg-10" style="float: none; margin: 0 auto;">
                                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                    <!-- <ol id="indicators" class="carousel-indicators">
                                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                        <li data-target="#myCarousel" data-slide-to="1"></li>
                                        <li data-target="#myCarousel" data-slide-to="2"></li>
                                    </ol> -->

                                    <div id="carouselpictures" class="carousel-inner">


                                    </div>

                                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                
                                <h4 id="category">Category</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Complainant</td>
                                        <td><span id="complainant"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <td><span id="description"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Created</td>
                                        <td><span id="created"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Modified</td>
                                        <td><span id="modified"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" id="status">
                                                    <option value="OPEN">OPEN</option>
                                                    <option value="INPROGRESS">IN PROGRESS</option>
                                                    <option value="SOLVED">SOLVED</option>
                                                    <option value="CLOSED">CLOSED</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="onSaveClick();" >Save</button>
                    </div>
                </div>

            </div>
        </div>  

       
        <div id="deleteModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    
                    <div class="modal-body">
                        <h4>Are you sure you want to delete this complaint?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="deleteComplaint(this,event);">Yes</button>
                        <button type="button" data-dismiss="modal" class="btn">No</button>
                    </div>
                </div>

            </div>
        </div>  
    <?php
        }
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgXvCK71rlegVtxwreXhQg_cXMey_FHgU&callback=initMap" async
        defer></script>
    <?php
        if (!isset($_SESSION['username'])) {
            echo '<script src="./js/index.js"></script>';
        } else {
            echo '<script src="./js/index_admin.js"></script>';
        }
    ?>

    <script src="./js/login.js"></script>
</body>

</html>