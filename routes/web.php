<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReplyVoteController;
use App\Http\Controllers\CommentVoteController;
use App\Http\Controllers\InboxMessageController;

Route::controller(UserController::class)->group(function () {
    Route::get('/', 'showLanding')->name('login'); // load login page
    Route::post('/login', 'login')->name('login.submit'); // user login
    Route::post('/google-login', 'googleLogin'); // google login
    Route::post('/register', 'register'); // user register
    Route::post('/logout', 'logout')->middleware('auth'); // user logout
    Route::get('/user/{id}', 'loadUser')->middleware('auth'); // load user
    Route::get('/user/{id}/settings', 'loadSettings')->middleware('auth')->name('user.settings'); // load user settings
    Route::post('/user/{id}/settings/update-public-information', 'updatePublicProfile')->middleware('auth')->name('user.updatePublicProfile'); // update public profile details
    Route::get('/user/{id}/overview', 'getUserOverview')->middleware('auth'); // load page 2+ user overview
    Route::get('/user/{id}/comments-and-replies', 'getUserCommentsAndReplies')->middleware('auth'); // load page 2+ user comments and replies
    Route::get('/user/{id}/deleted-overview', 'getUserDeletedOverview')->middleware('auth'); // load page 2+ deleted user overview
    Route::get('/user/{id}/deleted-comments-and-replies', 'getUserDeletedCommentsAndReplies')->middleware('auth'); // load page 2+ deleted user comments and replies
    Route::get('/groups/{groupId}/search-users', 'searchUsers')->middleware('auth')->name('group.searchUsers'); // search for users by name
    // Password reset routes
    Route::post('/password/email', 'sendPasswordResetEmail')->name('password.email'); // send reset email
    Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset'); // show reset form
    Route::post('/password/reset', 'resetPassword')->name('password.update'); // update password
    // Google Account Link Routes
    Route::post('/user/link-google', 'redirectToGoogle')->middleware('auth')->name('user.linkGoogle'); // redirect to Google Auth
    Route::get('/user/link-google/callback', 'handleGoogleCallback')->middleware('auth')->name('user.linkGoogle.callback'); // Save the Google Auth Data
});

Route::controller(PostController::class)->group(function () {
    Route::get('/home', 'getLatest')->middleware('auth'); // load home page
    Route::get('/post/{id}', 'getPost')->middleware('auth'); // load post page
    Route::post('/create-post/{id}', 'create')->middleware('auth'); // create post
    Route::post('/pin-post-home/{id}', 'pinHomeToggle')->middleware('auth'); // pin post to home
    Route::post('/pin-post/{id}', 'pinToggleRouter')->middleware('auth'); // pin post
    Route::post('/edit-post/{id}', 'edit')->middleware('auth'); // edit post
    Route::post('/delete-post/{id}', 'delete')->middleware('auth'); // delete post
    Route::get('/user/{id}/posts', 'getUserPost')->middleware('auth'); // load page 2+ user posts
    Route::get('/user/{id}/deleted-posts', 'getUserDeletedPosts')->middleware('auth'); // load page 2+ user deleted posts
    Route::post('/restore-post/{id}', 'restore')->middleware('auth'); // restore deleted post
});

Route::controller(VoteController::class)->group(function () {
    // Home Posts
    Route::post('/home/upvote/{id}', 'togglePostUpvote')->middleware('auth'); // upvote post
    Route::post('/home/downvote/{id}', 'togglePostDownvote')->middleware('auth'); // downvote post
    // Post Pages
    Route::post('/post/upvote/{id}', 'togglePostUpvote')->middleware('auth'); // upvote post
    Route::post('/post/downvote/{id}', 'togglePostDownvote')->middleware('auth'); // downvote post
});

Route::controller(CommentController::class)->group(function () {
    Route::post('/post/{postId}/create-comment', 'create')->middleware('auth'); // create comment
    Route::post('/post/{postId}/edit-comment/{commentId}', 'edit')->middleware('auth'); // edit comment
    Route::post('/post/{postId}/delete-comment/{commentId}', 'delete')->middleware('auth'); // delete comment
    Route::get('/user/{id}/comments', 'getUserComment')->middleware('auth'); // fetch page 2+ user comments
    Route::get('/user/{id}/deleted-comments', 'getUserDeletedComments')->middleware('auth'); // fetch page 2+ user deleted comments
    Route::post('/restore-comment/{id}', 'restore')->middleware('auth'); // restore deleted comment
});

Route::controller(CommentVoteController::class)->group(function () {
    Route::post('/comment/upvote/{id}', 'toggleCommentUpvote')->middleware('auth'); // upvote comment
    Route::post('/comment/downvote/{id}', 'toggleCommentDownvote')->middleware('auth'); // downvote comment
});

Route::controller(ReplyController::class)->group(function () {
    Route::get('/comment/{id}/replies', 'getReplies')->middleware('auth'); // load replies
    Route::post('/post/{postId}/comment/{commentId}/create-reply', 'create')->middleware('auth'); // create reply
    Route::post('/post/{postId}/edit-reply/{replyId}', 'edit')->middleware('auth'); // edit reply
    Route::post('/post/{postId}/delete-reply/{replyId}', 'delete')->middleware('auth'); // delete reply
    Route::get('/user/{id}/replies', 'getUserReply')->middleware('auth'); // fetch page 2+ user replies
    Route::get('/user/{id}/deleted-replies', 'getUserDeletedReplies')->middleware('auth'); // fetch page 2+ user deleted replies
    Route::post('/restore-reply/{id}', 'restore')->middleware('auth'); // restore deleted reply
});

Route::controller(ReplyVoteController::class)->group(function () {
    Route::post('/reply/upvote/{id}', 'toggleReplyUpvote')->middleware('auth'); // upvote comment
    Route::post('/reply/downvote/{id}', 'toggleReplyDownvote')->middleware('auth'); // downvote comment
});

Route::controller(GroupController::class)->group(function () {
    Route::get('/groups', 'showGroups')->middleware('auth'); // show groups page
    Route::get('/groups/create', 'showCreateGroup')->middleware('auth'); // show group creation form
    Route::post('/groups/create-submit', 'createGroup')->middleware('auth'); // create group
    Route::get('/groups/manage', 'showGroupsManager')->middleware('auth')->name('groups.manager'); // show groups manager page
    Route::get('/groups/manage/search', 'searchGroupsManager')->middleware('auth')->name('groups.manager.search'); // search groups page
    Route::get('/groups/{page}', 'showGroupsPaginated')->middleware('auth'); // show page 2+ groups page
    Route::post('/group/toggleStar/{id}', 'toggleStar')->middleware('auth'); // star/unstar a group
    route::post('/group/setStar/', 'setStar')->middleware('auth')->name('group.set.star'); // set star manually
    Route::post('/group/toggleMute/{id}', 'toggleMute')->middleware('auth'); // mute/unmute a group
    route::post('/group/setMute/', 'setMute')->middleware('auth')->name('group.set.mute'); // set mute manually
    Route::post('/group/{id}/request', 'requestToJoinGroup')->middleware('auth'); // request to join a group
    Route::post('/group/{id}/join', 'joinGroup')->middleware('auth')->name('group.join'); // join a group
    Route::post('/group/{id}/leave', 'leaveGroup')->middleware('auth')->name('group.leave'); // leave a group
    Route::post('/group/{id}/leave-alt', 'leaveGroupAlt')->middleware('auth')->name('group.leave.alt'); // leave a group via group manager
    Route::get('/group/{id}/settings', 'showGroupSettings')->middleware('auth'); // manage a group/go to group settings

    //ASSignments - MUST come before other /group/{id} routes to avoid conflicts
    Route::post('/group/{id}/create-assignment', 'createAssignment')->middleware('auth')->name('group.createAssignment'); // create assignment
    Route::get('/group/{id}/assignments', 'getAssignments')->middleware('auth'); // get assignments for a group
    Route::get('/group/{groupId}/assignments/{assignmentId}', 'getAssignment')->middleware('auth')->name('group.getAssignment'); // get single assignment
    Route::post('/group/{groupId}/assignments/{assignmentId}', 'updateAssignment')->middleware('auth')->name('group.updateAssignment'); // update assignment
    Route::delete('/group/{groupId}/assignments/{assignmentId}', 'deleteAssignment')->middleware('auth')->name('group.deleteAssignment'); // delete assignment

    // Student Assignment Submissions
    Route::post('/group/{groupId}/assignments/{assignmentId}/submit', 'submitAssignment')->middleware('auth')->name('group.submitAssignment'); // submit assignment
    Route::post('/group/{groupId}/assignments/{assignmentId}/save-draft', 'saveDraft')->middleware('auth')->name('group.saveDraft'); // save draft
    Route::get('/group/{groupId}/assignments/{assignmentId}/my-submission', 'getMySubmission')->middleware('auth')->name('group.getMySubmission'); // get my submission
    Route::post('/group/{groupId}/assignments/{assignmentId}/start-quiz', 'startQuiz')->middleware('auth')->name('group.startQuiz'); // start quiz timer
    Route::post('/group/{groupId}/assignments/{assignmentId}/save-quiz-progress', 'saveQuizProgress')->middleware('auth')->name('group.saveQuizProgress'); // save quiz progress

    // Teacher - View All Submissions
    Route::get('/group/{groupId}/assignments/{assignmentId}/submissions', 'getAssignmentSubmissions')->middleware('auth')->name('group.getAssignmentSubmissions'); // get all submissions for assignment
    Route::get('/group/{groupId}/assignments/{assignmentId}/submissions/{studentId}', 'getStudentSubmission')->middleware('auth')->name('group.getStudentSubmission'); // get single student submission
    Route::post('/group/{groupId}/assignments/{assignmentId}/submissions/{studentId}/grade', 'gradeSubmission')->middleware('auth')->name('group.gradeSubmission'); // grade student submission

    // Quiz Management (for teachers)
    Route::get('/group/{groupId}/assignments/{assignmentId}/quiz-questions', 'getQuizQuestions')->middleware('auth')->name('group.getQuizQuestions'); // get quiz questions
    Route::post('/group/{groupId}/assignments/{assignmentId}/quiz-questions', 'saveQuizQuestions')->middleware('auth')->name('group.saveQuizQuestions'); // bulk save quiz questions
    Route::post('/group/{groupId}/assignments/{assignmentId}/quiz/questions', 'addQuizQuestion')->middleware('auth')->name('group.addQuizQuestion'); // add quiz question
    Route::put('/group/{groupId}/assignments/{assignmentId}/quiz/questions/{questionId}', 'updateQuizQuestion')->middleware('auth')->name('group.updateQuizQuestion'); // update quiz question
    Route::delete('/group/{groupId}/assignments/{assignmentId}/quiz/questions/{questionId}', 'deleteQuizQuestion')->middleware('auth')->name('group.deleteQuizQuestion'); // delete quiz question

    // Rubric System
    Route::get('/group/{groupId}/assignments/{assignmentId}/rubrics', 'getRubrics')->middleware('auth')->name('group.getRubrics'); // get rubrics
    Route::post('/group/{groupId}/assignments/{assignmentId}/rubrics', 'saveRubrics')->middleware('auth')->name('group.saveRubrics'); // save rubrics
    Route::post('/group/{groupId}/assignments/{assignmentId}/submissions/{studentId}/grade-rubric', 'gradeWithRubric')->middleware('auth')->name('group.gradeWithRubric'); // grade with rubric

    // Resubmission System
    Route::get('/group/{groupId}/assignments/{assignmentId}/attempts', 'getAllSubmissionAttempts')->middleware('auth')->name('group.getAllSubmissionAttempts'); // get all submission attempts

    // Analytics Dashboard
    Route::get('/group/{groupId}/assignments/{assignmentId}/analytics', 'getAssignmentAnalytics')->middleware('auth')->name('group.getAssignmentAnalytics'); // get assignment analytics

    // Submission Comments/Feedback
    Route::post('/group/{groupId}/assignments/{assignmentId}/submissions/{studentId}/comments', 'addSubmissionComment')->middleware('auth')->name('group.addSubmissionComment'); // add submission comment
    Route::get('/group/{groupId}/assignments/{assignmentId}/submissions/{studentId}/comments', 'getSubmissionComments')->middleware('auth')->name('group.getSubmissionComments'); // get submission comments

    Route::get('/group/{id}', 'showGroup')->middleware('auth')->name('group.show'); // show a group's page
    // Group management routes
    Route::put('/group/{id}', 'update')->middleware('auth')->name('group.update'); // update group settings
    Route::post('/group/{groupId}/invite', 'invite')->middleware('auth')->name('group.invite'); // invite user to group
    Route::post('/group/{groupId}/promote/{userId}', 'promote')->middleware('auth')->name('group.promote'); // promote member to moderator
    Route::post('/group/{groupId}/demote/{userId}', 'demote')->middleware('auth')->name('group.demote'); // demote moderator to member
    Route::delete('/group/{groupId}/remove/{userId}', 'removeMember')->middleware('auth')->name('group.removeMember'); // remove member
    Route::put('/group/{id}/permissions', 'updatePermissions')->middleware('auth')->name('group.updatePermissions'); // update permissions
    Route::post('/group/{id}/transfer', 'transferOwnership')->middleware('auth')->name('group.transferOwnership'); // transfer ownership
    Route::delete('/group/{id}', 'destroy')->middleware('auth')->name('group.destroy'); // delete group
});

Route::controller(InboxMessageController::class)->group(function () {
    Route::get('/inbox', 'showInbox')->middleware('auth'); // show inbox page
    Route::post('/inbox/{id}/respond', 'respond')->middleware('auth'); // respond to inbox message
});
