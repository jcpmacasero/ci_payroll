<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="table_salary" style="width: 100%;">
            <a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' onclick="add_salary_click();"><span class='fa fa-plus'></span> Add Salary Grade</a>
            <thead class="header-th">
                <tr>
                    <th class="header-th">Salary Title</th>
                    <th class="header-th">Amount</th> 
                    <th class="header-th">Salary Status</th>
                    <th class="header-th">Date Created</th>
                    <th class="header-th">Created By</th>
                    <th class="header-th">Action</th>                   
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_salary" role="dialog">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header  btn-success">
               <button type="button" class="close" data-dismiss="modal">&times;</button>                
               <h3 class="modal-title"></h3>                    
      </div><!-- modal-header -->
            <div class="modal-body">  
              <form class="form-horizontal" id="frm_salary">

                <div class="form-group">
                  <label class="control-label col-sm-3" for="position_n">Salary title:</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" name="salary_name" style="text-transform: capitalize;" placeholder="Salary title" required>
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-3" for="position_n">Amount:</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" name="salary_amt" placeholder="Amount" required>
                    <span class="help-block"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-3" for="salary_stat">Status:</label>
                  <div class="col-sm-7">
                     <select name="sal_stat" class="form-control">
                        <option value="">Select</option>
                          <option value="0">Per Day</option>
                          <option value="1">Fixed</option>
                     </select>
                    <span class="help-block"></span>
                  </div>
                </div>

                <input type="hidden" class="form-control" name="salary_id" required> 
              </form>
                
            </div><!-- modal-body -->
            <div class="modal-footer">
              <button onclick="save()" id="btnSalarySave" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div><!-- modal-footer -->
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var table_salary;
var save_method; 

$(document).ready(function() {            
      showall_salary();

      $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
      });
});

function reload_tbl_salary(){
    table_salary.ajax.reload(null,false);  
}

function add_salary_click(){
  save_method = 'add';
  $('#frm_salary')[0].reset(); 
  $('.form-group').removeClass('has-error'); 
  $('.help-block').empty();
  $('#modal_salary').modal('show'); 
  $('.modal-title').text('Add new salary title'); 
}

function update_sal(id){
    save_method = 'update';
    $('#frm_salary')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
    $('.help-block').empty(); 
    $.ajax({
        url : base_url+'admin_company_structure/salary/get_specific_salary/'+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            $('[name="salary_name"]').val(data.salary_title);
            $('[name="salary_amt"]').val(data.amount);                
            $('[name="sal_stat"]').val(data.salary_status);                
            $('[name="salary_id"]').val(id);
            $('#modal_salary').modal('show'); 
            $('.modal-title').text('Edit salary');
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('A process cannot get through!','Error!')
        }
    });
}

function save(){
    $('#btnSalarySave').text('Saving...'); 
    $('#btnSalarySave').attr('disabled',true);
    let url;

    if(save_method == 'add') {
        url = base_url+'admin_company_structure/salary/add_salary';
    } 
    else if (save_method == 'update') {
        url = base_url+'admin_company_structure/salary/edit_salary';
    }         

    $.ajax({
        url : url,
        type: "POST",
        data: $('#frm_salary').serialize(),
        dataType: "JSON",
        success: function(data){
            if(data.status) {
                reload_tbl_salary();
                $('#modal_salary').modal('hide');
                if(save_method == 'add'){
                    toastr.success('Salary grade added','Success!')
                }else{
                   toastr.success('Salary grade updated','Success!')
                }
            }
            else{
                toastr.error('Please fill up the form correctly','Error!')
                $.each(data.message, function(key, value) {                  
                  $('[name="'+key+'"]').parent().parent().addClass('has-error'); 
                  $('[name="'+key+'"]').next().text(value);                      
                });                    
            }
            $('#btnSalarySave').text('Save');
            $('#btnSalarySave').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown){            
             toastr.error('A process cannot get through!','Error!')
            $('#btnSalarySave').text('Save'); 
            $('#btnSalarySave').attr('disabled',false); 
        }
    });
}

function showall_salary() {  
  var culumn_order = [];
  var culumn_center = [];
  var culumn_disable_sort = [];

  table_salary_data = [{name: '', value: ''}];

  table_salary = $('#table_salary').DataTable({
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
      sAjaxSource: base_url+'admin_company_structure/salary/get_salary_list',
      fnServerParams: function(aoData) {
          $.each(table_salary_data, function(i, field) {
              aoData.push({ name: field.name, value: field.value });
          });
      },
      fnDrawCallback: function() {

      }
  });
}
</script>