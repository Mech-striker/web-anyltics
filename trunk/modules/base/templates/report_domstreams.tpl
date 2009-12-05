<?php if (!empty($domstreams)):?>
<table class="tablesorter">
	<thead>
		<tr>
			<th>Date</th>
			<th>Page URL</th>
			<th>Duration</th>
			<th></th>
		</tr>
	</thead>
	<tbody>			
		<?php foreach($domstreams->rows as $ds): ?>
			
		<TR>
			<TD class="data_cell">
				<?php echo date("F j, Y, g:i a",$ds['timestamp']);?>
			</TD>
			<TD class="data_cell">
			<a href="<?php echo $ds['page_url'];?>">
				<?php echo $this->truncate($ds['page_url'], 150);?>			
			</a>
			</TD>
			
			<TD class="data_cell">
				<?php echo date("H:i:s", mktime(0,0,$ds['duration']));?>
			</TD>
			<TD class="data_cell">
				<a href="<?php echo $this->makeLink(array('do' => 'base.overlayLauncher', 'document_id' => $ds['document_id'], 'overlay_params' => urlencode($this->makeParamString(array('action' => 'loadPlayer', 'domstream_id' => $ds['id']), true, 'cookie'))));?>" target="_blank">Play</a>
			</TD>
		</TR>		
		<?php endforeach; ?>
	</tbody>
</table>

<?php echo $domstreams->displayPagination(array('do' => 'base.reportReferringSites'));?>

<?php else:?>
	There are no refering web pages for this time period.
<?php endif;?>