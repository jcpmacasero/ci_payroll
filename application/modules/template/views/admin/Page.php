<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $header_script; ?>
</head>
<body class="md-skin fixed-sidebar fixed-nav fixed-nav-basic no-skin-config">
    <div id="wrapper">  
        <?= $side_menu; ?>
        
        <div id="page-wrapper" class="gray-bg">            
            <?= $header; ?>
            
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <?php if ($ibox): ?>
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins" id="<?= $ibox_id; ?>">
                                <div class="ibox-title">
                                    <h5><?= $ibox_header; ?></h5>
                                    <div class="ibox-tools">
                                        <?php foreach ($ibox_tools as $row): ?>
                                            <?= $row; ?>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <?= $middle; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?= $middle; ?>
                    <?php endif ?>
                </div>
            </div>
            
            <?= $footer; ?>
            <?= $footer_script; ?>
        </div>
    </div>
</body>
</html>