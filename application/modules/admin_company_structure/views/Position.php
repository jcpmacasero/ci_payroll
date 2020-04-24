<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="table_position" style="width: 100%;">
            <a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' onclick="add_position_click();"><span class='fa fa-plus'></span> Add Position</a>
            <thead class="header-th">
                <tr>
                    <th class="header-th">Position Title</th>
                    <th class="header-th">Department</th> 
                    <th class="header-th">Salary Grade</th> 
                    <th class="header-th">Date Created</th>
                    <th class="header-th">Created By</th>
                    <th class="header-th">Action</th>                   
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_position" role="dialog">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header  btn-success">
               <button type="button" class="close" data-dismiss="modal">&times;</button>                
               <h3 class="modal-title"></h3>                    
      </div><!-- modal-header -->
            <div class="modal-body">  
              <form class="form-horizontal" id="frm_position">

                <div class="form-group">
                  <label class="control-label col-sm-3" for="position_n">Position title:</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" name="position_name" style="text-transform: capitalize;" placeholder="Position title" required>
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-3" for="department_n">Department Assign:</label>
                  <div class="col-sm-7">
                     <select name="dept_name" class="form-control">
                        <option value="">Select</option>
                          <?php foreach ($department as $key => $value): ?>
                              <option value="<?= $value->department_id; ?>"><?= html_escape($value->department_name); ?></option>
                          <?php endforeach ?>
                     </select>
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-3" for="salary_g">Salary Grade:</label>
                  <div class="col-sm-7">
                     <select name="sal_grade" class="form-control">
                        <option value="">Select</option>
                          <?php foreach ($salary as $key => $value): ?>
                              <?php if(html_escape($value->salary_status) == 1) {
                                        $salary_stat = "Fixed";
                              } else if(html_escape($value->salary_status) == 0) {
                                        $salary_stat = "Per Day";
                              } ?>
                              <option value="<?= $value->salary_id; ?>"><?= html_escape($value->salary_title); ?> - P<?= html_escape(number_format($value->amount, 2, '.',','));?> - <?php echo $salary_stat ?></option>
                          <?php endforeach ?>
                     </select>
                    <span class="help-block"></span>
                  </div>
                </div>
                
                <input type="hidden" class="form-control" name="position_id" required> 
              </form>
                
            </div><!-- modal-body -->
            <div class="modal-footer">
              <button onclick="save()" id="btnPositionSave" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div><!-- modal-footer -->
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var table_position;
var save_method; 

$(document).ready(function() {      
      showall_position();

      $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
      });
});

function add_position_click(){
        save_method = 'add';
        $('#frm_position')[0].reset(); 
        $('.form-group').removeClass('has-error'); 
        $('.help-block').empty();
        $('#modal_position').modal('show'); 
        $('.modal-title').text('Add new position'); 
}

function update_pos(id){
        save_method = 'update';
        $('#frm_position')[0].reset(); 
        $('.form-group').removeClass('has-error'); 
        $('.help-block').empty(); 
        $.ajax({
            url : base_url+'admin_company_structure/position/get_specific_position/'+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('[name="position_name"]').val(data.position_title);
                $('[name="dept_name"]').val(data.department_id);                
                $('[name="sal_grade"]').val(data.salary_id);                
                $('[name="position_id"]').val(id);
                $('#modal_position').modal('show'); 
                $('.modal-title').text('Edit position');
            },
            error: function (jqXHR, textStatus, errorThrown){
              toastr.error('A process cannot get through!','Error!')
            }
        });
}

function save(){
    $('#btnPositionSave').text('Saving...'); 
    $('#btnPositionSave').attr('disabled',true);
    let url;

    if(save_method == 'add') {
        url = base_url+'admin_company_structure/position/add_position';
    } 
    else if (save_method == 'update') {
        url = base_url+'admin_company_structure/position/edit_position';
    }         

    $.ajax({
        url : url,
        type: "POST",
        data: $('#frm_position').serialize(),
        dataType: "JSON",
        success: function(data){
            if(data.status) {
                reload_tbl_pos();
                $('#modal_position').modal('hide');
                if(save_method == 'add'){
                    toastr.success('Position added','Success!')
                }else{
                   toastr.success('Position updated','Success!')
                }
            }
            else{
                toastr.error('Please fill up the form correctly','Error!')
                $.each(data.message, function(key, value) {                  
                  $('[name="'+key+'"]').parent().parent().addClass('has-error'); 
                  $('[name="'+key+'"]').next().text(value);                      
                });                    
            }
            $('#btnPositionSave').text('Save');
            $('#btnPositionSave').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown){
            
             toastr.error('A process cannot get through!','Error!')
           
            $('#btnPositionSave').text('Save'); 
            $('#btnPositionSave').attr('disabled',false); 
        }
    });
}

function reload_tbl_pos(){
    table_position.ajax.reload(null,false);  
}

function showall_position() {
  var culumn_order = [];
  var culumn_center = [];
  var culumn_disable_sort = [];

  table_position_data = [{name: '', value: ''}];

  table_position = $('#table_position').DataTable({
      pageLength: 25,
      responsive: true,
      bProcessing: true,
      bServerSide: true,
      deferRender: true,
      order: culumn_order,
      columnDefs: [
          { className: 'text-center', targets: culumn_center },
          { orderable: false, targets: culumn_disable_sort },
      ],
      sServerMethod: 'POST',
      sAjaxSource: base_url+'admin_company_structure/position/get_position_list',
      fnServerParams: function(aoData) {
          $.each(table_position_data, function(i, field) {
              aoData.push({ name: field.name, value: field.value });
          });
      },
      fnDrawCallback: function() {

      }
  });
}
</script>