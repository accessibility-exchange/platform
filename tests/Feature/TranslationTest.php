<?php

use App\Models\CommunityMember;

test('adding a translation succeeds for a valid translatable model', function () {
    $communityMember = CommunityMember::factory()->create();

    $response = $this->actingAs($communityMember->user)
        ->from(localized_route('community-members.edit', $communityMember))
        ->put(localized_route('translations.add'), [
            'translatable_type' => get_class($communityMember),
            'translatable_id' => $communityMember->id,
            'new_language' => 'ase',
        ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();

    expect(in_array('ase', $communityMember->languages))->toBeTrue();
});

test('removing a translation succeeds for a valid translatable model', function () {
    $communityMember = CommunityMember::factory()->create();

    $response = $this->actingAs($communityMember->user)
        ->from(localized_route('community-members.edit', $communityMember))
        ->put(localized_route('translations.destroy'), [
            'translatable_type' => get_class($communityMember),
            'translatable_id' => $communityMember->id,
            'language' => 'fr',
        ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();

    expect(in_array('fr', $communityMember->languages))->toBeFalse();
});
