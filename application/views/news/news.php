<div class="app-content content" style="margin-left: 0px;">
    <style type="text/css">
    .letter_avatar_small_icon{
        color: #FFFFFF;
        font-size: 18px;
        height: 30px; width: 30px;
        float: left;
        margin: 2px;
        text-align: center;
        border-radius: 50%;
        padding-top: 1px;
    }
    .letter_avatar_medium_icon{
        color: #FFFFFF;
        font-size: 30px;
        height: 50px; width: 50px;
        float: left;
        margin: 2px;
        text-align: center;
        border-radius: 50%;
        padding-top: 1px;
    }

    .usertext_color {
        color: #1e9ff2;
        font-weight: 500;
    }

    .hide {
        display: none;
    }
    label{
        font-weight: bold;
    }
    #mapModal {
        padding-top: 10%;
    }
    img {
        width: 100%;
    }

    #lazyloading {
        height: 60px !important;
    }
</style>
<div class="content-wrapper">
    <div class="content-body">
        <section>
            <div class="row">
               <div class="col-md-12" id="ajax_response"></div>
            </div>
            <div class="row">
            		<div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Project</label>
                            <select class="form-control filter" name="project" data-filter_type="project">
                                <option value="all">All</option>
                                <?php foreach ($projects as $pkey => $project) { ?>
                                    <option value="<?php echo $project['project_id']; ?>"><?php echo $project['project_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Country</label>
                            <select class="form-control filter" name="country" data-filter_type="country">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-1">
                            <label>State</label>
                            <select class="form-control filter" name="state" data-filter_type="state">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-1">
                            <label>District</label>
                            <select class="form-control filter" name="district" data-filter_type="district">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-1">
                            <label>Centre</label>
                            <select class="form-control filter" name="centre" data-filter_type="centre">
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
            		<div class="col-md-3"></div>
                <div class="col-md-6 feed_body alldata">
                    <?php if(count($all_news) > 0) {
                        foreach ($all_news as $nkey => $news) {
                            $background_colors = array('#000066', '#004080', '#009900', '#ff661a', '#ffc34d');
                            $rand_background = $background_colors[array_rand($background_colors)];
                            ?>
                            <div class="card shadow-none feed">
                                <div class="catd-body">
                                    <div class="row p-2">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-lg-4 col-3">
                                                    <?php if($news['user_data'][0]['user_image'] != 'default.png'){ ?>
                                                        <img src="<?php echo base_url(); ?>uploads/user/<?php echo $news['user_data'][0]['user_image']; ?>" alt="<?php echo $news['user_data'][0]['user_name']; ?>" class="img-fluid rounded-circle width-50">
                                                    <?php } else { ?>
                                                        <div class="letter_avatar_medium_icon" <?php echo 'style="background-color:'.$rand_background.'"'; ?>><?php echo substr($news['user_data'][0]['user_name'],0,1);?></div>
                                                    <?php  } ?>
                                                </div>
                                                <div class="col-lg-8 col-7 p-0">
                                                    <h5 class="m-0 usertext_color"><?php echo $news['user_data'][0]['user_name']; ?></h5>
                                                    <p>Updated on <?php echo $news['upload_date']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="write-post">
                                        <div class="col-sm-12 px-2">
                                            <div class="row locations">
                                                <div class="col-md-12 mt-2">
                                                    <h6 class="title">
                                                        <?php if($news['post_location'] != null) { echo $news['post_location']['project_name'].' > '.$news['post_location']['name'].' > '.$news['post_location']['state_name'].' > '.$news['post_location']['district_name'].' > '.$news['post_location']['centre_name']; } ?>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class='row images' style="padding-left: 20px; padding-right: 20px;">
                                                <?php switch (count($news['post_image'])) {
                                                    case 1:
                                                    echo "<a href='".base_url('uploads/survey/'.$news['post_image'][0]['file_name'])."' class='col-md-12' style='background-image:url(\"".base_url('uploads/survey/'.$news['post_image'][0]['file_name'])."\"); background-position:center; height:400px; background-size:cover;'>
                                                    <img class='hide' src='".base_url('uploads/survey/'.$news['post_image'][0]['file_name'])."' alt='...' id=''>
                                                    </a>"; 
                                                    break;

                                                    case 2:
                                                    foreach ($news['post_image'] as $key => $imgage) {
                                                       echo "<a href='".base_url('uploads/survey/'.$imgage['file_name'])."' class='col-md-6' style='background-image:url(\"".base_url('uploads/survey/'.$imgage['file_name'])."\"); background-position:center; height:300px; background-size:cover; border:1px solid #FFFFFF;'>
                                                       <img class='hide' src='".base_url('uploads/survey/'.$imgage['file_name'])."' alt='...' id=''>
                                                       </a>";
                                                   }
                                                   break;
                                                   case 3:
                                                   foreach ($news['post_image'] as $key => $imgage) { ?>
                                                       <a class="col-md-<?php echo ($key == 0) ? 12 : 6; ?>" href="<?php echo base_url(); ?>uploads/survey/<?php echo $imgage['file_name'];?>" style="background-image:url('<?php echo base_url('uploads/survey/'.$imgage['file_name']); ?>'); background-position:center; height:200px; background-size:cover; border:1px solid #FFFFFF;">
                                                          <img class="hide" src="<?php echo base_url(); ?>uploads/survey/<?php echo $imgage['file_name'];?>" alt="..." id="">
                                                      </a>
                                                  <?php }
                                                  break;

                                                  default:
                                                  foreach ($news['post_image'] as $key => $imgage) {
                                                   if($key < 3) { ?>
                                                      <a class="col-md-<?php echo ($key == 0) ? 12 : 6; ?>" href="<?php echo base_url(); ?>uploads/survey/<?php echo $imgage['file_name'];?>" style="background-image:url('<?php echo base_url('uploads/survey/'.$imgage['file_name']); ?>'); background-position:center; height:200px; background-size:cover; border:1px solid #FFFFFF;">
                                                         <img class="hide" src="<?php echo base_url(); ?>uploads/survey/<?php echo $imgage['file_name'];?>" alt="..." id="">
                                                         <?php if($key == 2) {
                                                            echo "<p style='color:white; font-size:50px; text-align:center; padding-top:50px; text-shadow: 2px 2px 4px #000000;'>+".(count($news['post_image'])-3)."</p>";
                                                        } ?>
                                                    </a>
                                                <?php } else { ?>
                                                  <a class="hide" href="<?php echo base_url(); ?>uploads/survey/<?php echo $imgage['file_name'];?>">
                                                     <img class="hide" src="<?php echo base_url(); ?>uploads/survey/<?php echo $imgage['file_name'];?>" alt="..." id="">
                                                 </a>
                                             <?php } 
                                         }
                                         break;
                                     } ?>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12" style="padding: 20px;">
                                        <?php foreach ($news['post_data'] as $key => $visit) { ?>
                                           <h6><strong><?php echo $visit['label']; ?></strong>: <?php echo $visit['value']; ?></h6>
                                       <?php } ?>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           <?php } } else { ?>
           	<div class="card shadow-none feed">
	            <div class="card-body no_feed_found">
	              No record found.
	           </div>
	         </div>
        <?php } ?>
    </div>
</div>
</section>
</div>
</div>
</div>

<script type="text/javascript">
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    $(function(){
        //INITIALIZE COLORBOX
        function colorbox(){
         $('.images').each(function(index) {
            $(this).find('img').parent('a').colorbox({
               rel:"group"+index,
               width:"90%",
               height:"90%",
               top:"10px",
               fixed:true
            });
         });
        }
        colorbox();

        //SET ID AND TYPE OF FEED IN PARENT DIV
        $('.alldata').data('id', '');
        $('.alldata').data('type', 'all');
        $('.alldata').data('filter_on', 'all');

        $('.filter').on('change', function(){
            $elem = $(this);
            var project_id = $('select[name="project"]').val();
            var country_id = $('select[name="country"]').val();
            var state_id = $('select[name="state"]').val();
            var district_id = $('select[name="district"]').val();
            var centre_id = $('select[name="centre"]').val();
            var filter_type = $elem.data('filter_type');
            var event = 'change';
            var limitStart = 0;
            var query_data = {project_id : project_id, country_id:country_id, state_id:state_id, district_id : district_id, centre_id:centre_id, filter_type : filter_type, event:event, limitStart:limitStart};
            query_data[csrfName] = csrfHash;
            $('.feed_body').empty();
            $('.feed_body').html(`<div class="col-md-12 text-center" id="lazyloading">
            <img src="<?php echo base_url(); ?>include/app-assets/images/measure_loader.svg" style="height:60px !important; width:60px;">
            <h3>Please wait, filtering data...</h3>
            </div>`);
            $.ajax({
                url : '<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/view_news',
                dataType : 'json',
                type : 'post',
                data : query_data,
                complete: function(data) {
                  var csrfData = JSON.parse(data.responseText);
                  if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
                      $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
                  }
                  csrfName = csrfData.csrfName;
                  csrfHash = csrfData.csrfHash;
                },
                error:function(){
                   $('#ajax_response').html('<p style="padding:10px;" class="red-800">Could not conncet to server. Please check your internet connection and try again.</p>');
                },
                success : function(response){
                   //CALL FUNCTION TO PRINT RESPONSE DATA TO VIEW
                   get_success(response, event);
                   if(filter_type == 'project'){
                        if(response.countries != undefined){
                            var OPTIONS = '<option value="all">All</option>';
                            response.countries.forEach(function(country, index){
                            OPTIONS += '<option value="'+country.country_id+'">'+country.name+'</option>';
                            });
                            $('select[name="country"]').html(OPTIONS);
                            $('select[name="state"]').html('<option value="all">All</option>');
                            $('select[name="district"]').html('<option value="all">All</option>');
                            $('select[name="centre"]').html('<option value="all">All</option>');
                        }
                   }

                   if(filter_type == 'country') {
                        if(response.states != undefined){
                          var OPTIONS = '<option value="all">All</option>';
                          response.states.forEach(function(state, index){
                             OPTIONS += '<option value="'+state.state_id+'">'+state.state_name+'</option>';
                          });
                          $('select[name="state"]').html(OPTIONS);
                          $('select[name="district"]').html('<option value="all">All</option>');
                          $('select[name="centre"]').html('<option value="all">All</option>');
                        }
                   }

                   if(filter_type == 'state'){
                        if(response.districts != undefined){
                          var OPTIONS = '<option value="all">All</option>';
                          response.districts.forEach(function(dist, index){
                             OPTIONS += '<option value="'+dist.district_id+'">'+dist.district_name+'</option>';
                          });

                          $('select[name="district"]').html(OPTIONS);
                          $('select[name="centre"]').html('<option value="all">All</option>');
                        }
                   }

                   if(filter_type == 'district'){
                        if(response.centres != undefined){
                            var OPTIONS = '<option value="all">All</option>';
                            response.centres.forEach(function(centre, index){
                             OPTIONS += '<option value="'+centre.centre_id+'">'+centre.centre_name+'</option>';
                            });

                            $('select[name="centre"]').html(OPTIONS);
                        }
                   }
               }
           });
        });

        //WHEN PAGE SCROLLED TO BOTTOM. LAZYLOAD OF FEED.
        wait = false;
        $(window).scroll(function() {
         var no_feed_found = $('body').find('.no_feed_found').length;
         if($(window).scrollTop() + $(window).height() >= $(document).height()-1 && !wait && no_feed_found == 0) {
            var project_id = $('select[name="project"]').val();
            var country_id = $('select[name="country"]').val();
            var state_id = $('select[name="state"]').val();
            var district_id = $('select[name="district"]').val();
            var centre_id = $('select[name="centre"]').val();
            var limitStart = $('body').find(".feed").length;
            var type = $('.alldata').data('type');
            var filter_on = $('.alldata').data('filter_on');
            var event = 'lazyload';
            var id = $('.alldata').data('id');
            var data = {
               project_id:project_id,
               country_id:country_id,
               state_id:state_id,
               district_id:district_id,
               centre_id:centre_id,
               limitStart:limitStart,
               filter_type:type,
               filter_on:filter_on,
               id:id
            }
            data[csrfName] = csrfHash;
            var url = '<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/view_news';
            wait = true;
            $('.feed_body').append('<div id="lazyloading" style="text-align:center;">\
                  <img src="<?php echo base_url(); ?>include/app-assets/images/measure_loader.svg" style="height:60px !important; width:60px;">\
                  <h5 style="text-align:center;">Please wait, loading data...</h5>\
               </div>');
            $.ajax({
               url : url,
               dataType : 'json',
               type : 'post',
               data : data,
               complete: function(data) {
                  var csrfData = JSON.parse(data.responseText);
                  if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
                      $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
                  }
                  csrfName = csrfData.csrfName;
                  csrfHash = csrfData.csrfHash;
                },
               error:function(){
                  $('#ajax_response').html('<p style="padding:10px; background-color:red; color:#FFFFFF; border-radius:4px;">Could not conncet to server. Please check your internet connection and try again.</p>');
               },
               success : function(response){
                    get_success(response, event);
               }
            });
         }
        });

        function get_success(response){
            if(response.length == 0){
                var HTML_DATA = '<h4>'+response.msg+'</h4>';
            } else {
                var HTML_DATA = ``;
                if(response.all_news.length == 0){
                  HTML_DATA += `<div class="card shadow-none feed">\
                     <div class="card-body no_feed_found">\
                        No more feeds to show.\
                     </div>\
                  </div>`;
                } else {
                    response.all_news.forEach(function(news, index){
                        var background_colors =['#000066', '#004080', '#009900', '#ff661a', '#ffc34d'];
                        var color = background_colors[Math.floor(Math.random()*background_colors.length)];
                        var d = new Date(news.visit_date);
                        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        HTML_DATA +=`<div class="card shadow-none feed">
                                        <div class="catd-body">
                                            <div class="row p-2">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-3">`;
                                                            if($.type(news.user_data) == 'array'){
                                                               var user_image = news.user_data[0].user_image;
                                                               var user_id = news.user_data[0].user_id;
                                                               var user_name = news.user_data[0].user_name;
                                                            } else {
                                                               var user_image = news.user_data.user_image;
                                                               var user_id = news.user_data.user_id;
                                                               var user_name = news.user_data.user_name;
                                                            }
                                                            if(user_image != 'default.png'){
                                                                HTML_DATA += `<img src="<?php echo base_url();?>uploads/user/`+user_image+`" alt="" class="img-fluid rounded-circle width-50">`;
                                                            } else {
                                                                HTML_DATA += `<div class="letter_avatar_medium_icon" style="background-color:`+color+`">`+user_name.charAt(0)+`</div>`;
                                                            }
                                                        HTML_DATA += `</div>
                                                        <div class="col-lg-8 col-7 p-0">
                                                            <h5 class="m-0 usertext_color">`+user_name+`</h5>
                                                            <p>Updated on `+news.upload_date+`</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="write-post">
                                                <div class="col-sm-12 px-2">
                                                    <div class="row locations">
                                                        <div class="col-md-12 mt-2">
                                                            <h6 class="title">`;
                                                                if(news.post_location != null){
                                                                    HTML_DATA += `<h6 class="title">`+news.post_location.project_name+` > `+news.post_location.name+` > `+news.post_location.state_name+` > `+news.post_location.district_name+` > `+news.post_location.centre_name+`</h6>`;
                                                                }
                                                        HTML_DATA += `</div>
                                                    </div>
                                                    <div class='row images' style="padding-left: 20px; padding-right: 20px;">`;
                                                        switch(news.post_image.length){
                                                         case 1:
                                                         news.post_image.forEach(function(imgage, index){
                                                            HTML_DATA += '<a href="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" class="col-md-12" style="background-image:url('+site_url+'uploads/survey/'+imgage.file_name+'); background-position:center; height:400px; background-size:cover; border:1px solid #FFFFFF; display:block;">\
                                                            <img class="hide" src="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" alt="..." id="">\
                                                            </a>';
                                                         });
                                                         break;

                                                         case 2:
                                                         news.post_image.forEach(function(imgage, index){
                                                            HTML_DATA += '<a href="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" class="col-md-6" style="background-image:url('+site_url+'uploads/survey/'+imgage.file_name+'); background-position:center; height:300px; background-size:cover; border:1px solid #FFFFFF; display:block;">\
                                                            <img class="hide" src="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" alt="..." id="">\
                                                            </a>';
                                                         });
                                                         break;

                                                         case 3:
                                                         news.post_image.forEach(function(imgage, index){
                                                            HTML_DATA += '<a class="col-md-'+(index == 0 ? '12' :'6')+'" href="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" style="background-image:url('+site_url+'uploads/survey/'+imgage.file_name+'); background-position:center; height:200px; background-size:cover; border:1px solid #FFFFFF; display:block;">\
                                                            <img class="hide" src="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" alt="..." id="">\
                                                            </a>';
                                                         });
                                                         break;

                                                         default:
                                                         news.post_image.forEach(function(imgage, index){
                                                            if(index < 3) { 
                                                               HTML_DATA += '<a class="col-md-'+(index == 0 ? '12' :'6')+'" href="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" style="background-image:url('+site_url+'uploads/survey/'+imgage.file_name+'); background-position:center; height:200px; background-size:cover; border:1px solid #FFFFFF; display:block;">\
                                                               <img class="hide" src="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" alt="..." id="">';
                                                               if(index == 2) {
                                                                  HTML_DATA += '<p style="color:white; font-size:50px; text-align:center; padding-top:50px; text-shadow: 2px 2px 4px #000000;">+'+(news.post_image.length - 3)+'</p>';
                                                               }
                                                               HTML_DATA += '</a>';
                                                            } else {
                                                               HTML_DATA += '<a class="hide" href="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'">\
                                                               <img class="hide" src="<?php echo base_url(); ?>uploads/survey/'+imgage.file_name+'" alt="..." id="">\
                                                               </a>';
                                                            } 
                                                         });
                                                         break;
                                                      }
                                         HTML_DATA += `</div>
                                         <div class="row">
                                            <div class="col-md-12" style="padding: 20px;">`;
                                                news.post_data.forEach(function(visit, index){
                                                     HTML_DATA += '<h6><strong>'+visit.label+'</strong>: '+visit.value+'</h6>';
                                                  });
                                           HTML_DATA += `</div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>`;
                    })
                }
                $('body').find('#lazyloading').addClass('hide').remove();
                if(event == 'change'){
                    $('.feed_body').html(HTML_DATA);
                } else {
                    $('.feed_body').append(HTML_DATA);
                }
                colorbox();
            }
            wait = false;
        }
    })
</script>