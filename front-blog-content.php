<?php require_once('templates/headers/opening.tpl.php'); ?>

<!-- Specific Page Data -->
<?php $title = 'FaceRater forum page'; ?>
<?php $page = 'pages';   // To set active on the same id of primary menu ?>
<!-- End of Data -->

<?php $navbar_left_config = 0; ?>
<?php $navbar_right_config = 0; ?>
<?php require_once('templates/headers/'.$header.'.tpl.php'); ?>

<?php
  if(isset($_GET['user'])) {
    // database server variables
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "facerater";

    $user = $_GET['user'];
    $topic = $_GET['topic'];

    // Create database connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
      die("Connection failed on initial database connection: " . mysqli_connect_error());
    }
    
    $sql = "SELECT * FROM forum WHERE message_id = '$topic'";
    $result = mysqli_query($conn, $sql);
    $rowct = mysqli_num_rows($result);
    $colct = mysqli_num_fields($result);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result, MYSQLI_NUM);
      $msg_pt = $row[0];
      $topic_auth = $row[1];
      $topic_text = $row[2];
      $msg_orig = $row[3];
      $topic_date = $row[6];
      mysqli_free_result($result);
    } else {
      die("No topic found. " . mysqli_connect_error());
    }

    $sql = "SELECT image_id FROM image WHERE username = '$topic_auth'";
    $result = mysqli_query($conn, $sql);
    $rowct = mysqli_num_rows($result);
    $colct = mysqli_num_fields($result);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);
      $img_id = $row[0];
      mysqli_free_result($result);
    } else {
      die("No author image found. " . mysqli_connect_error());
    }

    $sql = "SELECT SUM(upvotes) FROM msg_parent NATURAL JOIN forum WHERE message_parent = '$topic'";
    $result = mysqli_query($conn, $sql);
    $rowct = mysqli_num_rows($result);
    $colct = mysqli_num_fields($result);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result, MYSQLI_NUM);
      $topic_up = $row[0];
      mysqli_free_result($result);
    } else {
      die("No topic found. " . mysqli_connect_error());
    }

    mysqli_close($conn);
  }
?>

<div class="content">
  <div class="clearfix pd-20" >
    <div class="container">
      <div class="vd_content clearfix">
        <div class="row mgtp-20">
          <div class="col-md-8">
            <div class="panel widget light-widget panel-bd-top">
              <div class="panel-heading no-title"> </div>
              <div class="panel-body">
                <h1 class="mgtp--5 font-bold"> Forum</h1>
                <hr/>
                <div class="row blog-info">
                  <h2>Topic: <?php echo $topic_text; ?></h2>
                  <div class="col-sm-3 blog-date font-sm"><i class="fa fa-user  append-icon"></i><span class="vd_soft-grey"> Posted By: <a href="pages-user-profile_pub.php?user=<?php echo $topic_auth; ?>&sender=<?php echo $user; ?>"><?php echo $topic_auth; ?></a></span></div>
                  <div class="col-sm-4 blog-date font-sm"><i class="icon-clock  append-icon"></i><span class="vd_soft-grey"><?php echo $topic_date; ?></span></div> 
                </div>
                <br/>

                <div class="row blog-info">
                  <form class="form-group" action="front-blog-content.php?user=<?php echo $user; ?>&topic=<?php echo $topic; ?>" method="POST" role="form" id="like-form"> 
                    <div class="col-sm-1">
                      <button class="btn vd_btn btn-xs vd_bg-yellow" type="submit" name="submit" value="upvote">Upvote</button>
                    </div>
                    <div class="col-sm-2">
                      <button class="btn vd_btn btn-xs vd_bg-yellow" type="submit" name="submit" value="downvote">Downvote</button>
                    </div>
                    <div class="col-sm-4 blog-date font-sm"><span class="vd_soft-grey"><?php echo $topic_up; ?> Likes</span></div>        
                  </form>
                </div>

                <?php
                $vote = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  $vote = $_POST["submit"];
                }
                if(isset($_POST['submit'])) {
                  
                  // Create database connection
                  $conn = mysqli_connect($servername, $username, $password, $dbname);
                  if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                  } else {
                  }
                  if ($vote == "upvote") {
                    mysqli_query($conn, "UPDATE forum SET upvotes=upvotes+1 WHERE message_id = '$topic'");
                  } else {
                    mysqli_query($conn, "UPDATE forum SET upvotes=upvotes-1 WHERE message_id = '$topic'");
                  }

                  echo "<meta http-equiv='refresh' content='0'>";

                  mysqli_free_result($result);
                  mysqli_close($conn);
                }
                ?>

                <br/>
                <div class="row blog-content">
                  <div class="col-xs-12">
                    <p> <img src="functions/img_display.php?id=<?php echo $img_id; ?>" class="img-left" alt="topic author" width="10%"><?php echo $msg_orig; ?></p>
                  </div>
                </div>
                <br/>
                <div class="row blog-content">
                  <div class="col-xs-12">
                    <h2>Comments</h2>
                    <br/>
                    <div class="col-sm-12">
                      <div class="">
                        <div class="content-list">
                          <div data-rel="scroll">
                            <ul  class="list-wrapper">
                              <?php
                                $conn = mysqli_connect($servername, $username, $password, $dbname);
                                // Check connection
                                if (!$conn) {
                                    die("Connection failed on forum: " . mysqli_connect_error());
                                }

                                $sql = "SELECT * FROM msg_parent NATURAL JOIN forum WHERE message_parent = '$msg_pt' AND message_id != '$msg_pt'";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                  // output data of each row
                                  while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                                      echo '<li class="mgbt-xs-10"> <span class="menu-icon vd_blue"><i class=" fa fa-user"></i></span> <span class="menu-text">' .$row[4]. ' <span class="menu-info"><span class="menu-date"><a href="pages-user-profile_pub.php?user=' .$row[2]. '&sender=' .$user. '">' .$row[2]. '</a> posted on ' .$row[7].'</span></span> </span> </li>';
                                  }
                                }
                                mysqli_free_result($result);
                                mysqli_close($conn);
                              ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- vd_comments -->
                    
                    <div class="vd_comments-form clearfix">
                      <h3>Add a <span>Comment</span></h3>
                      <form class="clearfix" action="functions/post-forum-comment.php?user=<?php echo $user; ?>&pt=<?php echo $msg_pt; ?>" method="POST" id="commentform">
                        <div class="row">
                          <div class="col-md-9">
                            <div class="form-group">
                              <label for="comment">Comment</label>
                              <div class="controls">
                                <textarea id="comment" name="comment" cols="58" rows="10" tabindex="4"></textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <input class="btn vd_btn btn-xs vd_bg-yellow" type="submit" value="Submit">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Panel Widget --> 
            
          </div>
          <!-- col-md-4 -->
          
          <div class="col-md-4">
            <div class="panel widget light-widget panel-bd-top vd_bdt-yellow">
              <div class="panel-heading no-title"> </div>
              <div class="panel-body">
                <h2 class="mgtp--5">Topics</h2>
                <div class="content-list content-image">
                  <ul class="list-wrapper no-bd-btm">
                  <?php
                      $conn = mysqli_connect($servername, $username, $password, $dbname);
                      // Check connection
                      if (!$conn) {
                          die("Connection failed on forum: " . mysqli_connect_error());
                      }

                      $sql = "SELECT * FROM msg_parent NATURAL JOIN forum WHERE message_parent = forum.message_id";
                      $result = mysqli_query($conn, $sql);

                      if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                            echo '<li class="mgbt-xs-10"><i class="fa fa-globe mgr-10 menu-icon"></i></span> <span class="menu-text  font-semibold">' .$row[3]. ' <span class="menu-info"><span class="menu-date"><a href="pages-user-profile_pub.php?user=' .$row[2]. '&sender=' .$user. '">' .$row[2]. '</a> posted on ' .$row[7].'</span></span> </span> </li>';
                        }
                      }
                      mysqli_free_result($result);
                      mysqli_close($conn);
                    ?>
                  </ul>
                </div>
              </div>
            </div>
            <!-- Panel Widget --> 
            
          </div>
          <!-- col-md-4 --> 
          
        </div>
        <!--row --> 
        
      </div>
    </div>
  </div>
</div>
<!-- Middle Content End --> 

<!--
</div></div>-->

<?php require_once('templates/footers/'.$footer.'.tpl.php'); ?>

<!-- Specific Page Scripts Put Here --> 

<!-- Specific Page Scripts END -->

<?php require_once('templates/footers/closing.tpl.php'); ?>
