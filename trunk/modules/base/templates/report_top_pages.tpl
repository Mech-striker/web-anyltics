<? if (!empty($top_pages)):?>
<table width="100%">
	<tr>
		<th scope="col">PageTitle</th>
		<th scope="col">Views</th>
	</tr>
				
	<?php foreach($top_pages as $page): ?>
				
	<TR>
		<TD>
			<a href="<?=$this->makeLink(array('do' => 'base.reportDocument', 'document_id' => $page['document_id']), true);?>"><?=$page['page_title'];?></a> (<?=$page['page_type'];?>) </TD>
		<TD><?=$page['count']?></TD>
	</TR>
				
	<?php endforeach; ?>

	</table>
<?else:?>
	There are no Page Views for this time period.
<?endif;?>