<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$form = $displayData->getForm();

$fieldSets = $form->getFieldsets('params');
if (empty($fieldSets))
{
	$fieldSets = $form->getFieldsets('attribs');
}

if (empty($fieldSets))
{
	return;
}

$ignoreFieldsets = $displayData->get('ignore_fieldsets') ?: array();
$ignoreFields = $displayData->get('ignore_fields') ?: array();
$extraFields = $displayData->get('extra_fields') ?: array();

if ($displayData->get('show_options', 1))
{
	foreach ($fieldSets as $name => $fieldSet)
	{
		if (in_array($name, $ignoreFieldsets))
		{
			continue;
		}

		if (!empty($fieldSet->label))
		{
			$label = JText::_($fieldSet->label, true);
		}
		else
		{
			$label = strtoupper('JGLOBAL_FIELDSET_' . $name);
			if (JText::_($label, true) == $label)
			{
				$label = strtoupper($app->input->get('option') . '_' . $name . '_FIELDSET_LABEL');
			}
			$label = JText::_($label, true);
		}

		echo JHtml::_('bootstrap.addTab', 'myTab', 'attrib-' . $name, $label);

		$displayData->fieldset = $name;
		echo JLayoutHelper::render('joomla.edit.fieldset', $displayData);

		echo JHtml::_('bootstrap.endTab');
	}
}
else
{
	$html = array();
	$html[] = '<div styl="display:hidden;">';
	foreach ($fieldSets as $name => $fieldSet)
	{
		if (in_array($name, $ignoreFieldsets))
		{
			continue;
		}

		foreach ($form->getFieldset($name) as $field)
		{
			$html[] = $field->input;
		}
	}
	$html[] = '</div>';

	echo implode('', $html);
}
