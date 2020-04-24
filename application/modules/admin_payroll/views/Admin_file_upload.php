<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Dropzone.js</h5>
            </div>
            <div class="ibox-content">

                <p>
                    <strong>Dropzone.</strong> Drop the file here to upload.
                </p>

                <form action="#" class="dropzone" id="dropzoneForm">
                    <div class="fallback">
                        <input name="file" type="file" />
                    </div>
                </form>               
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12">
	<div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tbl_additional" style="width: 100%;">           
            <thead class="header-th">
                <tr>
                    <th class="header-th">File name</th>
                    <th class="header-th">Date uploaded</th>
                    <th class="header-th">Uploaded by</th>
                    <th class="header-th">Action</th>                    
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
	Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)"
        };

        $(document).ready(function(){

            var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code2"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code3"), {
                lineNumbers: true,
                matchBrackets: true
            });

       });
</script>