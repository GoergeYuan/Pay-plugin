<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="from-vmcard" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i>
            </div>

            <h1><?php echo $heading_title; ?></h1>

            <ul class="breadcrumb">
                <?php foreach($breadcrumbs as $breadcrumb){ ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <?php if($error_warning){ ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
            </div>

            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vmcard" class="form-horizontal">
                <div class="form-group required">
                    <label for="input-payable" class="col-sm-2 control-label"><?php echo $entry_vmcard_payable; ?></label>
                    <div class="col-sm-10">
                        <?php echo $payable_list; ?>
                    </div>
                </div>

                <!-- Merchant no -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_vmcard_merchant_no; ?></label>
                    <div class="col-sm-10">
                        <input type="text" id="input-vmcard_merchant_no" name="vmcard_merchant_no" value="<?php echo $vmcard_merchant_no; ?>" placeholder="<?php echo $entry_vmcard_merchant_no; ?>" class="form-control"/>
                        <?php if($error_merchant_no): ?>
                            <div class="text-danger"><?php echo $error_merchant_no; ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- MD5key -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_vmcard_md5key; ?></label>
                    <div class="col-sm-10">
                        <input type="text" id="input-vmcard_md5key" name="vmcard_md5key" value="<?php echo $vmcard_md5key; ?>" placeholder="<?php echo $entry_vmcard_md5key; ?>" class="form-control"/>
                    </div>
                </div>

                <!-- Langauge -->
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_vmcard_language; ?></label>
                    <div class="col-sm-10">
                        <?php echo $language_list; ?>
                    </div>
                </div>
                
                <!-- Return Url -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_vmcard_returnurl; ?></label>
                    <div class="col-sm-10">
                        <input type="text" id="input-vmcard_returnurl" name="vmcard_returnurl" value="<?php echo $vmcard_returnurl; ?>" placeholder="<?php echo $entry_vmcard_returnurl; ?>" class="form-control"/>
                    </div>
                </div>

                <!-- Gateway -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_vmcard_gateway; ?></label>
                    <div class="col-sm-10">
                        <input type="text" id="input-vmcard_gateway" name="vmcard_gateway" value= "<?php echo $vmcard_gateway; ?>" placeholder="<?php echo $entry_vmcard_gateway; ?>" class="form-control"/>
                    </div>
                </div>

                <!-- new order status -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_new_order_status; ?></label>
                    <div class="col-sm-10">
                        <?php echo $new_order_list; ?>
                    </div>
                </div>

                <!-- processing -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_process_order_status; ?></label>
                    <div class="col-sm-10">
                        <?php echo $process_order_list; ?>
                    </div>
                </div>

                <!-- success  -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_success_order_status; ?></label>
                    <div class="col-sm-10">
                        <?php echo $success_order_list; ?>
                    </div>
                </div>

                <!-- failed -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_failed_order_status; ?></label>
                    <div class="col-sm-10">
                        <?php echo $failed_order_list; ?>
                    </div>
                </div>

                <!-- sort order -->
                <div class="form-group required">
                    <label for="" class="col-sm-2 control-label"><?php echo $entry_vmcard_sort_order; ?></label>
                    <div class="col-sm-10">
                        <input type="text" id="input-vmcard_sort_order" name="vmcard_sort_order" value= "<?php echo $vmcard_sort_order; ?>" placeholder="<?php echo $entry_vmcard_sort_order; ?>" class="form-control"/>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>