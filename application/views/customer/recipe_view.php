<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Kitchen Kits</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/img/KKIcon.png');?>">
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/KKIcon.png');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/font-awesome/css/font-awesome.min.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/fontawesome-stars.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/fontawesome-stars-o.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/AdminLTE.min.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/assets/css/recipe-view.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="<?php echo base_url();?>">
        <img src="<?php echo base_url('/assets/img/newNav.png'); ?>" alt="" width="140px" height="50px">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto nav-des">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url();?>">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('customer/view_region');?>">Recipes</a>
          </li>
        </ul>
        <ul class="navbar-nav nav-des">
          <?php
            if (isset($_SESSION['logged_in'])) {
              if ($_SESSION['utype'] == 3) {
                ?>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url('customer/view_profile');?>"><?php echo $_SESSION['user']; ?></a>
                  </li>
                <?php
              }
              elseif ($_SESSION['utype'] == 2) {
                ?>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url('branch');?>"><?php echo $_SESSION['user']; ?></a>
                  </li>
                <?php
              }
              else{
                ?>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo site_url('admin');?>"><?php echo $_SESSION['user']; ?></a>
                  </li>
                <?php
              }
            }
            else{
              ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo site_url('login');?>">Sign In</a>
                </li>
                <li id="sign-up" class="nav-item">
                  <a class="nav-link" href="<?php echo site_url('register');?>">Sign Up</a>
                </li>
              <?php
            }
          ?>
        </ul>
      </div>
    </nav>

    <div class="container padding">
      <div class="">
        <h5><a href="<?php echo site_url('customer/browse_recipe'.'?id='.$recipe_info[0]->re_cid);?>" class="back-arrow"><span class="fa fa-arrow-left"></span> Recipe Selection</a></h5>
      </div>
      <br>
      <div class="row">
        <div class="col-lg-6">
          <h2><?php echo $recipe_info[0]->re_name; ?></h2>
          <hr>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2">
                <p>
                  <strong>SERVINGS</strong>&nbsp;&nbsp;&nbsp;
                </p>
              </div>
              <div class="col-md-4">
                <?php echo $recipe_info[0]->re_serves; ?>&nbsp;&nbsp;&nbsp;
              </div>
              <div class="col-md-2">
                <strong>RATINGS</strong>
              </div>
              <div class="col-md-2">
                <select class="recipe_rating" id="reciperating<?php echo $recipe_info[0]->re_id;?>" recipereview-id="<?php echo $recipe_info[0]->re_id;?>" data-recipe-rating="<?php echo round($recipe_info[0]->average, 1);?>" autocomplete="off">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
              <div class="col-md-2">
                <?php echo $recipe_info[0]->total; ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <p>
                  <strong>COOKING TIME</strong>&nbsp;&nbsp;&nbsp;
                </p>
              </div>
              <div class="col-md-4"><?php echo $recipe_info[0]->re_cooktime; ?> Minutes&nbsp;&nbsp;&nbsp;</div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-lg-12">
              <h4>Ingredients</h4>
              <ul class="list-group ingr">
                <?php
                  if ($recipe_ings!=NULL) {
                    foreach ($recipe_ings as $rings) {
                      echo '<li class="list-group-item">'.$rings->ig_amount.' '.strtolower($rings->ig_unit).'  '.$rings->ig_name.'</li>';
                    }
                  }
                ?>
              </ul>
            </div>
            <?php
              if (isset($_SESSION['logged_in']) && $_SESSION['utype'] == 3) {
                ?>
                  <h4 id="pads" class="product-description" style="margin-left: 15px">Order Quantity</h4>
                  <div class="col-lg-12 ingr padding">
                    <div class="input-group">
                      <span class="input-group-btn">
                        <button type="button" id="sub_qty" class="btn btn-md btn-flat btn-margin" disabled><i class="fa fa-minus"></i></button>
                      </span>
                      <input  type="text" id="val_cont" class="form-control btnn-qty" readonly>
                      <button type="button" id="add_qty" class="btn btn-md btn-flat btn-margina"><i class="fa fa-plus"></i></button>
                    </div>
                    <input type="hidden" id="recipe_id" value="<?php echo $recipe_info[0]->re_id; ?>">
                    <button type="button" id="addTo_cart" class="btn btn-dark btn-cart">Add to Cart</button>
                  </div>
                <?php
              }
            ?>
          </div>
        </div>
        <div class="col-lg-6" style="padding-left: 85px">
          <img src="<?php echo base_url('Recipe_Folder/'.$recipe_info[0]->re_name.'/'.$recipe_info[0]->re_img); ?>" alt="" height="300px" width="470px">
        </div>
      </div>
    </div>
    <div class="container-fluid alternate">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <h4 style="margin-top: 20px">Directions</h4>
              <p class="p-style"><?php echo $recipe_info[0]->re_instruc; ?></p>
            </div>
          </div>
        </div>
    </div>
    <div class="container-fluid">
      <div class="container padding">
        <h4>Share Your Feedback</h4>
        <br>
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h5><strong>Reviews</strong></h5>
              <div id="review-scroll">
                <?php
                  if ($recipe_rts!=NULL) {
                    foreach ($recipe_rts as $key => $rts) {
                      echo '<div class="container-fluid shade">
                        <h6>review by: '.$rts->cu_fname.' '.$rts->cu_lname.'</h6>
                        <p>'.date('M d, Y - g:i a', strtotime($rts->cdate)).'</p>
                        <select class="revrates" id="example-fontawesome'.$rts->ra_id.'" revrate-id="'.$rts->ra_id.'" data-revrates="'.$rts->rate.'" autocomplete="off">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                        </select>
                        <p>'.$revs[$key]->co_me.'</p>
                      </div>';
                    }
                  }
                ?>
              </div>
            </div>
            <?php
              if (isset($_SESSION['logged_in']) && $_SESSION['utype'] == 3) {
                if ($myrate!=NULL) {
                  ?>
                    <div class="col-md-6">
                      <div class="container-fluid border">
                        <form>
                          <h5 style="font-weight: bold;margin-top: 10px">My Rate & Review On <?php echo $recipe_info[0]->re_name; ?></h5>
                          <div id="success_msg" style="font-size: 12px; color: green; display: none;"></div>
                          <div class="stars stars-example-fontawesome">
                            <select id="myrating" name="rating" autocomplete="off" data-myrating="<?php echo $myrate[0]->rate?>">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                            </select>
                          </div>
                          <h6>Review Detail</h6>
                          <div class="form-group">
                             <textarea style="resize: none;" class="form-control" rows="3" readonly><?php echo $myrev[0]->co_me?></textarea>
                          </div>
                        </form>
                      </div>
                    </div>
                  <?php
                }else{
                  ?>
                    <div class="col-md-6">
                      <div class="container-fluid border">
                        <form>
                          <h5 style="font-weight: bold;margin-top: 10px">Rate & Review <?php echo $recipe_info[0]->re_name; ?></h5>
                          <div id="success_msg" style="font-size: 12px; color: green; display: none;"></div>
                          <div class="stars stars-example-fontawesome">
                            <select id="example-fontawesome" name="rating" autocomplete="off">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                            </select>
                          </div>
                          <h6>Review Detail</h6>
                          <div class="form-group">
                             <textarea style="resize: none;" class="form-control" id="comment" rows="3" placeholder="Type here..."></textarea>
                          </div>
                          <div id="error_msg" style="font-size: 12px; color: red; display: none;"></div>
                          <input type="hidden" id="re_id" value="<?php echo $recipe_info[0]->re_id; ?>">
                          <button type="button" class="btn post-btn" id="submit_review">Publish</button>
                        </form>
                      </div>
                    </div>
                  <?php
                }
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="message">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5><b>Add To My Cart</b></h5>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <div class="alert alert-success" id="succ_msg" align="center" style="display: none;"></div>
            </div>
            <div class="form-group">
              <div class="alert alert-warning" id="warn_msg" align="center" style="display: none;"></div>
            </div>
          </div>
          <div class="modal-footer">
            <a href="<?php echo site_url('view_cart');?>" class="btn btn-sm bg-orange">GO TO CART</a>
            <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <footer class="container-fluid navbar-fixed-bottom">
      <div class="container">
        <h6 style="color: #fff">Copyright &copy; 2019 RLC Company. All Rights Reserved</h6>
      </div>
    </footer>
    <script src="<?php echo base_url('assets/bower_components/jquery/dist/jquery.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets/js/jquery.barrating.js');?>"></script>
    <script src="<?php echo base_url('assets/js/kitchenkitsrating.js');?>"></script>
    <script src="<?php echo base_url('assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/popper.min.js"></script>
    <script type="text/javascript">
      $(function(){
        $('.modal').on('hidden.bs.modal', function(){
          $('#succ_msg').css('display', 'none');
          $('#warn_msg').css('display', 'none');
        });
        $('#review-scroll').slimScroll({
          height: '300px'
        });

        document.getElementById("val_cont").value = 1;
        $('#sub_qty').on('click', function(){
          var val = $('#val_cont').val();
          if (val==2) {
            $('#sub_qty').prop("disabled", true);
          }
          var dif = val - 1;
          document.getElementById("val_cont").value = dif;
        });

        $('#add_qty').on('click', function(){
          $('#sub_qty').prop("disabled", false);
          var val = $('#val_cont').val();
          var sum = (val*1) + 1;
          document.getElementById("val_cont").value = sum;
        });

        $('#addTo_cart').on('click', function(){
          var qty = $('#val_cont').val();
          var re_id = $('#recipe_id').val();
          $.ajax({
            type: 'post',
            url: "<?php echo site_url('customer/add_to_cart'); ?>",
            data: {
              quantity: qty,
              recipe_id: re_id
            },
            dataType: 'JSON',
            success: function(data){
              $('#message').modal('show');
              if (data.status) {
                $('#warn_msg').css('display', 'none');
                $('#succ_msg').css('display', 'block');
                $('#succ_msg').html(data.notif);
              }else{
                $('#succ_msg').css('display', 'none');
                $('#warn_msg').css('display', 'block');
                $('#warn_msg').html(data.notif);
              }
            },
            error: function(data){
              console.log(data);
              alert('ERROR!');
            }
          });
        });

        $('#submit_review').on('click', function(){
          var rate = $("[name='rating']").val();
          var comm = $('#comment').val();
          var re_id = $('#re_id').val();
          $.ajax({
            type: 'post',
            url: "<?php echo site_url('customer/submit_rating_and_review'); ?>",
            data: {
              rate: rate,
              review: comm,
              re_id: re_id
            },
            dataType: 'JSON',
            success: function(data){
              if (data.status) {
                $('#success_msg').css('display', 'block');
                $('#success_msg').html(data.msg);
              }else{
                $('#error_msg').css('display', 'block');
                $('#error_msg').html(data.notif);
              }
            },
            error: function(data){
              console.log(data);
              alert('ERROR!');
            }
          });
        });

      });
    </script>
</body>
</html>
