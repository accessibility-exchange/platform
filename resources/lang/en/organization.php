<?php

return [
    'singular_name' => 'organization',
    'index_title' => 'Organizations',
    'create_title' => 'Create an organization',
    'label_name' => 'Organization name',
    'action_create' => 'Create organization',
    'members_title' => 'Organization members',
    'browse_all' => 'Browse all organizations',
    'none_found' => 'No organizations found.',
    'member_name' => 'Name',
    'member_status' => 'Status',
    'member_role' => 'Role',
    'member_active' => 'Active',
    'action_add_member' => 'Add member',
    'delete_title' => 'Delete organization',
    'delete_intro' => 'Your organization will be deleted and cannot be recovered. If you still want to delete your organization, please enter your current password to proceed.',
    'action_delete' => 'Delete organization',
    'create_succeeded' => 'Your organization has been created.',
    'update_succeeded' => 'Your organization has been updated.',
    'destroy_succeeded' => 'Your organization has been deleted.',
    'edit_title' => 'Edit organization',
    'edit_organization' => 'Edit organization',
    'edit_user_role_link' => 'Edit',
    'edit_user_role_link_with_name' => 'Edit :user’s role',
    'label_user_role' => 'Role',
    'action_update_user_role' => 'Update Role',
    'action_cancel_user_role_update' => 'Cancel',
    'action_remove_member' => 'Remove',
    'action_remove_member_with_name' => 'Remove :user from :organization',
    'error_new_administrator_required_before_user_deletion' => 'You must assign a new administrator to your organization, :organization, before deleting your account.',
    'types' => [
        'representative' => [
            'name' => 'representative organization',
            'description' => 'Organizations “of” disability, Deaf, and family-based organizations. Constituted primarily by people with disabilities.'
        ],
        'support' => [
            'name' => 'support organization',
            'description' => 'Organizations that provide support “for” disability, Deaf, and family-based members. Not constituted primarily by people with disabilities.'
        ],
        'civil-society' => [
            'name' => 'civil society organization',
            'description' => 'Organizations which have some constituency of persons with disabilities, Deaf persons, or family members, but these groups are not their primary mandate. Groups served, for example, can include: Indigenous organizations, 2SLGBTQ+ organizations, immigrant and refugee groups, and women’s groups.'
        ],
    ],
    'area_types' => [
        'urban' => 'Urban areas',
        'rural' => 'Rural areas',
        'remote' => 'Remote areas',
    ],
];
