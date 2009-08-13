<div class="owa_infobox">					
	<table cellpadding="0" cellspacing="0" width="" border="0" class="visit_summary">
		<TR>
			<!-- left col -->
			<TD valign="top" class="owa_visitSummaryLeftCol">
				<span class="h_label"><?=$visit['session_month'];?>/<?=$visit['session_day'];?> @ at <?=$visit['session_hour'];?>:<?=$visit['session_minute'];?></span> | <span class="info_text"><?=$visit['host_host'];?> <? if ($visit['host_city']):?>- <?=$visit['host_city'];?>, <?=$visit['host_country'];?><? endif;?></span> <?=$this->choose_browser_icon($visit['ua_browser_type']);?><BR>		
				<table>
					<TR>
						<TD class="visit_icon" align="right" valign="bottom">
							<span class="h_label">
								<? if ($visit['session_is_new_visitor'] == true): ?>
								<img src="<?=$this->makeImageLink('base/i/newuser_icon_small.png');?>" alt="New Visitor" >
								<? else:?>
								<img src="<?=$this->makeImageLink('base/i/user_icon_small.png');?>" alt="Repeat Visitor">
								<? endif;?>
							</span>
						</TD>
						
						<TD valign="bottom">
							 <a href="<?=$this->makeLink(array('do' => 'base.reportVisitor', 'visitor_id' => $visit['visitor_id'], 'site_id' => $site_id));?>">
							 	<span class="inline_h2"><? if (!empty($visit['visitor_user_name'])):?><?=$visit['visitor_user_name'];?><? elseif (!empty($visit['visitor_user_email'])):?><?=$visit['visitor_user_email'];?><? else: ?><?=$visit['visitor_id'];?><? endif; ?></span>
							 </a>
							<? if ($visit['session_is_new_visitor'] == false): ?>
								<? if (!empty($visit['session_prior_session_id'])): ?>	
								- <span class="info_text">(<a href="<?=$this->makeLink(array('session_id' => $visit['session_prior_session_id'], 'do' => 'base.reportVisit'), true);?>">Last visit was</a>	<?=round($visit['session_time_sinse_priorsession']/(3600*24));?> 
									<? if (round($visit['session_time_sinse_priorsession']/(3600*24)) == 1): ?>
										day ago.
									<? else: ?>
										days ago.
									<? endif; ?>
									)</span>
								<? endif;?>
							<? endif;?>
						</TD>
					</TR>							
					<TR>					
						<TD class="visit_icon" align="right" valign="top"><span class="h_label">
							<img src="<?=$this->makeImageLink('base/i/document_icon.gif');?>" alt="Entry Page"></span>
						</TD>
												
						<TD valign="top">
							<a href="<?=$visit['document_url'];?>"><span class="inline_h4"><?=$visit['document_page_title'];?></span></a><? if($visit['document_page_type']):?> (<?=$visit['document_page_type'];?>)<? endif;?><BR><span class="info_text"><?=$visit['document_url'];?></span>
						</TD>							
					</TR>
					<?php if (!empty($visit['referer_url'])):?>		
					<TR>
						<TD class="visit_icon" rowspan="2" align="right" valign="top">
						
							<span class="h_label"><img src="<?=$this->makeImageLink('base/i/referer_icon.gif');?>" alt="Refering URL"></span>
						</TD>
				
						<TD valign="top" colspan="2">
							<a href="<?=$visit['referer_url'];?>"><? if (!empty($visit['referer_page_title'])):?><span class="inline_h4"><?=$this->truncate($visit['referer_page_title'], 80, '...');?></span></a><BR><span class="info_text"><?=$this->truncate($visit['referer_url'], 80, '...');?></span><? else:?><?=$this->truncate($visit['referer_url'], 50, '...');?><? endif;?></a>
						</TD>
																		
					</TR>	
					<?php endif;?>
					<?php if (!empty($visit['referer_snippet'])):?>			
					<TR>
						<TD colspan="1">
							<span class="snippet_text"><?=$visit['referer_snippet'];?></span>
						</TD>
						
					</TR>
					<?php endif;?>
				</table>
				
			</TD>
			<!-- right col -->
			<TD valign="top" align="right" class="owa_visitSummaryRightCol">
				
				<div class="visitor_info_box pages_box">
					<a href="<?=$this->makeLink(array('session_id' => $visit['session_id'], 'do' => 'base.reportVisit'), true);?>"><span class="large_number"><?=$visit['session_num_pageviews'];?></span></a>
					<br />
					<span class="info_text">Pages</span>
				</div>
				<BR>				
				<?php if (!empty($visit['session_num_comments'])):?>
				<div class="comments_info_box">
					<span class="large_number"><?=$visit['session_num_comments'];?></span><br /><span class="info_text"></span></a>
				</div>
				<?php endif;?>
				
			</TD>
		</TR>
	</table>	
									
</div>