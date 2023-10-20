<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Views\{LayoutView, UsersView};
use App\Services\{ErrorMessagesService, FormValidatorService, ImageService};

class UsersController extends ControllerWIthPagination
{
  public function __construct(
          private LayoutView $layoutView,
          private UsersView $usersView,
          private FormValidatorService $formValidatorService,
          private ErrorMessagesService $errorMessagesService,
          private User $userModel,
          private ImageService $imageService,
  ) {
    $this->formValidatorService->addRulesToField('username', ['required', 'lengthMax:100']);
    $this->formValidatorService->addRulesToField('email', ['required', 'email', 'lengthMax:500']);
  }

  public function prepareUsersData($category = '', $provideAllCategories = false): array
  {
    $usersWithPagination = $this->prepareDataWithPagination('users', [$this->userModel, 'getAllUsers']);
    $usersImages = $this->imageService->createB64ImageArray($usersWithPagination['users']);
    return
            $usersWithPagination + [
                    'sectionTitle'       => $category === '' ? 'Latest Articles' : $category,
                    'articleImages'      => $usersImages
            ]
            ;
  }

  public function renderManageUsers()
  {
    $category = $_GET['category'] ?? '';
    $dataForDisplayingUsers = $this->prepareUsersData($category, true);
    $manageArticlesTemplate = $this->usersView->getManageUsersTemplate(
            $dataForDisplayingUsers
    );
    $this->layoutView->renderPage($manageArticlesTemplate);
  }

  public function deleteUser($params) {
    $this->userModel->delete((int)$params['user']);
    redirectTo('/manage-users');
  }

  public function renderEditUser(array $params) {
    $user = $this->userModel->getUserById(
            (int) $params['user']
    );
    $editArticleTemplate = $this->usersView->getEditUserTemplate(
            [
                    'user' => $user
            ]
    );
    $this->layoutView->renderPage($editArticleTemplate);
  }

  public function editUser(array $params)
  {
    $errors = $this->formValidatorService->validate($_POST);
    if (count($errors)) {
      $this->errorMessagesService->setErrorMessage($errors);
    }
    $this->userModel->edit($_POST, (int)$params['user']);
    redirectTo('/manage-users');
  }
}
