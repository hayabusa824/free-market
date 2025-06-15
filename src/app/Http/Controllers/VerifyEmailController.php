<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/mypage/profile')->with('success', 'メール認証が完了しました。');
    }
}
