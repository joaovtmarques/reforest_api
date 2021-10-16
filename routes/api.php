<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GreenPointController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\MissionController;

/* feito */ Route::get('/ping', function() {
    return ['pong'=>true];
});

/* feito */ Route::get('/401', [AuthController::class, 'unauthorized'])->name('login'); // rota de não autorizado

/* feito */ Route::post('/auth/login', [AuthController::class, 'login']); // rota de login 
/* feito */ Route::post('/auth/logout', [AuthController::class, 'logout']); // rota de logout
/* feito */ Route::post('/auth/refresh', [AuthController::class, 'refresh']); // rota de atualização
/* feito */ Route::post('/user', [AuthController::class, 'create']); // rota de cadastro

/* feito */ Route::get('/user', [UserController::class, 'read']); // rota de ver usuário
/* feito */ Route::put('/user', [UserController::class, 'update']); // rota de editar usuário

/* feito */ Route::get('/user/people', [UserController::class, 'getPeople']); // rota de ver pessoas que segue
/* feito */ Route::post('/user/people', [UserController::class, 'togglePeople']); // rota de seguir uma nova pessoa

/* feito */ Route::get('/user/favorites', [UserController::class, 'getFavorites']); // rota de ver pv's favoritos
/* feito */ Route::post('/user/favorite', [UserController::class, 'toggleFavorite']); // rota de adicionar pv's favoritos

/* feito */ Route::get('/user/posts', [UserController::class, 'getPosts']); // rota de ver posts do usuário
/* feito */ Route::post('/user/posts', [UserController::class, 'addPost']); // rota de criar um novo post

/* feito */ Route::get('/user/exchanges', [UserController::class, 'getExchanges']); // rota de listar trocas feitas pelo usuário

Route::get('/people', [PeopleController::class, 'getPeople']); // rota para listar as informações de uma perfil

/* feito */ Route::get('/posts', [PostController::class, 'getAllPosts']); // rota de ver todos os posts (tela inicial)
/* feito */ Route::get('/post/{id}', [PostController::class, 'getOnePost']); // rota de listar um post específico
/* feito */ Route::get('/post/{id}/comments', [PostController::class, 'getComments']); // rota de listar os comentários de um post específico
/* feito */ Route::post('/post/{id}/comments', [PostController::class, 'addComment']); // rota de adcionar um comentário em um post específico
/* feito */ Route::get('/post/{id}/likes', [PostController::class, 'getLikes']); // rota de listar os likes de um post específico
/* feito */ Route::post('/post/like', [PostController::class, 'toggleLike']); // rota de adicionar um like em um post específico

/* feito */ Route::get('/greenpoints', [GreenPointController::class, 'list']); // rota de listar pv's
/* feito */ Route::get('/greenpoints/{id}', [GreenPointController::class, 'one']); // rota de listar um pv específico
/* feito */ Route::post('/greenpoints', [GreenPointController::class, 'addGreenPoint']); // rota de criar um pv

/* feito */ Route::get('/user/greencredit', [UserController::class, 'getGreenCredit']); // rota de ver cv's

/* feito */ Route::get('/missions', [MissionController::class, 'getMissions']); // rota de ver missões
/* feito */ Route::post('/mission/{id}', [MissionController::class, 'completeMission']); // rota de completar missão
/* feito */ Route::post('/mission', [MissionController::class, 'addMission']); // rota de adicionar uma missão

/* feito (falta permissão */ Route::post('/awards', [AwardController::class, 'addAwards']); // rota de adcionar um prêmio
/* feito */ Route::get('/awards', [AwardController::class, 'getAwards']); // rota de listar prêmios
/* feito */ Route::get('/awards/{id}', [AwardController::class, 'getAward']); // rota de listar um prêmio específico
/* feito */ Route::post('/awards/{id}/exchange', [AwardController::class, 'exchangeAward']); // rota de trocar um prêmio específico

/* feito */ Route::post('/search', [GreenPointController::class, 'search']); // rota de buscar um pv
