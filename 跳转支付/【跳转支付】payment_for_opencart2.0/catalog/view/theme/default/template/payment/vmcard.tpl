<form action="<?php echo $action; ?>" method="POST" class="form-horizontal" id="vmcard_form_redirect">
<fieldset id="payment">
    <?php foreach($formData as $key=> $value): ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/>
    <?php endforeach; ?>
</fieldset>
</form>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript">
    $('#button-confirm').bind('click', function(){
        $('#button-confirm').val('Please Wait A Moment......');
        $.ajax({
            url: "<?php echo $url_create_order; ?>",
            type: "post",
            data: "",
            dataType: 'json',
            cache: false,
            complete: function(){
                $("#vmcard_form_redirect").submit();
            }
        })
        // $("#vmcard_form_redirect").submit();
    });
</script>