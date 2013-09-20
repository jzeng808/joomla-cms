<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_services
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Load tooltips behavior
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'j.cancel' || document.formvalidator.isValid(document.id('application-form'))) {
			Joomla.submitform(task, document.getElementById('application-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_services');?>" id="application-form" method="post" name="adminForm" class="form-validate">
	<div class="row-fluid">
		
		<!-- Begin Content -->
		<div class="span10">
		
			<div class="btn-toolbar">
				<div class="btn-group">
					<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('services.save.config.apply')">
						<i class="icon-ok"></i> <?php echo JText::_('JSAVE') ?>
					</button>
				</div>
				<div class="btn-group">
					<button type="button" class="btn" onclick="Joomla.submitbutton('j.cancel')">
						<i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
					</button>
				</div>
			</div>

			<hr class="hr-condensed" />

			<div id="page-site" class="tab-pane active">
				<div class="row-fluid">
					<div class="span6">
						<?php echo $this->loadTemplate('site'); ?>
						<?php echo $this->loadTemplate('metadata'); ?>
						<?php echo $this->loadTemplate('seo'); ?>

					</div>
				</div>
			</div>

			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>

		</div>
		<!-- End Content -->
	</div>
</form>
