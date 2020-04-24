<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="col-sm-12"> 
    <div class="row" style="margin-top: 3%;">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="table_salary_history" style="width: 100%;">            
                    <thead class="header-th">
                        <tr>
                            <th class="header-th">Date Paid</th>
                            <th class="header-th">Salary Status</th> 
                            <th class="header-th">Inclusive Dates</th>                                                     
                            <th class="header-th">Total paid</th>                   
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function() {          
    show_allpayroll_history();
          
});

function show_allpayroll_history() {
    var culumn_order = [];
    var culumn_center = [3];
    var culumn_disable_sort = [3];

    tbl_department_data = [{name: '', value: ''}];

    table_salary_history = $('#table_salary_history').DataTable({
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
            sAjaxSource: base_url+'admin_payroll/admin_view_payroll_history/get_payroll_history_list',
            fnServerParams: function(aoData) {
                    $.each(tbl_department_data, function(i, field) {
                            aoData.push({ name: field.name, value: field.value });
                    });
            },
            fnDrawCallback: function() {

            }
    });
}


</script>

<!-- 

     1. check kung sa tbl_attendance naay data nga inside sa date range
     2. check kung na calculate na ba daan ang date range
        if true (meaning na calculate na)
            - Calculate again / Get ang calculate history        
        if false (calculate dritso) 

-->
