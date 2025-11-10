<?php  $token_3 = $csrf_token->generate_token('remove_user_confirm'); ?>

<div class="msg_wrapper_mt">
<span class="msg_span">System message</span>
<div class="msg_default">
<p>Are you sure you want to remove that user ?</p>
<form method="post">
<input type="hidden" name="user_id_remove_user_confirm_form" value="<?php echo sanitize($user_id_remove_user_form);?>">
<input type="hidden" name="csrf_token" value="<?php echo $token_3;?>">
<button class="msg_btn" type="submit" name="remove_user_confirm">Confirm</button>
</form>
<a href="index.php?section=user_management"><button class="msg_btn">Cancel</button></a>
</div>
</div>
