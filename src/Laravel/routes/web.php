<?php
Route::get('/oauth/jwks', \OpenIDConnect\Laravel\JwksController::class."@jwks")->name('openid.jwks');
Route::get('/.well-known/openid-configuration', \OpenIDConnect\Laravel\DiscoveryController::class."@discovery")->name('openid.discovery');
