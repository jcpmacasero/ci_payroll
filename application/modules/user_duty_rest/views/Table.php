<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tbl_duty_rest" style="width: 100%;">
            <thead class="header-th">
                <tr>
                    <th class="header-th">Employee ID</th>
                    <th class="header-th">Name</th>
                    <th class="header-th">Date Duty</th>
                    <th class="header-th">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var tbl_duty_rest;
    var tbl_duty_rest_data;

    $(function(){


        var culumn_order = [];
        var culumn_center = [3];
        var culumn_disable_sort = [3];

        tbl_duty_rest_data = [{name: '', value: ''}];

        tbl_duty_rest = $('#tbl_duty_rest').DataTable({
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
            sAjaxSource: base_url+'user_duty_rest/duty_rest/get_duty_rest_list',
            fnServerParams: function(aoData) {
                $.each(tbl_duty_rest_data, function(i, field) {
                    aoData.push({ name: field.name, value: field.value });
                });
            },
            fnDrawCallback: function() {

            }
        });
    });
</script>
