<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tbl_overtime" style="width: 100%;">
            <thead class="header-th">
                <tr>
                    <th class="header-th">Employee ID</th>
                    <th class="header-th">Name</th>
                    <th class="header-th">Overtime <small>(IN - OUT)</small></th>
                    <th class="header-th">Overtime Duration Min</small></th>
                    <th class="header-th">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var tbl_overtime;
    var tbl_overtime_data;

    $(function(){


        var culumn_order = [];
        var culumn_center = [4];
        var culumn_disable_sort = [4];

        tbl_overtime_data = [{name: '', value: ''}];

        tbl_overtime = $('#tbl_overtime').DataTable({
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
            sAjaxSource: base_url+'user_overtime/overtime/get_overtime_list',
            fnServerParams: function(aoData) {
                $.each(tbl_overtime_data, function(i, field) {
                    aoData.push({ name: field.name, value: field.value });
                });
            },
            fnDrawCallback: function() {

            }
        });
    });
</script>
