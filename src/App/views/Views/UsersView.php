<?php

namespace App\Views;

class UsersView extends View {
  public function getManageUsersTemplate(array $data = []): string
  {
    $data['tableColNames'] = ['ID', 'Username', 'Email', 'Role'];
//    $data['tableColValues'] = [];
    $data['displayData'] = [];
    foreach ($data['users'] as $user) {
      array_push($data['displayData'], ['ID' => $user->getId(), 'Username' => $user->getUsername(),'Email' => $user->getEmail(),'Role' => $user->getRole()]);
    }
    $deleteButton = $this->templateEngine->render(
            'partials/_deleteButton.php',
            [
                    'action' => '/delete-article/' ]
    );
    $data['actionButtons'] = [$deleteButton];
//    $tableColNames = ['ID', 'Username', 'Email', 'Role',];
    return $this->templateEngine->render('manageData.php', $data);
  }
}