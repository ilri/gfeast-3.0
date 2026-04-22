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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> VC Actor Type</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="vc_actor_id" class='vc_actor_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>VC Actor Type </label>
                  <input type="text" name="vc_actor_name" class="name form-control" placeholder="Enter VC Actor Type here" >
                  <span class="name_err"></span>
                </div>
               <!--  <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="latitude"  class="desc form-control" placeholder="Enter Description here" value="<?php if(!empty($vc_actor)){ $vc_actor[0]->lat;}?>"><span class="desc_err"></span>
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
          <a class="btn btn-md btn-round btn-success pull-right addvc_actor" href="javascript:void(0);">
            <i class="fa fa-plus addvc_actor" aria-hidden="true"></i> VC Actor Type
          </a>
          <h5 class="card-title">VC Actor Type Details</h5>
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
                <?php  foreach ($vc_actor_type as $key =>  $vc_actor) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $vc_actor->vc_actor_type;?></td>
                     <td class="text-truncate"><?php echo $vc_actor->first_name.' '.$vc_actor->last_name;?></td>
                    <td style="display:none;" class="status_<?php echo $vc_actor->vc_actor_id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $vc_actor->status;?>">
                      <?php if($vc_actor->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $vc_actor->vc_actor_id;?>">
                      <?php if($vc_actor->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $vc_actor->vc_actor_id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $vc_actor->vc_actor_id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $vc_actor->vc_actor_id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $vc_actor->vc_actor_id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td class="text-truncate">
                      <a href="javascript:void(0);" class="edit" id="<?php echo $vc_actor->vc_actor_id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subvc_actor/<?php echo $specific->vc_actor_id;?>">
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
      var vc_actor_id=$(this).attr('id');
      var vc_actor_status=$(this).parent().prev().find('.status').val();
      if(vc_actor_id!="" && vc_actor_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/vc_actor_delete',
          type : 'POST',
          dataType:'json',
          data : {
            vc_actor_id:vc_actor_id,
            status:vc_actor_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+vc_actor_id).html("");
              $('#test tbody').find('.status_'+vc_actor_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+vc_actor_id).removeClass('fa fa-toggle-off');
              $('.togg'+vc_actor_id).addClass('fa fa-toggle-on "togg'+vc_actor_id+'"').css("color","green");
            }
            else{
              $('.status_'+vc_actor_id).html("");
              $('#test tbody').find('.status_'+vc_actor_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+vc_actor_id).removeClass('fa fa-toggle-on');
              $('.togg'+vc_actor_id).addClass('fa fa-toggle-off "togg'+vc_actor_id+'"').css("color","red");
            }
          },
        });
      }
    });
    $('.addvc_actor').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var vc_actor_id=$('.vc_actor_id').val(); 
      var vc_actor_name=$('.name').val();
      var error=0;
      if(vc_actor_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter vc_actor name').css('color','red');
      }
      else
      {
      if(vc_actor_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addvc_actor",
          type:'post',
          dataType:'json',
          data:{
            vc_actor_id:vc_actor_id,
            vc_actor_name:vc_actor_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(vc_actor_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> VC Actor Type Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>VC Actor Type Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(vc_actor_id==""){
              $('#msg').html("Unable to Insert vc_actor ");
            }
            else{
              $('#msg').html("Unable to Update vc_actor");
            }
          }
        });
      }
      }
    });

    $('.edit').click(function(){
      $('.add').show();

      var vc_actor_id=$(this).attr('id');
      if(vc_actor_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/vc_actor_edit",
          type:'post',
          dataType:'json',
          data:{
           vc_actor_id:vc_actor_id,
          },
          success:function(data){
            if(data.status=='1'){
              data.vc_actor_type.forEach(function(vc_actor,index){
              $('.vc_actor_id').val(vc_actor.vc_actor_id);
              $('.name').val(vc_actor.vc_actor_type);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(vc_actor_id==""){
              $('#msg').html("Unable to Insert vc_actor ");
            }
            else{
              $('#msg').html("Unable to Update vc_actor");
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
