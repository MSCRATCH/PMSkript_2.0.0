<?php  $token_1 = $csrf_token->generate_token('update_user_level'); ?>
<?php  $token_2 = $csrf_token->generate_token('remove_user'); ?>

<div class="table_wrapper">
<div class="content_container_margin_bottom">
Users can be managed in this area. Users have not automatically the not_activated rank, which means the page is unusable until the user rank is changed to user.
</div>
<div class="table_container">
<div class="table_row table_title">
<div class="table_cell">Username</div>
<div class="table_cell">E-Mail</div>
<div class="table_cell">User level</div>
<div class="table_cell">Registered</div>
<div class="table_cell">Last activity</div>
<div class="table_cell">Remove</div>
<div class="table_cell">Change user level</div>
</div>
<?php $i = 1; ?>
<?php foreach ($users as $user) { ?>
<div class="table_row">
<div class="table_cell">#<?php echo sanitize($i);?> <?php echo sanitize_ucfirst($user['username']);?> (<?php echo sanitize($user['user_id']);?>)</div>
<div class="table_cell"><?php echo sanitize($user['user_email']);?></div>
<div class="table_cell"><?php echo sanitize($user['user_level']);?></div>
<div class="table_cell"><?php echo sanitize($user['user_date']);?></div>
<?php if ($user['last_activity_minutes'] <= 5) { ?>
<div class="table_cell">Online</div>
<?php } else { ?>
<div class="table_cell"><?php echo sanitize($user['last_activity']);?></div>
<?php }?>
<div class="table_cell">
<form method="post">
<input type="hidden" name="user_id_remove_user_form" value="<?php echo sanitize($user['user_id']);?>">
<input type="hidden" name="csrf_token" value="<?php echo $token_2;?>">
<button class="btn_table" type="submit" name="remove_user">Remove</button>
</form>
</div>
<div class="table_cell">
<form class="form_row" method="post">
<select class="select_nbg" name="user_level_form" id="user_level">
<option value="<?php echo sanitize($user['user_level']);?>"><?php echo sanitize($user['user_level']);?></option>
<?php if ($user['user_level'] !== 'user') { ?>
<option value="user">user</option>
<?php } ?>
<?php if ($user['user_level'] !== 'not_activated') { ?>
<option value="not_activated">not activated</option>
<?php } ?>
<?php if ($user['user_level'] !== 'moderator') { ?>
<option value="moderator">moderator</option>
<?php } ?>
</select>
<input type="hidden" name="user_id_user_level_form" value="<?php echo sanitize($user['user_id']);?>">
<input type="hidden" name="csrf_token" value="<?php echo $token_1;?>">
<button class="btn_table" type="submit" name="update_user_level">Change</button>
</form>
</div>
</div>
<?php $i++; ?>
<?php } ?>

</div>
<div class="pagination">
<?php echo $pagination->render_pagination('index.php?section=user_management'); ?>
</div>
</div>
