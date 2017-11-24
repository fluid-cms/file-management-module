<?php

namespace Grapesc\GrapeFluid\FileManagementFileManagementModule;

use Grapesc\GrapeFluid\CoreModule\AclFormTrait;
use Grapesc\GrapeFluid\FileManagementModule\Model\CategoryModel;
use Grapesc\GrapeFluid\FluidFormControl\FluidForm;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\DateTime;
use Nette\Security\User;


class CategoryForm extends FluidForm
{

	use AclFormTrait;

	/** @var CategoryModel @inject */
	public $model;

	/** @var User @inject */
	public $user;


	/**
	 * @param Form $form
	 */
	protected function build(Form $form)
	{
		$form->addHidden("id");

		$form->addText("name", "Název kategorie")
			->setAttribute("cols", 6)
			->addRule(Form::MAX_LENGTH, "Maximální velikost je %s znaků", 80)
			->setRequired("Vyplntě název galerie");

		$form->addTextArea("description", "Popis kategorie")
			->setRequired(false)
			->setAttribute("cols", 6)
			->addRule(Form::MAX_LENGTH, "Maximální velikost je %s znaků", 65535);

		$this->addAclInput('file.category');

	}


	/**
	 * @param Control $control
	 * @param Form $form
	 */
	protected function submit(Control $control, Form $form)
	{
		$presenter = $control->getPresenter();
		$values    = $form->getValues(true);

		if ($values['id']) {
			$this->model->update($this->model->clearingValues($values), $values['id']);
			$presenter->flashMessage("Změny uloženy", "success");
			$this->saveAcl();
			$presenter->redirect('category');
		} else {
			unset($values['id']);

			$values['created_by_id'] = $this->user->getId();
			$values['created_on']    = new DateTime();

			$createdId = $this->model->insert($this->model->clearingValues($values));
			$presenter->flashMessage("Categorie vytvořena", "success");
			$presenter->redirect("categoryFiles", $createdId);
		}
	}

}