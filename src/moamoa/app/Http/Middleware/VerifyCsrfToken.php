<?php
namespace App\Http\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];
    protected function inExceptArray($request)
    {
        // 디버그 모드일 때 모든 요청 허용
        if (env('APP_DEBUG')) {
            return true;
        }
        return parent::inExceptArray($request);
    }
}
