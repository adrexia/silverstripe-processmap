<?php
/*
 * Process Flow Admin provides an interface for the creation of process structures to be 
 * used to create diagrams of processes
 */

class ProcessFlowAdmin extends ModelAdmin implements PermissionProvider {
	public static $managed_models = array('Process', 'ProcessCase', 'ProcessInfo');
	public static $url_segment = 'processflow';
	public static $menu_title = 'Processes';
	public static $menu_icon = "processflow/images/process.png";
	

	public function getEditForm($id = null, $fields = null){
		$form = parent::getEditForm($id, $fields);
		
		$gridField = $form->Fields()->fieldByName(
			$this->sanitiseClassName($this->modelClass)
		);
		$gridField->getConfig()->addComponents(
			new GridFieldAddExistingAutocompleter('buttons-before-left'),
			$filter = new GridFieldFilterHeader(),
			new GridFieldEditButton(),
			new GridFieldDeleteAction(true),
			new GridFieldDetailForm()
		);
		return $form;
	}

	public function providePermissions() {
		return array(
			'PROCESS_FLOW_VIEW' => array(
				'name' => 'View process flow admin',
				'category' => 'Process Flow',
			),
			'PROCESS_FLOW_EDIT' => array(
				'name' => 'Edit process flows',
				'category' => 'Process Flow',
			),
			'PROCESS_FLOW_DELETE' => array(
				'name' => 'Delete from process flows',
				'category' => 'Process Flow',
			),
			'PROCESS_FLOW_CREATE' => array(
				'name' => 'Create process flows',
				'category' => 'Process Flow'
			)
		);
	}
}