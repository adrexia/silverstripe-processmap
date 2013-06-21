<?php
/*

**/

class ProcessStopStage extends Stage{

	public static $has_one = array(
		'Parent'=>'Process'
	);

	public static $has_many = array(
		'ProcessStages'=>'ProcessStage'
	);

	public static $searchable_fields = array(
		'Title',
		'Parent.Title',
	);

	public static $summary_fields = array(
		'Title'=>'Title',
		'PiecesOfInfo'=>'Pieces of Info (num)',
		'Parent.Title'=>'Process Title'
	);
	
	public static $default_sort = 'Order';

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('Order');
		$fields->removeByName('ProcessInfo');
		$fields->removeByName('ProcessStages');

		$processParent = Process::get();
		if ($processParent) {
			$fields->insertAfter(
				new DropdownField(
					'ParentID', 
					'Belongs to this Process', 
					$processParent->map('ID', 'Title')
				), 'Title');
		}

		$fields->addFieldToTab('Root.Main', $processSteps = new CompositeField(
			new GridField(
				'ProcessInfo', 
				'Information for this stage', 
				$this->ProcessInfo(), 
				GridFieldConfig_RelationEditor::create())
		));

		$fields->insertBefore(new LiteralField('StageTitle', 
		'<h3 class="process-info-header">
			<span class="step-label">
				<span class="flyout">1.1</span><span class="arrow"></span>
				<span class="title">Stop stage details</span>
			</span>
		</h3>'),'Title');

		$fields->insertBefore(new LiteralField('StageTitle', 
		'<h3 class="process-info-header">
			<span class="step-label">
				<span class="flyout">1.2</span><span class="arrow"></span>
				<span class="title">Final information</span>
			</span>
		</h3>'),'ProcessInfo');
		
		return $fields;
	}

}