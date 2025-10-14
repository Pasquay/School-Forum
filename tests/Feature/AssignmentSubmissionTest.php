<?php

use App\Models\Assignment;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('blocks submission after due when late is not allowed', function () {
    $user = User::factory()->create();
    $group = Group::factory()->create();

    // Add user as a member of the group (required to submit)
    DB::table('group_members')->insert([
        'group_id' => $group->id,
        'user_id' => $user->id,
        'role' => 'member',
        'is_starred' => 0,
        'is_muted' => 0,
    ]);

    $assignment = Assignment::create([
        'group_id' => $group->id,
        'created_by' => $user->id, // creator can be the same user; not required for submit
        'assignment_name' => 'Late Block Test',
        'description' => 'desc',
        'assignment_type' => 'essay',
        'submission_type' => 'text',
        'max_points' => 10,
        'visibility' => 'published',
        'date_assigned' => now()->subDay(),
        'date_due' => now()->subMinute(), // already past due
        'allow_late_submissions' => false,
    ]);

    actingAs($user);

    $response = post(route('group.submitAssignment', [
        'groupId' => $group->id,
        'assignmentId' => $assignment->id,
    ]), [
        'submission_text' => 'my answer',
    ]);

    $response->assertSessionHas('error');
});

it('allows submission after due when late is allowed', function () {
    $user = User::factory()->create();
    $group = Group::factory()->create();

    DB::table('group_members')->insert([
        'group_id' => $group->id,
        'user_id' => $user->id,
        'role' => 'member',
        'is_starred' => 0,
        'is_muted' => 0,
    ]);

    $assignment = Assignment::create([
        'group_id' => $group->id,
        'created_by' => $user->id,
        'assignment_name' => 'Late Allowed Test',
        'description' => 'desc',
        'assignment_type' => 'essay',
        'submission_type' => 'text',
        'max_points' => 10,
        'visibility' => 'published',
        'date_assigned' => now()->subDay(),
        'date_due' => now()->subMinute(), // already past due
        'allow_late_submissions' => true,
    ]);

    actingAs($user);

    $response = post(route('group.submitAssignment', [
        'groupId' => $group->id,
        'assignmentId' => $assignment->id,
    ]), [
        'submission_text' => 'my answer',
    ]);

    $response->assertSessionHas('success');
});

it('allows submission before due when late is not allowed', function () {
    $user = User::factory()->create();
    $group = Group::factory()->create();

    DB::table('group_members')->insert([
        'group_id' => $group->id,
        'user_id' => $user->id,
        'role' => 'member',
        'is_starred' => 0,
        'is_muted' => 0,
    ]);

    $assignment = Assignment::create([
        'group_id' => $group->id,
        'created_by' => $user->id,
        'assignment_name' => 'On Time Test',
        'description' => 'desc',
        'assignment_type' => 'essay',
        'submission_type' => 'text',
        'max_points' => 10,
        'visibility' => 'published',
        'date_assigned' => now()->subDay(),
        'date_due' => now()->addHour(), // in the future
        'allow_late_submissions' => false,
    ]);

    actingAs($user);

    $response = post(route('group.submitAssignment', [
        'groupId' => $group->id,
        'assignmentId' => $assignment->id,
    ]), [
        'submission_text' => 'my answer',
    ]);

    $response->assertSessionHas('success');
});
