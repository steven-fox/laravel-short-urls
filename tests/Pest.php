<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use StevenFox\Larashurl\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
uses(RefreshDatabase::class)->in('Feature');
