<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
 
<ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_Servers Configure'); ?></li><span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_Oracle'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">

<form name="form" class="form-horizontal" method="post" action="<?php echo site_url('servers_oracle/add_dg') ?>" >
<input type="hidden" name="submit" value="add_dg"/> 
<div class="btn-toolbar">
    <a class="btn btn " href="<?php echo site_url('servers_oracle/index') ?>"><i class="icon-return"></i> <?php echo $this->lang->line('return'); ?></a>
  <div class="btn-group"></div>
</div>

<?php if ($error_code!==0) { ?>
<div class="alert alert-error">
<button type="button" class="close" data-dismiss="alert">×</button>
<?php echo validation_errors(); ?>
</div>
<?php } ?>

<div class="well">
  <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('group_name'); ?></label>
    <div class="controls">
      <input type="text" id="group_name"  name="group_name" style="width: 200px;">
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('primary_db'); ?></label>
    <div class="controls">
      <select name="primary_db" id="primary_db" class="input-large"  >
        <option value=""><?php echo $this->lang->line('primary_db'); ?></option>
        <?php foreach ($datalist as $item):?>
        <option value="<?php echo $item['id'];?>" ><?php echo $item['host'];?>:<?php echo $item['port'];?>(<?php echo $item['tags'];?>)</option>
        <?php endforeach;?>
        </select>
        <span class="help-inline"></span>
    </div>
   </div>

   <!-- <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('primary_dest_id'); ?></label>
    <div class="controls">
      <select name="primary_dest_id" id="primary_dest_id" class="input-large">
        <?php 
        $arr_dest_id = range(2, 31);
        foreach ($arr_dest_id as $dest_id):?>
        <option value="<?php echo $dest_id;?>" ><?php echo $dest_id;?></option>
        <?php endforeach;?>
        </select>
        <span class="help-inline"></span>
    </div>
   </div> -->
   
   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('standby_db'); ?></label>
    <div class="controls">
      <select name="standby_db" id="standby_db" class="input-large"  >
        <option value=""><?php echo $this->lang->line('standby_db'); ?></option>
        <?php foreach ($datalist as $item):?>
        <option value="<?php echo $item['id'];?>" ><?php echo $item['host'];?>:<?php echo $item['port'];?>(<?php echo $item['tags'];?>)</option>
        <?php endforeach;?>
        </select>
        <span class="help-inline"></span>
    </div>
   </div>



   <!-- <div class="control-group">
    <label class="control-label" for=""><?php echo $this->lang->line('standby_dest_id'); ?></label>
    <div class="controls">
      <select name="standby_dest_id" id="standby_dest_id" class="input-large">
      <?php 
      $arr_dest_id = range(2, 31);
      foreach ($arr_dest_id as $dest_id):?>
      <option value="<?php echo $dest_id;?>" ><?php echo $dest_id;?></option>
      <?php endforeach;?>
      </select>
      <span class="help-inline"></span>
    </div>
   </div> -->

   <div class="controls">
    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> <?php echo $this->lang->line('add'); ?></button>
   <div class="btn-group"></div>
   </div>
   
   <hr />
   
   <table class="table table-hover table-bordered">
      <thead>
        <th colspan="2"><center><?php echo $this->lang->line('dg_group'); ?></center></th>
        <th colspan="4"><center><?php echo $this->lang->line('primary_db'); ?></center></th>
        <th colspan="4"><center><?php echo $this->lang->line('standby_db'); ?></center></th>
        <th colspan="1"></th>
        <tr style="font-size: 12px;">
        <th><?php echo $this->lang->line('group_id'); ?></th>
        <th><?php echo $this->lang->line('group_name'); ?></th>
        <th><?php echo $this->lang->line('host'); ?></th>
        <th><?php echo $this->lang->line('port'); ?></th>
        <th><?php echo $this->lang->line('dsn'); ?></th>
        <th><?php echo $this->lang->line('tags'); ?></th>
        <th><?php echo $this->lang->line('host'); ?></th>
        <th><?php echo $this->lang->line('port'); ?></th>
        <th><?php echo $this->lang->line('dsn'); ?></th>
        <th><?php echo $this->lang->line('tags'); ?></th>
        <th></th>
	</tr>
      </thead>
      <tbody>
 <?php if(!empty($dglist)) {?>
 <?php foreach ($dglist  as $item):?>
    <tr style="font-size: 12px;">
        <td><?php echo $item['id'] ?></td>
        <td><?php echo $item['group_name'] ?></td>
	      <td><?php echo $item['pri_host'] ?></td>
        <td><?php echo $item['pri_port'] ?></td>
        <td><?php echo $item['pri_dsn'] ?></td>
        <td><?php echo $item['pri_tags'] ?></td>
	      <td><?php echo $item['sta_host'] ?></td>
        <td><?php echo $item['sta_port'] ?></td>
        <td><?php echo $item['sta_dsn'] ?></td>
        <td><?php echo $item['sta_tags'] ?></td>
        <td>
        <a href="<?php echo site_url('servers_oracle/dg_delete/'.$item['id']) ?>" class="confirm_delete" title="<?php echo $this->lang->line('delete'); ?>" ><i class="icon-remove"></i></a>
        </td>
	</tr>
 <?php endforeach;?>
<tr>
<td colspan="13">
<font color="#000000"><?php echo $this->lang->line('total_record'); ?> <?php echo $dgcount; ?></font>
</td>
</tr>
 <?php }else{  ?>
<tr>
<td colspan="13">
<font color="red"><?php echo $this->lang->line('no_record'); ?></font>
</td>
</tr>
<?php } ?>      
      </tbody>
    </table>

   
</div>


</form>

<script type="text/javascript">
	$(' .confirm_delete').click(function(){
		return confirm("<?php echo $this->lang->line('delete_confirm'); ?>");	
	});
</script>

