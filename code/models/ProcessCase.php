<?php
/*

**/

class ProcessCase extends DataObject{
	public static $db = array(
		'Title'=>'Varchar(255)',
		'Order'=>'Int'
	);

	public static $has_many = array(
		'ProcessInfo'=>'ProcessInfo'
	);

	public static $has_one = array(
		'ParentProcess'=>'Process'
	);
	
	public static $default_sort = 'Order';

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('Order');
		$fields->removeByName('Title');
		$fields->removeByName('ProcessInfo');
		$fields->removeByName('ParentProcessID');

		$fields->addFieldToTab('Root.Main', $processSteps = new CompositeField(
			$title = new TextField('Title','Title')
		));

		$title->addExtraClass('process-noborder');

		$processSteps->addExtraClass('process-step');

		$fields->addFieldToTab('Root.Main', $processSteps = new CompositeField(
			new GridField(
				'ProcessInfo', 
				'Information for this case', 
				$this->ProcessInfo(), 
				GridFieldConfig_RecordViewer::create())
		));

		$processes = Process::get();
		if ($processes) {
			$fields->insertAfter(
				$inner = new CompositeField(
					new LiteralField('ExplainStop', 
						'<label class="right">This must be set after you create a process</label>'
						),
					$processesOptions = new DropdownField(
						'ParentProcessID', 
						'Process', 
						$processes->map('ID', 'Title')
					)
				),'Title');

			$inner->addExtraClass('message special');
		}

		$processSteps->addExtraClass('process-step');

		$fields->insertBefore(new LiteralField('StageTitle', 
			'<h3 class="process-info-header">
				<span class="step-label">
					<span class="flyout">0.1</span><span class="arrow"></span>
					<span class="title">Case details</span>
				</span>
			</h3>'),'Title');
		$fields->insertBefore(new LiteralField('StageTitle', 
			'<h3 class="process-info-header">
				<span class="step-label">
					<span class="flyout">0.2</span><span class="arrow"></span>
					<span class="title">Associated Information Pieces</span>
				</span>
			</h3>'),'ProcessInfo');

		return $fields;
	}
	
}