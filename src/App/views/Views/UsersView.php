<?php

namespace App\Views;

class UsersView extends View {
  public function getManageUsersTemplate(array $data = []): string
  {
    $data['tableColNames'] = ['ID', 'Username', 'Email', 'Role'];
    $data['displayData'] = [];
    foreach ($data['users'] as $user) {
      array_push($data['displayData'], ['ID' => $user->getId(), 'Username' => $user->getUsername(),'Email' => $user->getEmail(),'Role' => $user->getRole()]);
    }
    $deleteButton = $this->templateEngine->render(
            'partials/_deleteButton.php',
            [
            'method' => 'DELETE'
            ]
    );
    $data['actionButtons'] = [$deleteButton];
    return $this->templateEngine->render('manageUsers.php', $data);
  }

  public function getEditUserTemplate(array $data = []): string
  {
    return $this->templateEngine->render('user/editUser.php', $data);
  }
}