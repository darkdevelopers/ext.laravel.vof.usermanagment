<?php

return [
    'index' => [
        'headline' => 'Usermanagment',
    ],
    'create' => [
        'headline' => 'Create Admin',
    ],
    'edit' => [
        'headline' => 'Edit Admin',
    ],
    'show' => [
        'headline' => 'Show Admin',
    ],
    'partials' => [
        'table' => [
            'create-new-user-btn' => 'Create Admin',
            'username' => 'Name',
            'email' => 'E-Mail Address',
            'created' => 'Create at',
            'updated' => 'Updated at',
            'actions' => 'Actions',
            'delete_button' => '<i class="fas fa-fw fa-trash-alt" aria-hidden="true"></i>',
            'delete_tooltip' => 'Delete Admin',
            'show_tooltip' => 'Show Admin',
            'edit_tooltip' => 'Edit Admin',
            'delete_user_title' => 'Delete Admin sure?',
            'delete_user_message' => 'Are you sure you want to delete :admin?',
            'delete_user_btn_cancel' => '<i class="fas fa-fw fa-times" aria-hidden="true"></i> Cancel',
            'delete_user_btn_confirm' => '<i class="fas fa-fw fa-trash-alt" aria-hidden="true"></i> Delete',
            'user-delete-success' => 'Successfully deleted the admin!',
            'edit_button' => '<a href=":route" class="btn btn-primary"><i class="fas faw fa-edit" aria-hidden="true"></i></a>',
            'show_button' => '<a href=":route" class="btn btn-secondary"><i class="fas faw fa-eye" aria-hidden="true"></i></a>',
        ],
        'create-read-update' => [
            'username' => 'Name',
            'email' => 'E-Mail Adress',
            'password' => 'Password',
            'btn-create' => 'Create',
            'btn-edit' => 'Edit',
            'btn-save' => 'Save',
            'username-required' => 'The username field is required.',
            'email-required' => 'The email-adress field is required.',
            'password-required' => 'The password field is required.',
            'email-isexists' => 'The email adress is exists',
            'email-is-wrong' => 'This email adress is not valid',
            'password-too-short' => 'Your password is to short. 8 length minimum.',
            'admin-cant-created' => 'This admin cant created',
            'admin-created-success' => 'Successfully created admin account!'
        ]
    ]
];
