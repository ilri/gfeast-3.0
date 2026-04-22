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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Finance Accessed Type</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="financingaccessed_type_id" class='financingaccessed_type_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>Finance Accessed Type </label>
                  <input type="text" name="financingaccessed_type_name" class="name form-control" placeholder="Enter Finance Accessed Type here" >
                  <span class="name_err"></span>
                </div>
               <!--  <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="latitude"  class="desc form-control" placeholder="Enter Description here" value="<?php if(!empty($financingaccessed_type)){ $financingaccessed_type[0]->lat;}?>"><span class="desc_err"></span>
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
          <a class="btn btn-md btn-round btn-success pull-right addfinancingaccessed_type" href="javascript:void(0);">
            <i class="fa fa-plus addfinancingaccessed_type" aria-hidden="true"></i> Finance Accessed Type
          </a>
          <h5 class="card-title">Finance Accessed Type Details</h5>
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
                <?php  foreach ($financingaccessed_type as $key =>  $financingaccessed_type) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $financingaccessed_type->name;?></td>
                    <td style="display: none;" class="status_<?php echo $financingaccessed_type->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $financingaccessed_type->status;?>">
                      <?php if($financingaccessed_type->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $financingaccessed_type->id;?>">
                      <?php if($financingaccessed_type->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $financingaccessed_type->id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $financingaccessed_type->id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $financingaccessed_type->id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $financingaccessed_type->id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit" id="<?php echo $financingaccessed_type->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subfinancingaccessed_type/<?php echo $specific->id;?>">
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
      var financingaccessed_type_id=$(this).attr('id');
      var financingaccessed_type_status=$(this).parent().prev().find('.status').val();
      if(financingaccessed_type_id!="" && financingaccessed_type_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/financingaccessed_type_delete',
          type : 'POST',
          dataType:'json',
          data : {
            id:financingaccessed_type_id,
            financingaccessed_type_status:financingaccessed_type_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+financingaccessed_type_id).html("");
              $('#test tbody').find('.status_'+financingaccessed_type_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i class="fa fa-check"></i>'); 
              $(".togg"+financingaccessed_type_id).removeClass('fa fa-toggle-off');
              $('.togg'+financingaccessed_type_id).addClass('fa fa-toggle-on "togg'+financingaccessed_type_id+'"').css("color","green");
            } 
            else{
              $('.status_'+financingaccessed_type_id).html("");
              $('#test tbody').find('.status_'+financingaccessed_type_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i class="fa fa-close"></i>');
              $(".togg"+financingaccessed_type_id).removeClass('fa fa-toggle-on');
              $('.togg'+financingaccessed_type_id).addClass('fa fa-toggle-off "togg'+financingaccessed_type_id+'"').css("color","red");
            }
          },
        });
      }
    });
    $('.addfinancingaccessed_type').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var financingaccessed_type_id=$('.financingaccessed_type_id').val(); 
      var financingaccessed_type_name=$('.name').val();
      var error=0;
      if(financingaccessed_type_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter financingaccessed_type name').css('color','red');
      }
      else
      {
      if(financingaccessed_type_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addfinancingaccessed_type",
          type:'post',
          dataType:'json',
          data:{
            id:financingaccessed_type_id,
            name:financingaccessed_type_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(financingaccessed_type_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Finance Accessed Type Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Finance Accessed Type Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(financingaccessed_type_id==""){
              $('#msg').html("Unable to Insert financingaccessed_type ");
            }
            else{
              $('#msg').html("Unable to Update financingaccessed_type");
            }
          }
        });
      }
      }
    });
   $('.edit').click(function(){

      var financingaccessed_type_id=$(this).attr('id');
      if(financingaccessed_type_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/financingaccessed_type_edit",
          type:'post',
          dataType:'json',
          data:{
           id:financingaccessed_type_id,
          },
          success:function(data){
            if(data.status=='1'){
              data.financingaccessed_type.forEach(function(financingaccessed_type,index){
              $('.financingaccessed_type_id').val(financingaccessed_type.id);
              $('.name').val(financingaccessed_type.name);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(financingaccessed_type_id==""){
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
