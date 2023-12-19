<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

use RootInc\LaravelAzureMiddleware\Azure as BaseAzure;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

use App\Models\User;

class Azure extends BaseAzure
{
    protected function success(Request $request, $access_token, $refresh_token, $profile)
    {
        $graph = new Graph();
        $graph->setAccessToken($access_token);

        $graph_user = $graph->createRequest("GET", "/me")
                      ->setReturnType(Model\User::class)
                      ->execute();

        $email = $graph_user->getMail();

        // $user = User::query()->where('email', $email)->first();
        // if (is_null($user)) {
        //     abort(403, "Your email does't exist in our database");
        // }

        // if ($user->name !== $graph_user->getGivenName() . ' ' . $graph_user->getSurname()) {
        //     $user->update([
        //         'name' => $graph_user->getGivenName() . ' ' . $graph_user->getSurname(),
        //     ]);
        // }

        // auth()->login($user);

        return view('welcome', [
            'email' => $email,
        ]);

        // return parent::success($request, $access_token, $refresh_token, $profile);
    }

    public function azurelogout(Request $request)
    {
        $request->session()->pull('_rootinc_azure_access_token');
        $request->session()->pull('_rootinc_azure_refresh_token');

        auth()->logout();

        return redirect()->away($this->getLogoutUrl());
    }
}
