<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tbl_user" style="width: 100%;">
            <thead class="header-th">
                <tr>
                    <th class="header-th">Photo</th>
                    <th class="header-th">Employee ID</th>
                    <th class="header-th">Name</th>
                    <th class="header-th">Gender</th>
                    <th class="header-th">Email</th>
                    <th class="header-th">Birthdate</th>
                    <th class="header-th">Position</th>
                    <th class="header-th">Status</th>
                    <th class="header-th">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var tbl_user;
    var tbl_user_data;

    $(function(){
        var culumn_order = [];
        var culumn_center = [0, 7, 8];
        var culumn_disable_sort = [0, 8];

        tbl_user_data = [{name: '', value: ''}];

        tbl_user = $('#tbl_user').DataTable({
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
            sAjaxSource: base_url+'admin_employee/employee/get_employee_list',
            fnServerParams: function(aoData) {
                $.each(tbl_user_data, function(i, field) {
                    aoData.push({ name: field.name, value: field.value });
                });
            },
            fnDrawCallback: function() {

            }
        });
    });
</script>

<?php $this->load->view('admin_employee/Permission'); ?>