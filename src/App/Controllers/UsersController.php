<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Views\{LayoutView, UsersView};
use App\Services\{ImageService};

class UsersController extends ControllerWIthPagination
{
  public function __construct(
          private LayoutView $layoutView,
          private UsersView $usersView,
          private Category $categoryModel,
          private User $userModel,
          private ImageService $imageService,
  ) {
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
}
