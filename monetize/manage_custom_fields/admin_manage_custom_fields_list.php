<?php
include 'tab_header.php';
if($_REQUEST['act']=='del')
{
	$cid = $_REQUEST['cid'];
	$wpdb->query("delete from $custom_post_meta_db_table_name where cid=\"$cid\"");
	$url = site_url().'/wp-admin/admin.php';
	echo '<form action="'.$url.'" method="get" id="frm_bulk_upload" name="frm_bulk_upload">
	<input type="hidden" value="custom" name="page"><input type="hidden" value="delsuccess" name="msg">
	</form>
	<script>document.frm_bulk_upload.submit();</script>
	';exit;	
}
?>
<script>
function confirm_delete(c_id)
{
	if(!confirm('<?php _e('Are you sure, want to delete this information?','ddb_wp');?>'))
	{
		return false;
	}else
	{
		window.location.href="<?php echo site_url();?>/wp-admin/admin.php?page=custom&act=del&cid="+c_id;	
	}
}
</script>
<div class="block" id="option_add_custom_fields">
	<?php include ('admin_manage_custom_fields_edit.php'); ?>
</div>

<div class="block" id="option_display_custom_fields">
<p><?php _e('Custom fields help you gather required information from sellers via the Post Deal page. They will fill their information in the fields you set here.','ddb_wp');?></p>
<?php
if($_REQUEST['msg']=='delsuccess')
{
	$message = __('Information Deleted successfully.','ddb_wp');	
}
?>
<?php if($message){?>
<div class="updated fade below-h2" id="message" style="padding:5px; font-size:11px;" >
  <?php echo $message;?>
</div>
<?php }?>
<table width="100%"  class="widefat post" >
  <thead>
    <tr>
      <th><?php _e('Field','ddb_wp');?></th>
      <th><?php _e('Post type','ddb_wp');?></th>
      <th align="center"><?php _e('Field type','ddb_wp');?></th>
      <th align="center"><?php _e('Activated?','ddb_wp');?></th>
      <th><?php _e('Action','ddb_wp');?></th>
    </tr>
<?php
$post_meta_info = $wpdb->get_results("select * from $custom_post_meta_db_table_name order by sort_order asc,admin_title asc");
if($post_meta_info){
	foreach($post_meta_info as $post_meta_info_obj){
	?>
     <tr>
      <td><?php echo $post_meta_info_obj->admin_title;?></td>
      <td><?php echo $post_meta_info_obj->post_type;?></td>
      <td><?php echo $post_meta_info_obj->ctype;?></td>
      <td><?php if($post_meta_info_obj->is_active) _e('Yes','ddb_wp'); else _e('No','ddb_wp');?></td>
      <td>
	 <a href="javascript:void(0);showdetail('<?php echo $post_meta_info_obj->cid;?>');"><?php _e('Detail','ddb_wp');?></a> | <a href="<?php echo site_url();?>/wp-admin/admin.php?page=custom&cf=<?php echo $post_meta_info_obj->cid;?>#option_add_custom_fields"><?php _e('Edit','ddb_wp');?></a> <?php if($post_meta_info_obj->is_delete=='0'){?> | <a href="javascript:void(0);" onclick="return confirm_delete('<?php echo $post_meta_info_obj->cid;?>');"><?php _e('Delete','ddb_wp');?></a><?php }?>
      </td>
      </tr>
      <tr id="detail_<?php echo $post_meta_info_obj->cid;?>" style="display:none;">
      <td colspan="5">
      <table style="background-color:#eee;" width="100%">
      <tr>
        <td><?php _e('Admin Title','ddb_wp')?> : <strong><?php echo $post_meta_info_obj->admin_title;?></strong></td>
        
		
        <td><?php _e('Post Type','ddb_wp')?> : <strong><?php echo $post_meta_info_obj->post_type;?></strong></td>
        <td><?php _e('Display Order','ddb_wp')?> : <strong><?php echo $post_meta_info_obj->sort_order;?></strong></td>
     </tr> 
	<tr>
	<td><?php _e('Type','ddb_wp')?> : <strong><?php echo $post_meta_info_obj->ctype;?></strong></td>
	<td><?php _e('Defaule Value','ddb_wp')?> : <strong><?php echo $post_meta_info_obj->default_value;?></strong></td>
	<td><?php _e('Is Display On Detail?','ddb_wp')?> : <strong><?php if($post_meta_info_obj->show_on_detail) _e('Yes','ddb_wp'); else _e('No','ddb_wp');?></strong></td>
	 
	</tr>
	<tr>       
		<td><?php _e('Front Title','ddb_wp')?> : <strong><?php echo $post_meta_info_obj->site_title;?></strong></td>
        <td><?php _e('Is Active?','ddb_wp')?> : <strong><?php if($post_meta_info_obj->is_active) _e('Yes','ddb_wp'); else _e('No','ddb_wp');?></strong></td>
		<td><?php _e('Is Display On listing?','ddb_wp')?> : <strong><?php if($post_meta_info_obj->show_on_listing) _e('Yes','ddb_wp'); else _e('No','ddb_wp');?></strong></td>
	</tr>
      
       <tr>       
      	<td><?php _e('HTML Variable Name','ddb_wp')?> : <strong><?php echo $post_meta_info_obj->htmlvar_name;?></strong></td>
		
         <td colspan="2"><?php _e('Use at front end','ddb_wp')?> : <strong><?php if($post_meta_info_obj->is_delete=='0'){echo 'get_post_meta($post->ID,"'.$post_meta_info_obj->htmlvar_name.'",true)';}elseif($post_meta_info_obj->is_delete=='1'){_e('Theme Default Field','ddb_wp');}elseif($post_meta_info_obj->ctype=='head'){_e('Heading','ddb_wp');}?></strong></td>
      </tr>
      </table>
      </td>
      </tr>
    <?php	
	}
}else
{
?>
     <tr><td colspan="9"><?php _e('No custom fields available.','ddb_wp');?></td></tr>
<?php		
}
?>
  </thead>
</table>
</div>
<script type="text/javascript">
function showdetail(custom_id)
{
	if(document.getElementById('detail_'+custom_id).style.display=='none')
	{
		document.getElementById('detail_'+custom_id).style.display='';
	}else
	{
		document.getElementById('detail_'+custom_id).style.display='none';	
	}
}
</script>
<?php include DDB_ADMIN_TPL_PATH.'footer.php';?>
