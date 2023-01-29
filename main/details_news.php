<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Products</title>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style1.css" />
    </head>
    <body class="light-mode">
        <!-- partial:index.partial.html -->
        <?php
            require_once 'copy/layout.php';
            require_once 'config.php';
            // echo $_GET["news_id"];
            // echo $_REQUEST["news_id"];
            // echo "SELECT * FROM `news` WHERE id = ".$_REQUEST["news_id"];
            $info_news_raw = mysqli_query($conn, "SELECT * FROM `news` WHERE id = ".$_REQUEST["news_id"]) or die('query failed from detail_news');
            $info_news = mysqli_fetch_array($info_news_raw);
            $latest_news_raw = mysqli_query($conn, "SELECT * FROM `news` WHERE id <> ".$_REQUEST["news_id"]." ORDER BY date DESC LIMIT 3") or die('query failed from detail_news');
        ?> 
        <div class="main-content">
                  <div style="border-radius: 20px; text-align: center; background-color: var(--box-color);">
                    <h1><?php echo $info_news['title'];?></h1>
                    <!-- <p style="overflow:hidden;">Release-date: <?php //echo $info_news['date'];?></p> -->
                    <?php echo '<div>Release-date: '.$info_news['date'].'</div>'; ?>
                  </div>

                  <div class="container-fluid padding" style="border-radius: 20px; background-color: var(--box-color); text-align: justify;">
                    <div class="row padding">
                        <div class="col-md-12 col-lg-6" style="margin-top: 25px;">
                        <p><?php echo $info_news['content'];?></p>
                        </div>
                        <div class="col-lg-6" style="align-content: center;">
                           <img style="width:100%;" src="<?php echo "uploaded_img" . "/" . $info_news['img'] ?>" />
                        </div>
                    </div>
                  </div>

                <!-- News -->
                <div style="color: var(--theme-color); margin-top: 50px;">
                  <p style=" margin-bottom: 3px;">Other News</p>                          
                  <div class="container-fluid padding" style="margin-top: 50px; color: var(--theme-color);">
                     <div class="row text-center padding">
                        <?php
                          while($result_all = mysqli_fetch_assoc($latest_news_raw)) { 
                              echo '
                              <div class="col-xs-12 col-sm-6 col-md-3">
                              <a href="/bookstore/details_news.php?news_id=' . $result_all['id'] . '"' . ' style="color: var(--theme-color); border-radius: 10px; width:23%; background-color: var(--box-color);">
                              <img src="uploaded_img/' . $result_all['img'] . '" alt="">
                              <div style="text-align: center; padding-bottom: 10px;">' . $result_all['title'] . '</div>
                              </a>
                              </div>';
                          }
                        ?>
                     </div>
                  </div>
              </div>
              </div>
          </div>
        </div>
        </div>
        <?php include_once('footer.php');  ?>
        <!-- partial -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.1.1/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="js/script1.js"></script>
    </body>
</html>