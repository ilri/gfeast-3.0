<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title title">Edit details</h4>
      </div>
      
      <div class="modal-body">
          
      </div>
      <div class="modal-load" style="text-align: center;"></div>
      <div class="modal-footer">
          
      </div>
    </div>
  </div>
</div>

<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body">
      <div class="row">
        <div class="col-md-12">
           <a href="<?php echo base_url(); ?>viewmanager" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;">Back to home page</a>
          <h4 style="font-weight: bold;">Edit hhids</h4>
          <div class="card p-10 mt-10">
            <div class="row">
              <div class="col-md-3">
                <label>Valuechain</label>
                <select class="form-control" name="valuechain">
                  <option value="">Select Valuechain</option>
                  <?php foreach ($valuechains as $key => $valuechain) { ?>
                    <option value="<?php echo $valuechain['value_chain_id']; ?>"><?php echo $valuechain['value_chain_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-3">
                <label>Enter HHID</label>
                <input type="text" name="hhid" class="form-control">
              </div>
              <div class="col-md-1">
                <button class="btn btn-sm btn-success get_data" style="margin-top: 30px;">Submit</button>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="row">
              <div class="col-md-12 response">
                
              </div>
            </div>
          </div>
        </div>        
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function(){
    $('.response').html('');

    $('.get_data').on('click', function(){
      var valuechain = $('select[name="valuechain"]').val();
      var hhid = $('input[name="hhid"]').val();

      $.ajax({
        url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/get_data_toedithhid",
        type:'post',
        dataType:'json',
        data:{
          valuechain:valuechain,
          hhid:hhid
        },
        error: function() {
          $('.response').html('<div class="alert alert-danger">Please check your internet connection and try again</div>');
        },
        success:function(response){
          if(response.status == 0){
            $('.response').html(response.msg);
          }else{

            var HTML_DATA = '<div class="table-responsive">\
              <table  class="table mb-0" id="hhid">\
                <thead>\
                  <tr>\
                    <th scope="col">Sl.NO</th>\
                    <th scope="col">Value Chain</th>\
                    <th scope="col">HHID</th>\
                    <th scope="col">HH Head First Name</th>\
                    <th scope="col">HH Head Last Name</th>\
                    <th scope="col">Mobile Number</th>\
                    <th scope="col">National Id</th>\
                    <th>Submitted by</th>\
                    <th>Inserted Date</th>\
                    <th>Action</th>\
                  </tr>\
                </thead>\
                <tbody>';
                  if(response.data.length > 0){
                    response.data.forEach(function(record, index){
                      HTML_DATA += '<tr>\
                        <td>'+(index+1)+'</td>\
                        <td>'+record.valuechainid+'</td>\
                        <td>'+record.hhid+'</td>\
                        <td>'+record.field_1450+'</td>\
                        <td>'+record.field_1456+'</td>\
                        <td>'+record.field_1002+'</td>\
                        <td>'+record.field_1003+'</td>\
                        <td>'+record.name+'</td>\
                        <td>'+record.inserteddate+'</td>\
                        <td><a href="javascript:void(0);" data-recordid = "'+record.id+'" data-hhidvalue="'+record.hhid+'" class="edit_record">Edit</a></td>\
                      </tr>';
                    });
                  }else{
                    HTML_DATA += '<tr><td colspan="9">No records found</td></tr>';
                  }
                HTML_DATA += '</tbody>\
              </table>\
            </div>';
            $('.response').html(HTML_DATA);                   
          }
        }
      });      
    });

    $('body').on('click', '.edit_record', function(){
      $elem = $(this);
      var valuechain = $('select[name="valuechain"]').val();
      var recordid = $elem.attr('data-recordid');
      var hhidvalue = $elem.attr('data-hhidvalue');

      var HTML_DATA = '';

      HTML_DATA = '<div class="row">\
        <div class="col-md-12">\
          <div class="form-group">\
            <label>Old HHID<span style="color:red;">*</span></label>\
            <p class="form-control">'+hhidvalue+'</p>\
          </div>\
        </div>\
        <div class="col-md-12">\
          <div class="form-group">\
            <label>Enter correct HHID<span style="color:red;">*</span></label>\
            <input type="text" class="form-control" name="correct_hhid">\
            <p class="field-error red-800"></p>\
          </div>\
        </div>\
      </div>';


      $('.modal-body').html(HTML_DATA);
      $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-success btn-sm save_editedhhid" data-recordid="'+recordid+'" data-hhidvalue="'+hhidvalue+'" data-valuechain="'+valuechain+'">Save</button>');
      $('#myModal').modal('show');
    });

    $('body').on('click', '.save_editedhhid', function(){
      $elem = $(this);
      var valuechain = $elem.attr('data-valuechain');
      var recordid = $elem.attr('data-recordid');
      var hhidvalue = $elem.attr('data-hhidvalue');

      var correct_hhid = $('input[name="correct_hhid"]').val();

      var error_val = 0;

      if($.trim(correct_hhid) == ''){
        $('.field-error').html('This field is mandatory');
        error_val++;
      }else{
        if($.trim(correct_hhid).length != 12){
          $('.field-error').html('Invalid hhids please eneter a valid hhid');
          error_val++;
        }
      }

      if(error_val == 0){        
        $.ajax({
          url:"<?php echo base_url();?><?php echo $this->uri->segment(1);?>/update_edited_hhid",
          type:'post',
          dataType:'json',
          data:{
            valuechain : valuechain,
            hhidvalue : hhidvalue,
            recordid : recordid,
            correct_hhid : correct_hhid
          },
          error: function() {
            $('.modal-body').html('<div class="alert alert-danger">Please check your internet connection and try again</div>');
          },
          success:function(response){
            if(response.status == 0){
              $('.modal-body').html('<div class="alert alert-danger">'+response.msg+'</div>');
              $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
            }else{
              $('.modal-body').html('<div class="alert alert-success">'+response.msg+'</div>');
              $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
            }
          }
        });
      }
    });

    $('#myModal').on('hidden.bs.modal', function () {
      location.reload();
    });
  });  
</script>