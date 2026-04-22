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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Gender</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="gender_id" class='gender_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>Gender </label>
                  <input type="text" name="gender_name" class="name form-control" placeholder="Enter Gender here" >
                  <span class="name_err"></span>
                </div>
               <!--  <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="latitude"  class="desc form-control" placeholder="Enter Description here" value="<?php if(!empty($gender)){ $gender[0]->lat;}?>"><span class="desc_err"></span>
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
          <a class="btn btn-md btn-round btn-success pull-right addgender" href="javascript:void(0);">
            <i class="fa fa-plus addgender" aria-hidden="true"></i> Gender
          </a>
          <h5 class="card-title">Gender Details</h5>
          <div class="table-responsive">
            <table class="table mb-0" id="test">
              <thead>
                <tr>
                  <th scope="col">Sl.NO</th>
                  <!-- <th scope="col">Training Type</th> -->
                  <th scope="col">Name</th>
                  <th>Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  foreach ($gender as $key =>  $gender) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $gender->type;?></td>
                    <td style="display: none;"class="status_<?php echo $gender->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $gender->status;?>">
                      <?php if($gender->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $gender->id;?>">
                      <?php if($gender->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $gender->id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $gender->id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $gender->id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $gender->id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit" id="<?php echo $gender->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subgender/<?php echo $specific->id;?>">
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
      var gender_id=$(this).attr('id');
      var gender_status=$(this).parent().prev().find('.status').val();
      if(gender_id!="" && gender_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/gender_delete',
          type : 'POST',
          dataType:'json',
          data : {
            id:gender_id,
            gender_status:gender_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+gender_id).html("");
              $('#test tbody').find('.status_'+gender_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>'); 
              $(".togg"+gender_id).removeClass('fa fa-toggle-off');
              $('.togg'+gender_id).addClass('fa fa-toggle-on "togg'+gender_id+'"').css("color","green");
            } 
            else{
              $('.status_'+gender_id).html("");
              $('#test tbody').find('.status_'+gender_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+gender_id).removeClass('fa fa-toggle-on');
              $('.togg'+gender_id).addClass('fa fa-toggle-off "togg'+gender_id+'"').css("color","red");
            }
          },
        });
      }
    });
    $('.addgender').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var gender_id=$('.gender_id').val(); 
      var gender_name=$('.name').val();
      var error=0;
      if(gender_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter gender name').css('color','red');
      }
      else
      {
        $('.name').css('border','1px solid green');
        $('.name_err').html("");
      }
      if(gender_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addgender",
          type:'post',
          dataType:'json',
          data:{
            id:gender_id,
            type:gender_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(gender_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Gender Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Gender Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(gender_id==""){
              $('#msg').html("Unable to Insert gender ");
            }
            else{
              $('#msg').html("Unable to Update gender");
            }
          }
        });
      }
    });

    $('.edit').click(function(){

      var gender_id=$(this).attr('id');
      if(gender_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/gender_edit",
          type:'post',
          dataType:'json',
          data:{
           id:gender_id,
          },
          success:function(data){
            if(data.status=='1'){
              data.gender.forEach(function(gender,index){
              $('.gender_id').val(gender.id);
              $('.name').val(gender.type);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(gender_id==""){
              $('#msg').html("Unable to Insert gender ");
            }
            else{
              $('#msg').html("Unable to Update gender");
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
