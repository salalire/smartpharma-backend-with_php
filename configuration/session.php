<?php
// Allow session cookie to be sent cross-origin (SameSite=None requires Secure,
// but for localhost we use Lax which works when origin and target are both localhost)
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_path', '/');
