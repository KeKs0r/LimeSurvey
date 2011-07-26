<?php $this->load->view("installer/header_view",array('progressValue' => $progressValue)); ?>
<div class="container_6">
<div class="grid_2 table">
<p class="title"> &nbsp;Progress</p>
<p> &nbsp;<?php echo $progressValue ; ?>% Completed</p>
<div style="width: 320px; height: 20px; margin-left: 6px;" id="progressbar"></div>
<br />
<div id="steps">
<table class="grid_2" >
<tr class="<?php echo $classesForStep[0]; ?>">
<td>1: License</td>
</tr>
<tr>
<td></td>
</tr>
<tr class="<?php echo $classesForStep[1]; ?>">
<td>2: Pre-installation check</td>
</tr>
<tr class="<?php echo $classesForStep[2]; ?>">
<td>3: Configuration </td>
</tr>
<tr class="<?php echo $classesForStep[3]; ?>">
<td>4: Database settings </td>
</tr>
<tr class="<?php echo $classesForStep[4]; ?>">
<td>5: Optional settings</td>
</tr>
</table>
</div>




</div>
<div class="grid_4 table">


<p class="title">&nbsp;<?php echo $title; ?></p>





<div style="-moz-border-radius:15px; border-radius:15px;" >
<p>&nbsp;<?php echo $descp; ?></p>
<hr />
<iframe src="<?php echo base_url(); ?>LICENSE.php" style="height: 268px; width: 694px; border-width: 0px;"> </iframe>

<br /><br />
</div>
</div>

</div>
<div class="container_6">
<div class="grid_2">&nbsp;</div>
<div class="grid_4 demo">
<br/>
<table style="width: 694px;">
<tbody>
<tr>
<td align="left" style="width: 300px;"><form action="<?php echo site_url("installer/install/0"); ?>" method="post" style="width: 300px;" name="formcheck">
<input type="checkbox" name="accept" id="cbStatus" checked="checked" /><span onclick="changecbStatus();">I accept the license terms above.</span></td>
<td align="center" style="width: 800px;"></td>
<td align="right" style="width: 190px;">
<div id="next" style="font-size:11px;"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" value="Next" /></div>
</form>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<?php $this->load->view("installer/footer_view"); ?>