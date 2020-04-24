<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tbl_schedule" style="width: 100%;">
            <thead class="header-th">
                <tr>
                    <th class="header-th">Employee ID</th>
                    <th class="header-th">Name</th>
                    <th class="header-th">Time <small>(IN - OUT)</small></th>
                    <th class="header-th">Date <small>(From - To)</small></th>
                    <th class="header-th">Rest Day</th>
                    <th class="header-th">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var tbl_schedule;
    var tbl_schedule_data;

    $(function(){


        var culumn_order = [];
        var culumn_center = [4, 5];
        var culumn_disable_sort = [5];

        tbl_schedule_data = [{name: '', value: ''}];

        tbl_schedule = $('#tbl_schedule').DataTable({
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
            sAjaxSource: base_url+'user_schedule/schedule/get_schedule_list',
            fnServerParams: function(aoData) {
                $.each(tbl_schedule_data, function(i, field) {
                    aoData.push({ name: field.name, value: field.value });
                });
            },
            fnDrawCallback: function() {

            }
        });
    });
</script>
