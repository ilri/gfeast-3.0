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
          <h3 class="title"><?php if(!empty($this->uri->segment(3))){ echo 'Edit';}else{ echo 'Add';}?> Technology Type</h3>
        </div>
        
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div id="msg" style="color:green;text-align: center;"></div>
            <form  action="javascript:void(0);" id="form">
              <input type="hidden" name="technologytype_id" class='technologytype_id' >
              <div class="row">
                <div class="col-md-3">
                  <label>Technology Type </label>
                  <input type="text" name="technologytype_name" class="name form-control" placeholder="Enter Technology Type here" >
                  <span class="name_err"></span>
                </div>
               <!--  <div class="col-md-3">
                  <label>Description</label>
                  <input type="text" name="latitude"  class="desc form-control" placeholder="Enter Description here" value="<?php if(!empty($technologytype)){ $technologytype[0]->lat;}?>"><span class="desc_err"></span>
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
          <a class="btn btn-md btn-round btn-success pull-right addtechnologytype" href="javascript:void(0);">
            <i class="fa fa-plus addtechnologytype" aria-hidden="true"></i> Technology Type
          </a>
          <h5 class="card-title">Technology Type Details</h5>
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
                <?php  foreach ($technologytype as $key =>  $technologytype) {?>
                  <tr>
                    <td class="text-truncate"><?php echo $key+1; ?></td>
                    <td class="text-truncate" ><?php echo $technologytype->technologytype_name;?></td>
                     <td class="text-truncate"><?php echo $technologytype->first_name.' '.$technologytype->last_name;?></td>
                    <td style="display:none;" class="status_<?php echo $technologytype->technologytype_id;?>">
                      <input type="hidden" name="status" class="status" value="<?php echo $technologytype->status;?>">
                      <?php if($technologytype->status=='1'){?>
                        <i style="font-size:25px;color:green;" class="fa fa-check"></i>
                      <?php }else{ ?>
                        <i style="font-size:25px;color:red" class="fa fa-close"></i>
                      <?php } ?>
                    </td>
                    <td class="text-truncate" class="status2_<?php echo $technologytype->technologytype_id;?>">
                      <?php if($technologytype->status=='1'){?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $technologytype->technologytype_id;?>">
                        <i style="font-size:25px;color:green" class="togg<?php echo $technologytype->technologytype_id;?> <?php echo "fa fa-toggle-on"; ?>"></i>
                      </a><?php }else{ ?>
                      <a href="javascript:void(0);" class="delete" id="<?php echo $technologytype->technologytype_id;?>">
                        <i style="font-size:25px;color: red" class="togg<?php echo $technologytype->technologytype_id;?> <?php echo "fa fa-toggle-off"; ?>"></i>
                      </a><?php } ?>
                    </td>
                    <td class="text-truncate">
                      <a href="javascript:void(0);" class="edit" id="<?php echo $technologytype->technologytype_id;?>">
                        <i class="fa fa-edit" style="font-size:25px;"></i>
                      </a>
                    <!--   <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_subtechnologytype/<?php echo $specific->technologytype_id;?>">
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
      var technologytype_id=$(this).attr('id');
      var technologytype_status=$(this).parent().prev().find('.status').val();
      if(technologytype_id!="" && technologytype_status!=""){
        $.ajax({
          url : '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/technologytype_delete',
          type : 'POST',
          dataType:'json',
          data : {
            technologytype_id:technologytype_id,
            status:technologytype_status,
          },
          success : function(data){ 
            if(data.cstatus==1){
              $('.status_'+technologytype_id).html("");
              $('#test tbody').find('.status_'+technologytype_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:green;" class="fa fa-check"></i>');
              $(".togg"+technologytype_id).removeClass('fa fa-toggle-off');
              $('.togg'+technologytype_id).addClass('fa fa-toggle-on "togg'+technologytype_id+'"').css("color","green");
            }
            else{
              $('.status_'+technologytype_id).html("");
              $('#test tbody').find('.status_'+technologytype_id).html('<input type="hidden" name="status" class="status" value="'+data.cstatus+'"><i style="font-size:25px;color:red;" class="fa fa-close"></i>');
              $(".togg"+technologytype_id).removeClass('fa fa-toggle-on');
              $('.togg'+technologytype_id).addClass('fa fa-toggle-off "togg'+technologytype_id+'"').css("color","red");
            }
          },
        });
      }
    });
    $('.addtechnologytype').click(function(){
      $('.add').show();
      window.scrollTo({ top: 0, behavior: 'smooth' });

    });
    $('#submit').click(function(){
      var technologytype_id=$('.technologytype_id').val(); 
      var technologytype_name=$('.name').val();
      var error=0;
      if(technologytype_name==""){
        var error=error+1;
        $('.name').css('border','1px solid red');
        $('.name_err').html('please enter technologytype name').css('color','red');
      }
      else
      {
      if(technologytype_name!="" && error=='0'){
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/addtechnologytype",
          type:'post',
          dataType:'json',
          data:{
            technologytype_id:technologytype_id,
            technologytype_name:technologytype_name,
          },
          success:function(data){
            if(data.status=='1'){
              $('#form').trigger("reset");
              $("#msg").removeAttr("style");
              if(technologytype_id==""){
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong> Technology Type Inserted Successfully.</div>').delay(3000).fadeOut();
              }
              else{
                $("#msg").removeAttr('style');
                $('#msg').html('<div class="alert alert-success" style="text-align:center;"><strong>Success!</strong>Technology Type Updated Successfully.</div>').delay(3000).fadeOut();
              }
            }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(technologytype_id==""){
              $('#msg').html("Unable to Insert technologytype ");
            }
            else{
              $('#msg').html("Unable to Update technologytype");
            }
          }
        });
      }
      }
    });

    $('.edit').click(function(){

      var technologytype_id=$(this).attr('id');
      if(technologytype_id!="")
      {
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/technologytype_edit",
          type:'post',
          dataType:'json',
          data:{
           technologytype_id:technologytype_id,
          },
          success:function(data){
            if(data.status=='1'){
              data.technologytype.forEach(function(technologytype,index){
              $('.technologytype_id').val(technologytype.technologytype_id);
              $('.name').val(technologytype.technologytype_name);
              $('.add').show();
              window.scrollTo({ top: 0, behavior: 'smooth' });
               });
              }
            else{
              $('#msg').html('<div class="alert alert-danger "style="text-align:center"><strong>Success!</strong>'+ data.msg +'</div>').delay(3000).fadeOut();
            }
          },
          error:function(data){
            if(technologytype_id==""){
              $('#msg').html("Unable to Insert technologytype ");
            }
            else{
              $('#msg').html("Unable to Update technologytype");
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
