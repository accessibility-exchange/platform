<?php

use App\Filament\Resources\PaymentTypeResource;
use App\Filament\Resources\PaymentTypeResource\Pages\CreatePaymentType;
use App\Filament\Resources\PaymentTypeResource\Pages\EditPaymentType;
use App\Filament\Resources\PaymentTypeResource\Pages\ListPaymentTypes;
use App\Models\PaymentType;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => 'administrator']);
});

test('only administrative users can access payment type admin pages', function () {
    $user = User::factory()->create();
    $paymentType = PaymentType::factory()->create();

    actingAs($user)->get(PaymentTypeResource::getUrl('index'))->assertForbidden();
    actingAs($this->admin)->get(PaymentTypeResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(PaymentTypeResource::getUrl('create'))->assertForbidden();
    actingAs($this->admin)->get(PaymentTypeResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(PaymentTypeResource::getUrl('edit', [
        'record' => $paymentType,
    ]))->assertForbidden();
    actingAs($this->admin)->get(PaymentTypeResource::getUrl('edit', [
        'record' => $paymentType,
    ]))->assertSuccessful();
});

test('payment types can be listed', function () {
    actingAs($this->admin);

    $paymentTypes = PaymentType::factory(5)->create();

    livewire(ListPaymentTypes::class)
        ->assertCanSeeTableRecords($paymentTypes);
});

test('rendering create form', function () {
    livewire(CreatePaymentType::class)
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr');
});

test('filling create form', function () {
    livewire(CreatePaymentType::class)
        ->fillForm([
            'name.en' => 'test',
            'name.fr' => 'teste',
        ])
        ->assertFormSet([
            'name' => [
                'en' => 'test',
                'fr' => 'teste',
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    livewire(CreatePaymentType::class)
        ->fillForm([
            'name.en' => null,
            'name.fr' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name.en' => 'required',
            'name.fr' => 'required',
        ]);
});

test('rendering edit form', function () {
    $paymentType = PaymentType::factory()->create();

    livewire(EditPaymentType::class, ['record' => $paymentType->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr');
});

test('filling edit form', function () {
    $paymentType = PaymentType::factory()->create();

    livewire(EditPaymentType::class, ['record' => $paymentType->id])
        ->fillForm([
            'name.en' => 'test',
            'name.fr' => 'teste',
        ])
        ->assertFormSet([
            'name' => [
                'en' => 'test',
                'fr' => 'teste',
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    livewire(EditPaymentType::class, ['record' => $paymentType->id])
        ->fillForm([
            'name.en' => null,
            'name.fr' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name.en' => 'required',
            'name.fr' => 'required',
        ]);
});
