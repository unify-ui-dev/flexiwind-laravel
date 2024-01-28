<?php

declare(strict_types=1);

use Flexiwind\Flexiwind\View\Components\Alerts\Alert;
use Illuminate\Testing\Assert;

it('can be instantiated with no arguments', function (): void {
    $alert = new Alert();

    Assert::assertInstanceOf(Alert::class, $alert);
});

it('returns null for type, message, and title when not provided', function (): void {
    $alert = new Alert();

    Assert::assertNull($alert->type);
    Assert::assertNull($alert->message);
    Assert::assertNull($alert->title);
});

it('returns provided type, message, and title', function (): void {
    $alert = new Alert('success', 'Test Message', 'Test Title');

    Assert::assertEquals('success', $alert->type);
    Assert::assertEquals('Test Message', $alert->message);
    Assert::assertEquals('Test Title', $alert->title);
});

it('is not dismissible by default', function (): void {
    $alert = new Alert();

    Assert::assertFalse($alert->dismissible);
});

it('can be dismissible', function (): void {
    $alert = new Alert(null, null, null, true);

    Assert::assertTrue($alert->dismissible);
});
