<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tbl_additional" style="width: 100%;">
            <a name='btn_add' id='btn_add' class='btn btn-sm btn-primary' onclick="add_additional_click();"><span class='fa fa-plus'></span> Add Additional title</a>
            <thead class="header-th">
                <tr>
                    <th class="header-th">Additional Title</th>
                    <th class="header-th">Amount</th> 
                    <th class="header-th">Status</th>
                    <th class="header-th">Date Created</th>
                    <th class="header-th">Created by</th>
                    <th class="header-th">Action</th>                    
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_additional" role="dialog">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header  btn-success">
                             <button type="button" class="close" data-dismiss="modal">&times;</button>                
                             <h3 class="modal-title"></h3>                    
            </div><!-- modal-header -->
                        <div class="modal-body">  
                            <form class="form-horizontal" id="frm_additional">

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="addtnl_title">Title:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="additional_title" style="text-transform: capitalize;" placeholder="Additional title" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>               

                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="addt_amt">Amount:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="amt_additional" placeholder="Amount" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                  <label class="control-label col-sm-3" for="additional_stat">Status:</label>
                                  <div class="col-sm-7">
                                     <select name="add_stat" class="form-control">
                                        <option value=""></option>
                                        <option value="0">DISABLED</option>
                                        <option value="1">ENABLED</option>                                          
                                     </select>
                                    <span class="help-block"></span>
                                  </div>
                                </div>               

                                <div class="form-group">                                  
                                <input type="hidden" class="form-control" name="add_id" required> 
                                </div>
                            </form>
                                
                        </div><!-- modal-body -->
                        <div class="modal-footer">
                            <button onclick="save()" id="btnAddSave" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div><!-- modal-footer -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modaleditpatient -->

<script type="text/javascript">
var tbl_additional;
var save_method; 
var tbl_additional_data;

$(document).ready(function() {          
        showall_additionals();           

        $("input").change(function(){
                    $(this).parent().parent().removeClass('has-error');
                    $(this).next().empty();
        });
});

function add_additional_click(){
    save_method = 'add';
    $('#frm_additional')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
    $('.help-block').empty();
    $('#modal_additional').modal('show'); 
    $('.modal-title').text('Add new title'); 
}

function edit_additional(id){
        save_method = 'update';
        $('#frm_additional')[0].reset(); 
        $('.form-group').removeClass('has-error'); 
        $('.help-block').empty(); 
        $.ajax({
            url : base_url+'admin_additional/additional/get_specific_addt/'+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('[name="additional_title"]').val(data.additional_title);
                $('[name="amt_additional"]').val(data.amount);                
                $('[name="add_stat"]').val(data.additional_status);                
                $('[name="add_id"]').val(id);
                $('#modal_additional').modal('show'); 
                $('.modal-title').text('Edit additional title');
            },
            error: function (jqXHR, textStatus, errorThrown){
              toastr.error('A process cannot get through!','Error!')
            }
        });
}

function save(){
    $('#btnAddSave').text('Saving...'); 
    $('#btnAddSave').attr('disabled',true);
    let url;

    if(save_method == 'add') {
            url = base_url+'admin_additional/additional/add_additional';
    } 
    else if (save_method == 'update') {
            url = base_url+'admin_additional/additional/edit_additional';
    }         

    $.ajax({
            url : url,
            type: "POST",
            data: $('#frm_additional').serialize(),
            dataType: "JSON",
            success: function(data){
                    if(data.status) {
                            reload_tbl_additional();
                            $('#modal_additional').modal('hide');
                            if(save_method == 'add'){
                                    toastr.success('Title added','Success!')
                            }else{
                                 toastr.success('Title updated','Success!')
                            }
                    }
                    else{
                            toastr.error('Please fill up the form correctly','Error!')
                            $.each(data.message, function(key, value) {
                                console.log(key,value);
                                $('[name="'+key+'"]').parent().parent().addClass('has-error'); 
                                $('[name="'+key+'"]').next().text(value);                      
                            });                    
                    }
                    $('#btnAddSave').text('Save');
                    $('#btnAddSave').attr('disabled',false);
            },
            error: function (jqXHR, textStatus, errorThrown){                     
                     toastr.error('A process cannot get through!','Error!')
                 
                    $('#btnAddSave').text('Save'); 
                    $('#btnAddSave').attr('disabled',false); 
            }
    });
}

function reload_tbl_additional(){
        tbl_additional.ajax.reload(null,false);  
}

function showall_additionals() {
    var culumn_order = [];
    var culumn_center = [];
    var culumn_disable_sort = [];

    tbl_additional_data = [{name: '', value: ''}];

    tbl_additional = $('#tbl_additional').DataTable({
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
            sAjaxSource: base_url+'admin_additional/additional/get_additional_list',
            fnServerParams: function(aoData) {
                    $.each(tbl_additional_data, function(i, field) {
                            aoData.push({ name: field.name, value: field.value });
                    });
            },
            fnDrawCallback: function() {

            }
    });
}
</script>