<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReplyVoteController;
use App\Http\Controllers\CommentVoteController;

Route::controller(UserController::class)->group(function() {
    Route::get('/', 'showLogin'); // load login page
    Route::post('/register', 'register'); // user register
    Route::post('/login', 'login'); // user login
    Route::post('/logout', 'logout')->middleware('auth'); // user logout
    Route::get('/user/{id}', 'loadUser')->middleware('auth'); // load user
    Route::get('/user/{id}/overview', 'getUserOverview')->middleware('auth'); // load page 2+ user overview
});

Route::controller(PostController::class)->group(function() {
    Route::get('/home', 'getLatest')->middleware('auth'); // load home page
    Route::get('/post/{id}', 'getPost')->middleware('auth'); // load post page
    Route::post('/create-post', 'create')->middleware('auth'); // create post
    Route::post('/edit-post/{id}', 'edit')->middleware('auth'); // edit post
    Route::post('/delete-post/{id}', 'delete')->middleware('auth'); // delete post
    Route::get('/user/{id}/posts', 'getUserPost')->middleware('auth'); // load page 2+ user posts
});

Route::controller(VoteController::class)->group(function() {
    // Home Posts
    Route::post('/home/upvote/{id}', 'togglePostUpvote')->middleware('auth'); // upvote post
    Route::post('/home/downvote/{id}', 'togglePostDownvote')->middleware('auth'); // downvote post
    // Post Pages
    Route::post('/post/upvote/{id}', 'togglePostUpvote')->middleware('auth'); // upvote post
    Route::post('/post/downvote/{id}', 'togglePostDownvote')->middleware('auth'); // downvote post
});

Route::controller(CommentController::class)->group(function() {
    Route::post('/post/{postId}/create-comment', 'create')->middleware('auth'); // create comment
    Route::post('/post/{postId}/edit-comment/{commentId}', 'edit')->middleware('auth'); // edit comment
    Route::post('/post/{postId}/delete-comment/{commentId}', 'delete')->middleware('auth'); // delete comment
    Route::get('/user/{id}/comments', 'getUserComment')->middleware('auth'); // load page 2+ user comments
});

Route::controller(CommentVoteController::class)->group(function() {
    Route::post('/comment/upvote/{id}', 'toggleCommentUpvote')->middleware('auth'); // upvote comment
    Route::post('/comment/downvote/{id}', 'toggleCommentDownvote')->middleware('auth'); // downvote comment
});

Route::controller(ReplyController::class)->group(function() {
    Route::get('/comment/{id}/replies', 'getReplies')->middleware('auth'); // load replies
    Route::post('/post/{postId}/comment/{commentId}/create-reply', 'create')->middleware('auth'); // create reply
    Route::post('/post/{postId}/edit-reply/{replyId}', 'edit')->middleware('auth'); // edit reply
    Route::post('/post/{postId}/delete-reply/{replyId}', 'delete')->middleware('auth'); // delete reply
});

Route::controller(ReplyVoteController::class)->group(function() {
    Route::post('/reply/upvote/{id}', 'toggleReplyUpvote')->middleware('auth'); // upvote comment
    Route::post('/reply/downvote/{id}', 'toggleReplyDownvote')->middleware('auth'); // downvote comment
});