<?php require_once('templates/headers/opening.tpl.php'); ?>

<!-- Specific Page Data -->
<?php $title = 'FaceRater start page'; ?>
<?php $page = 'login-page';   // To set active on the same id of primary menu ?>
<?php $id_page = 'login-page';   // To set active on the same id of primary menu ?>
<!-- End of Data -->
<?php $layout="middle-layout" ; 
	  $body_extra_class="remove-navbar front-layout"; 
	  $top_menu_extra_class="vd_bg-grey"; 
	  $header="header-front";  
	  
	  $footer = "footer-4"; 
	  $background = "background-login"; 
	  
	  $logo_path = 'img/logo-white.png'; 
    $specific_css[0] = 'css/animate.css';
?>

<?php $navbar_left_config = 0; ?>
<?php $navbar_right_config = 0; ?>
<?php require_once('templates/headers/'.$header.'.tpl.php'); ?>

<div class="content">
<div id="front-1-banner" class="vd_banner vd_bg-white clearfix slide-waypoint" data-waypoint="home" >
  <div class="container">
    <div class="vd_content vd_info-parent clearfix">
      <div class="word-header">
        <h1 class="font-semibold text-center vd_white"><strong>Facial Expression Research</strong></h1>
      </div>
      <div class="word-subheader">
        <h2 class="text-center vd_white">Contribute to Citizen Science!</h2>
      </div>
      <div class="icon-banner icon-4"><img alt="facerater icon" src="img/logo_image_lg.png" /></div>
    </div>
  </div>
</div>

<!-- Middle Content End -->

<?php include_once('templates/footers/scripts.tpl.php'); ?>

<!-- Specific Page Scripts Put Here -->
<?php include('templates/scripts/front-1.tpl.php'); ?>

<!-- Specific Page Scripts END -->

<?php require_once('templates/footers/closing.tpl.php'); ?>
