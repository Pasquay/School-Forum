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
 
Route::controller(UserController::class)->group(function() {
    Route::get('/', 'showLanding'); // load login page
    Route::post('/login', 'login'); // user login
    Route::post('/google-login', 'googleLogin'); // google login
    Route::post('/register', 'register'); // user register
    Route::post('/logout', 'logout')->middleware('auth'); // user logout
    Route::get('/user/{id}', 'loadUser')->middleware('auth'); // load user
    Route::get('/user/{id}/settings', 'loadSettings')->middleware('auth'); // load user settings
    Route::get('/user/{id}/overview', 'getUserOverview')->middleware('auth'); // load page 2+ user overview
    Route::get('/user/{id}/comments-and-replies', 'getUserCommentsAndReplies')->middleware('auth'); // load page 2+ user comments and replies
    Route::get('/user/{id}/deleted-overview', 'getUserDeletedOverview')->middleware('auth'); // load page 2+ deleted user overview
    Route::get('/user/{id}/deleted-comments-and-replies', 'getUserDeletedCommentsAndReplies')->middleware('auth'); // load page 2+ deleted user comments and replies
    
    // Password reset routes
    Route::post('/password/email', 'sendPasswordResetEmail')->name('password.email'); // send reset email
    Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset'); // show reset form
    Route::post('/password/reset', 'resetPassword')->name('password.update'); // update password
});

Route::controller(PostController::class)->group(function () {
    Route::get('/home', 'getLatest')->middleware('auth'); // load home page
    Route::get('/post/{id}', 'getPost')->middleware('auth'); // load post page
    Route::post('/create-post/{id}', 'create')->middleware('auth'); // create post
    Route::post('/pin-post-home/{id}', 'pinHomeToggle')->middleware('auth'); // pin post to home
    Route::post('/pin-post/{id}', 'pinToggle')->middleware('auth'); // pin post
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
    Route::get('/groups/{page}', 'showGroupsPaginated')->middleware('auth'); // show page 2+ groups page

    Route::post('/group/toggleStar/{id}', 'toggleStar')->middleware('auth'); // star/unstar a group
    Route::post('/group/toggleMute/{id}', 'toggleMute')->middleware('auth'); // mute/unmute a group
    Route::post('/group/{id}/join', 'joinGroup')->middleware('auth'); // join a group
    Route::post('/group/{id}/leave', 'leaveGroup')->middleware('auth'); // leave a group
    Route::get('/group/{id}/settings', 'showGroupSettings')->middleware('auth'); // manage a group/go to group settings
    Route::get('/group/{id}', 'showGroup')->middleware('auth'); // show a group's page
});
