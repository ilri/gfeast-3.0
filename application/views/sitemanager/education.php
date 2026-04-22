<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
  .btn{
    margin-top: 30px;
      margin-bottom:30px;
  }
  th, td{
    text-align: center;
  }
</style>
<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body">

    <div class="row add">
        <div class="col-md-12">
          <!-- <a class="btn btn-success pull-right" href="<?php echo base_url();?><?php echo $this->uri->segment('1');?>/get_country">Countries List</a> -->
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Education</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="education_id" class='education_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>Education </label>
                  <input type="text" name="education_name" class="name form-control" placeholder="Enter Education here" >
                  <span class="name_err"></span>
                </div>
               <!--  <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="latitude"  class="desc form-control" placeholder="Enter Description here" value="<?php if(!empty($education)){ $education[0]->lat;}?>"><span class="desc_err"></span>
                </div> -->

                <div class="col-md-1">
                  <input class="btn btn-success pull-right" type="submit" name="submit" id="submit" class="submit" value="submit">
                </div>
              </div>
            </form> 
          </div>
        </div>
      </div>

      <div class="card shadow-none" style="margin-top:20px;margin-bottom:90px;">
        <div class="card-body">
          <a class="btn btn-md btn-round btn-success pull-right addeducation" href="javascript:void(0);">
            <i class="fa fa-plus addeducation" aria-hidden="true"></i> Education
          </a>
          <h5 class="card-title">Education Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <!-- <th scope="col">Training Type</th> -->
                  <th scope="col">Name</th>
                  <th scope="col">Added By</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($education as $key =>  $education) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $education->education_name;?></td>
                     <td class="text-truncate" ><?php echo $education->first_name.' '.$education->last_name;?></td>
                    <td style="display: none;"class="status_<?php echo $education->education_id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $education->education_status;?>">
                      <?php if($education->education_status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $education->education_id;?>">
                      <?php if($education->education_status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $education->education_id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $education->education_id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $education->education_id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $education->education_id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit" id="<?php echo $education->education_id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subeducation/<?php echo $specific->education_id;?>">
                        <i class="fa fa-list" style="font-size:25px;"></i>
                      </a> -->
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  $(function(){
    $('.add').hide();

    $('.delete').click(function(){
      var education_id=$(this).attr('id');
      var education_status=$(this).parent().prev().find('.status').val();
      if(education_id!="" && education_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/education_delete',
          type : 'POST',
          dataType:'json',
          data : {
            education_id:education_id,
            education_status:education_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+education_id).html("");
              $('#test tbody').find('.status_'+education_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+education_id).removeClass('fa fa-toggle-off');
              $('.togg'+education_id).addClass('fa fa-toggle-on "togg'+education_id+'"').css("color","green");
            }
            else{
              $('.status_'+education_id).html("");
              $('#test tbody').find('.status_'+education_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
            $(".togg"+education_id).removeClass('fa fa-toggle-on');
              $('.togg'+education_id).addClass('fa fa-toggle-off "togg'+education_id+'"').css("color","red"); }
          },
        });
      }
    });
    $('.addeducation').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    $('#submit').click(function(){
      var education_id=$('.education_id').val(); 
      var education_name=$('.name').val();
      var error=0;
      if(education_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter education name').css('color','red');
      }
      else
      {
      if(education_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addeducation",
          type:'post',
          dataType:'json',
          data:{
            education_id:education_id,
            education_name:education_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(education_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Education Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Education Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(education_id==""){
              $('#msg').html("Unable to Insert education ");
            }
            else{
              $('#msg').html("Unable to Update education");
            }
          }
        });
      }
      }
    });

    $('.edit').click(function(){

      var education_id=$(this).attr('id');
      if(education_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/education_edit",
          type:'post',
          dataType:'json',
          data:{
           education_id:education_id,
          },
          success:function(data){
            if(data.status=='1'){
              data.education.forEach(function(education,index){
              $('.education_id').val(education.education_id);
              $('.name').val(education.education_name);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(education_id==""){
              $('#msg').html("Unable to Insert education ");
            }
            else{
              $('#msg').html("Unable to Update education");
            }
          }
        });

      }
      else
      {
        $("#msg").html("Invalid Edit");
      }
    });
  });
</script>
