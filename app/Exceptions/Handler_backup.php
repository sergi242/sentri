<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function render ($request, Throwable $exception)

    {
        if ($exception instanceof AuthorizationException)
        {
            if ($request->expectsJson()=== false){
                return redirect()->back()->with
                ('error',"Vous n'avez pas les accreditation necessaire");
                
            }
            
        }
        return parent::render($request,$exception);

    }
   public function register(): void
{
    $this->renderable(function (AuthorizationException $e, $request) {

        // 🔒 Requête AJAX / API
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'forbidden',
                'message' => "Vous n'avez pas les accréditations nécessaires."
            ], 403);
        }

        // 🔒 Navigation normale
        return redirect()
            ->back()
            ->with('forbidden', "Vous n'avez pas les accréditations nécessaires pour effectuer cette action.");
    });
}

}
