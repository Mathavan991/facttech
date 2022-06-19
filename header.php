<?php
include('db_config.php');
session_start();

$user_check = $_SESSION['login_user'];

$query = "SELECT username from tb_user where username = '$user_check'";
$sql = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($sql);
$login_session = $row['username'];

if(!isset($_SESSION['login_user'])){
header("location:index.php"); // Redirecting To Home Page
}
?>

<!DOCTYPE html>
<html lang="zxx">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Factura</title>

     <link rel="icon" href="img/favicon.ico" type="image/png"> 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- themefy CSS -->
    <link rel="stylesheet" href="vendors/themefy_icon/themify-icons.css" />
    <!-- swiper slider CSS -->
    <link rel="stylesheet" href="vendors/swiper_slider/css/swiper.min.css" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="vendors/select2/css/select2.min.css" />
    <!-- select2 CSS -->
    <link rel="stylesheet" href="vendors/niceselect/css/nice-select.css" />
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="vendors/owl_carousel/css/owl.carousel.css" />
    <!-- gijgo css -->
    <link rel="stylesheet" href="vendors/gijgo/gijgo.min.css" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="vendors/font_awesome/css/all.min.css" />
    <link rel="stylesheet" href="vendors/tagsinput/tagsinput.css" />
    <!-- datatable CSS -->
    <link rel="stylesheet" href="vendors/datatable/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="vendors/datatable/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="vendors/datatable/css/buttons.dataTables.min.css" />
    <!-- text editor css -->
    <link rel="stylesheet" href="vendors/text_editor/summernote-bs4.css" />
    <!-- morris css -->
    <link rel="stylesheet" href="vendors/morris/morris.css">
    <!-- metarial icon css -->
    <link rel="stylesheet" href="vendors/material_icon/material-icons.css" />

    <!-- menu css  -->
    <link rel="stylesheet" href="css/metisMenu.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/colors/default.css" id="colorSkinCSS">
    
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
    
    <link rel="icon" href="img/favicon.ico" type="image/png">
    
    
</head>
<!-- main content part here -->
 <body class="crm_body_bg">
 <!-- sidebar  -->
 <!-- sidebar part here -->
<nav class="sidebar">
    <div class="logo d-flex justify-content-between">
        <a href="dashbord.php"><img src="img/logo_1.png" alt=""></a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    <ul id="sidebar_menu">
        <li class="mm-active">
          <a   href="dashbord.php"  aria-expanded="false">
          <!-- <i class="fas fa-th"></i> -->
          <img src="img/menu-icon/1.svg" alt="">
            <span>Dashboard</span>
          </a>
          

        </li>

        <li class="">
          <a    href="product_list.php" aria-expanded="false">
            <img src="img/menu-icon/2.svg" alt="">
            <span>Product List</span>
          </a>
          
        </li>

        <li class="">
          <a    href="add_invoice.php" aria-expanded="false">
            <img src="img/menu-icon/3.svg" alt="">
            <span>Invoice List</span>
          </a>
         
        </li>
        <li class="">
          <a  href="add_invoice.php?add=1" aria-expanded="false">
            <img src="img/menu-icon/4.svg" alt="">
            <span>Create Invoice</span>
          </a>
         
        </li>
        <li class="">
          <a  href="history.php" aria-expanded="false">
            <img src="img/menu-icon/6.svg" alt="">
            <span>History</span>
          </a>
          
        </li>

<!--
        <li class="">
          <a   class="has-arrow" href="#" aria-expanded="false">
            <img src="img/menu-icon/4.svg" alt="">
            <span>UI Component</span>
          </a>
          <ul>
            <li><a href="#">Elements</a>
                <ul>
                    <li><a href="buttons.php">Buttons</a></li>
                    <li><a href="dropdown.php">Dropdowns</a></li>
                    <li><a href="Badges.php">Badges</a></li>
                    <li><a href="Loading_Indicators.php">Loading Indicators</a></li>
                </ul>
            </li>
            <li><a href="#">Components</a>
                <ul>
                    <li><a href="notification.php">Notifications</a></li>
                    <li><a href="progress.php">Progress Bar</a></li>
                    <li><a href="carousel.php">Carousel</a></li>
                    <li><a href="cards.php">cards</a></li>
                    <li><a href="Pagination.php">Pagination</a></li>
                </ul>
            </li>
          </ul>
        </li>

        <li class="">
          <a   class="has-arrow" href="#" aria-expanded="false">
            <img src="img/menu-icon/5.svg" alt="">
            <span>Widgets</span>
          </a>
          <ul>
            <li><a href="chart_box_1.php">Chart Boxes 1</a></li>
            <li><a href="profilebox.php">Profile Box</a></li>
          </ul>
        </li>

        <li class="">
          <a   class="has-arrow" href="#" aria-expanded="false">
            <img src="img/menu-icon/6.svg" alt="">
            <span>Forms</span>
          </a>
          <ul>
            <li><a href="#">Elements</a>
                <ul>
                    <li><a href="data_table.php">Data Tables</a></li>
                    <li><a href="bootstrap_table.php">Grid Tables</a></li>
                    <li><a href="datepicker.php">Date Picker</a></li>
                </ul>
            </li>
            <li><a href="#">Widgets</a>
                <ul>
                    <li><a href="Input_Selects.php">Input Selects</a></li>
                    <li><a href="Input_Mask.php">Input Mask</a></li>
                </ul>
            </li>
          </ul>
        </li>

        <li class="">
          <a   class="has-arrow" href="#" aria-expanded="false">
            <img src="img/menu-icon/7.svg" alt="">
            <span>Charts</span>
          </a>
          <ul>
            <li><a href="chartjs.php">ChartJS</a></li>
            <li><a href="apex_chart.php">Apex Charts</a></li>
            <li><a href="chart_sparkline.php">chart sparkline</a></li>
          </ul>
        </li>
-->

      </ul>
    
</nav>
<!-- sidebar part end -->
 <!--/ sidebar  -->
<section class="main_content dashboard_part">
        <!-- menu  -->
    <div class="container-fluid no-gutters">
        <div class="row">
            <div class="col-lg-12 p-0">
                <div class="header_iner d-flex justify-content-between align-items-center">
                    <div class="sidebar_icon d-lg-none">
                        <i class="ti-menu"></i>
                    </div>
                    <div class="serach_field-area">
                            <div class="search_inner">
                                
                            </div>
                        </div>
                    <div class="header_right d-flex justify-content-between align-items-center">
                        <div class="header_notification_warp d-flex align-items-center">
                            
                        </div>
                        <div class="profile_info">
                            <img src="img/client_img.png" alt="#">
                            <div class="profile_info_iner">
                                <p>Welcome Admin!</p>
                                <h5>Travor James</h5>
                                <div class="profile_info_details">
                                    <a href="profilebox.php">My Profile <i class="ti-user"></i></a>
                                    
                                    <a href="signout.php">Log Out <i class="ti-shift-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ menu  -->    