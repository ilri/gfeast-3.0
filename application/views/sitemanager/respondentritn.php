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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Relationships</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="respondentritn_id" class='respondentritn_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>Relationships </label>
                  <input type="text" name="respondentritn_name" class="name form-control" placeholder="Enter Relationship here" >
                  <span class="name_err"></span>
                </div>
               <!--  <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="latitude"  class="desc form-control" placeholder="Enter Description here" value="<?php if(!empty($respondentritn)){ $respondentritn[0]->lat;}?>"><span class="desc_err"></span>
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
          <a class="btn btn-md btn-round btn-success pull-right addrespondentritn" href="javascript:void(0);">
            <i class="fa fa-plus addrespondentritn" aria-hidden="true"></i> Relationships
          </a>
          <h5 class="card-title">Relationships Details</h5>
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
                <?php  foreach ($respondentritn as $key =>  $respondentritn) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $respondentritn->relationship;?></td>
                    <td style="display: none;" class="status_<?php echo $respondentritn->id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $respondentritn->status;?>">
                      <?php if($respondentritn->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $respondentritn->id;?>">
                      <?php if($respondentritn->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $respondentritn->id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $respondentritn->id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $respondentritn->id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $respondentritn->id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit" id="<?php echo $respondentritn->id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subrespondentritn/<?php echo $specific->id;?>">
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
      var respondentritn_id=$(this).attr('id');
      var respondentritn_status=$(this).parent().prev().find('.status').val();
      if(respondentritn_id!="" && respondentritn_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/respondentritn_delete',
          type : 'POST',
          dataType:'json',
          data : {
            id:respondentritn_id,
            respondentritn_status:respondentritn_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+respondentritn_id).html("");
              $('#test tbody').find('.status_'+respondentritn_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+respondentritn_id).removeClass('fa fa-toggle-off');
              $('.togg'+respondentritn_id).addClass('fa fa-toggle-on togg'+respondentritn_id+'').css("color","green");
            }
            else{
              $('.status_'+respondentritn_id).html("");
              $('#test tbody').find('.status_'+respondentritn_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+respondentritn_id).removeClass('fa fa-toggle-on');
              $('.togg'+respondentritn_id).addClass('fa fa-toggle-off togg'+respondentritn_id+'').css("color","red");
            }
          },
        });
      }
    });
    $('.addrespondentritn').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var respondentritn_id=$('.respondentritn_id').val(); 
      var respondentritn_name=$('.name').val();
      var error=0;
      if(respondentritn_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter respondentritn name').css('color','red');
      }
      else
      {
      if(respondentritn_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addrespondentritn",
          type:'post',
          dataType:'json',
          data:{
            id:respondentritn_id,
            relationship:respondentritn_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(respondentritn_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Relationship Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Relationship Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(respondentritn_id==""){
              $('#msg').html("Unable to Insert respondentritn ");
            }
            else{
              $('#msg').html("Unable to Update respondentritn");
            }
          }
        });
      }
      }
    });

   $('.edit').click(function(){

      var respondentritn_id=$(this).attr('id');
      if(respondentritn_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/respondentritn_edit",
          type:'post',
          dataType:'json',
          data:{
           id:respondentritn_id,
          },
          success:function(data){
            if(data.status=='1'){    
              data.respondentritn.forEach(function(respondentritn,index){
              $('.respondentritn_id').val(respondentritn.id);
              $('.name').val(respondentritn.relationship);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(respondentritn_id==""){
              $('#msg').html("Unable to Insert respondentritn ");
            }
            else{
              $('#msg').html("Unable to Update respondentritn");
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
