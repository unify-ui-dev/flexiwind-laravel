<?php

declare(strict_types=1);

use Flexiwind\Flexiwind\View\Components\Inputs\TextInput;

it('can be instantiated with no arguments', function (): void {
    $textInput = new TextInput();
    expect($textInput)->toBeInstanceOf(TextInput::class);
});

it('returns text as default type', function (): void {
    $textInput = new TextInput();
    expect($textInput->getType())->toBe('text');
});

it('returns provided type', function (): void {
    $textInput = new TextInput(null, null, null, 'password');
    expect($textInput->getType())->toBe('password');
});

it('returns null for name value and placeholder when not provided', function (): void {
    $textInput = new TextInput();
    expect($textInput->name)->toBeNull()
        ->and($textInput->value)->toBeNull()
        ->and($textInput->placeholder)->toBeNull();
});

it('returns provided name value and placeholder', function (): void {
    $textInput = new TextInput('testName', 'testValue', 'testPlaceholder');
    expect($textInput->name)->toBe('testName')
        ->and($textInput->value)->toBe('testValue')
        ->and($textInput->placeholder)->toBe('testPlaceholder');
});
