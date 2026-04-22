<link rel="Stylesheet" href="<?php echo base_url();?>includeout/croppie/croppie.css">
<div class="app-content content" style="margin-left: 0px;">
    <div class="content-wrapper">
      <div class="content-body">
	<div class="page-content container-fluid">
		<link rel="stylesheet" href="<?php echo base_url(); ?>includein/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css">
		<style type="text/css">
			.btn-bs-file{
			    position:relative;
			    height: 30px; width: 30px;
			}
			.btn-bs-file input[type="file"]{
			    position: absolute;
			    top: -9999999;
			    opacity: 0.7;
			    width:0;
			    height:0;
			}
			textarea{
				resize: none;
			}

			.modal-header .close {
		        -ms-flex-order: 2;
		        order: 2;
		        margin-top: -10px;
		    }
		    

		    @media (min-width: 576px){
		        .modal-dialog {
		            max-width: 700px;
		            margin: 1.75rem auto;
		        }
		    }
		</style>
		<!-- Croppie Modal -->
		<div class="modal fade modal-fade-in-scale-up" id="cropperModal" aria-hidden="true"
		aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
			<div class="modal-dialog">
			  <div class="modal-content">
			    <div class="modal-header">
			      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			        <span aria-hidden="true">×</span>
			      </button>
			      <h4 class="modal-title">Select Viewable Portion</h4>
			    </div>
			    <div class="modal-body">
			      <div id="demo-basic"></div>
			    </div>
			    <div class="modal-footer">
			      <button type="button" class="btn btn-sm btn-danger margin-0" id="cancelImage" data-dismiss="modal">Discard Changes</button>
			      <button type="button" class="btn btn-sm btn-success" id="saveImage">Save Changes</button>
			    </div>
			  </div>
			</div>
		</div>
		<!-- End Modal -->

		<div class="row">
			<div class="col-md-12">
				<h4 style="font-weight: bold;">Edit Profile</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="card fixme" style="padding: 10px;">
					<div id="holder"><img src="<?php echo base_url(); ?>uploads/user/<?php echo $this->session->userdata('image'); ?>" style="width: 100%;">
					</div>
	        		<?php echo form_open_multipart('login/change_profile_img/',array('class' => '', 'id' => 'pImgForm', 'name' => 'img_form')); ?>
		        		<label class="btn-bs-file btn btn-md" style="background-color: black; margin-top: -50px;" data-toggle="tooltip" title="Change profile image">
		        			<i class="ft-camera" aria-hidden="true" style="margin-left:-7px; color: #FFFFFF; font-weight: bold;"></i>
	                    	<input id="profile_img" type="file" name="profile_img" accept="image/x-png, image/gif, image/jpeg"/>
	                    </label>
                    <?php echo form_close(); ?>
          			<button class="btn btn-success btn-xs" id="cp_btn">Change Password</button>
				</div>
				<div id="img_err"></div>
			</div>

			<!-- change password modal starts-->
			<div id="myModal" class="modal fade" role="dialog">
			  <div class="modal-dialog">
			    <!-- Modal content-->
			    <div class="modal-content" style="border-radius: 0px;">
			      <div class="modal-header" style="background-color: #6cc00c; border-radius: 0px;">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title" style="color: #FFFFFF;">Change password</h4>
			      </div>
			      <div class="modal-body">
			      	<div id="cp_succ" class="col-md-12"></div>
			      	<div id="cp_modal_body"></div>
			      </div>
			    </div>
			  </div>
			</div>
			<!-- change password modal ends -->

			<div class="col-lg-9">
				<div class="card p-10" style="padding: 10px;">
					<h4 style="font-weight: bold;">Name : <?php echo $profile_details['first_name']; ?> <?php echo $profile_details['last_name']; ?></h4>

					<form method="post" action="<?php echo base_url(); ?>login/profile">
						<label>First Name</label>
						<input type="text" name="first_name" class="form-control" value="<?php echo $profile_details['first_name']; ?>">

						<label>Last Name</label>
						<input type="text" name="last_name" class="form-control" value="<?php echo $profile_details['last_name']; ?>">

						<button type="submit" class="btn btn-success btn-xs">Update</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>

<script src="<?php echo base_url();?>includeout/croppie/croppie.js"></script>

<script type="text/javascript">
$(function(){
	$('#cp_btn').click(function(){
		var HTML = '<form id="change_password" autocomplete="off">\
	      	<div class="col-md-12">\
	      		<label>Old password</label> <span class="text-danger">*</span>\
	      		<input type="password" name="old_pass" autocomplete="off" class="form-control">\
	      		<span id="op" style="color:red; font-size: 11px;"></span>\
	      	</div>\
	      	<div class="col-md-12 mt-20">\
	      		<label>New password</label> <span class="text-danger">*</span>\
	      		<input type="password" name="new_pass" autocomplete="off" class="form-control">\
	      		<span id="np" style="color:red; font-size: 11px;"></span>\
	      	</div>\
	      	<div class="col-md-12 mt-20 mb-20">\
	      		<label>Confirm password</label> <span class="text-danger">*</span>\
	      		<input type="password" name="cnew_pass" autocomplete="off" class="form-control">\
	      		<span id="cnp" style="color:red; font-size: 11px;"></span>\
	      	</div>\
	      	<button type="button" class="btn btn-danger btn-xs pull-right" data-dismiss="modal">Cancel</button>\
	      	<button type="button" class="btn btn-success btn-xs pull-right" style="background-color: #6cc00c; border:1px solid #6cc00c; margin-right: 10px;" id="cpm_btn">Change password</button>\
       	</form>';
		$('#cp_modal_body').html(HTML);
		$('#myModal').modal('show');
	});
});
</script>

<script type="text/javascript">
	// Define global variable ajaxData
	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
	
	$('body').on('click', '#cpm_btn', function(event) {
		var old_pass = $('body').find('input[name="old_pass"]').val();
		var new_pass = $('body').find('input[name="new_pass"]').val();
		var cnew_pass = $('body').find('input[name="cnew_pass"]').val();

		$('body').find('#op').empty();
		$('body').find('#np').empty();
		$('body').find('#cnp').empty();

		ajaxData['old_pass'] = old_pass;
		ajaxData['new_pass'] = new_pass;
		ajaxData['cnew_pass'] = cnew_pass;
		$.ajax({
            url: '<?php echo base_url(); ?>login/change_password/',
            type: 'POST',
            dataType:"json",
            data: ajaxData,
            complete: function(data) {
				var csrfData = JSON.parse(data.responseText);
				ajaxData[csrfData.csrfName] = csrfData.csrfHash;
				if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
					$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
				}
			},
            error: function() {
               	$('body').find('#cp_succ').append('<p style="background-color: #F35462; color: #FFFFFF; padding: 10px;">Could not establish connection to server. Please refresh the page and try again.</p>').delay(5000).fadeOut(400);
            },
            success: function(data) {
            	if(data.status > 0){
                	$('body').find('#op').html(data.old_pass);
                	$('body').find('#np').html(data.new_pass);
                	$('body').find('#cnp').html(data.cnew_pass);
                }
                
                if(data.c_status > 0){
                	$('body').find('#cp_succ').append('<p style="background-color: #6cc00c; color: #FFFFFF; padding: 10px;">'+data.msg+'</p>').delay(5000).fadeOut(400);
                	$('#cp_modal_body').html('');
                	window.location.replace("<?php echo base_url(); ?>auth/logout");
                }
            }
        });

	});
</script>
<script type="text/javascript">
  var sizeerror = 0;
  var myReader = new FileReader();
  var fileTypes = ['jpg', 'jpeg', 'png', 'gif'];  //acceptable file types
  var image_holder = $("#holder");
  var image_err = $("#img_err");

  var basic = $('#demo-basic').croppie({
    viewport: {
      width: 180,
      height: 180
    }
  });
  $("input[name='profile_img']").on('change', function () {
  	image_err.empty();
    if($("input[name='cropimg']").length > 0) $("input[name='cropimg']").remove();
    if (typeof (FileReader) != "undefined") {
      var extension = $(this)[0].files[0].name.split('.').pop().toLowerCase(),//file extension from input file
      isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable type

      //image_holder.empty();
      if($(this)[0].files[0].size > 5242880) {
        sizeerror = 1;
      }
      else {
        sizeerror = 0;
      }

      if (isSuccess && sizeerror === 0) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#cropperModal').modal('show');

          $('#cropperModal').on('shown.bs.modal', function() {
            basic.croppie('bind', {
              url: e.target.result
            });
          });
        }
        //image_holder.show();
        reader.readAsDataURL($(this)[0].files[0]);
      } else if(!isSuccess) {
        image_err.html("<p class='red-800 font-size-16'>Please choose .gif, .png, .jpg, .jpeg file type.</p>");
      } 
      else if(sizeerror > 0) {
        image_err.html("<p class='red-800 font-size-16'>Image size should be between 32KB to 5MB.</p>");
      }
    } else {
      alert("This browser does not support FileReader. Can not show image preview.");
    }
  });

  //Save Cropped Image
  $('#saveImage').on('click', function() {
  	//alert($("input[name='profile_img']").val());
    basic.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (resp) {
    	$('#pImgForm').append('<input type="hidden" value="'+resp+'" name="cropimg" />')
      	$('#pImgForm').submit();
    });
  });
  //Cancel cropped image
  $('#cancelImage').on('click', function() {
    $("input[name='profile_img']").val('');
    if($("input[name='cropimg']").length > 0) $("input[name='cropimg']").remove();
    image_err.html();
  });
</script>
