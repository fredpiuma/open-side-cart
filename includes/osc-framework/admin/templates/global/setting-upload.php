<?php
	
if( !isset( $id ) || !isset( $value ) ){
	echo 'Input Id/ Value not set';
	return;
}

?>
<div class="osc-fw-as-upload-container">
	<a class="button-primary osc-fw-upload-icon">Select</a>
	<input type="hidden" name="<?php echo esc_attr( $id ); ?>" class="osc-fw-upload-url" value="<?php echo esc_attr( $value ); ?>">
	<a class="button osc-fw-remove-media">Remove</a>
	<span class="osc-fw-upload-title"></span>
</div>