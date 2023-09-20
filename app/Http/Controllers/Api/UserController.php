<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**1)L'utilisateur envoie une requête avec les données nécessaires 
 * (avatar, type, open_id, name, email, password) au point de terminaison correspondant.

2)Le code commence par valider ces données à l'aide de la classe Validator de Laravel.
Si la validation échoue, il renvoie une réponse JSON avec les erreurs de validation.

3)Si la validation réussit, il vérifie si un utilisateur avec le même type et 
open_id existe déjà dans la base de données.

4)Si l'utilisateur n'existe pas, il génère un token unique pour l'utilisateur, 
enregistre l'heure de création, crypte le mot de passe, 
insère l'utilisateur dans la base de données et lui attribue un jeton d'accès. Ensuite, 
il renvoie les informations de l'utilisateur créé en réponse.

5)Si l'utilisateur existe déjà, il génère un nouveau jeton d'accès pour cet utilisateur 
et met à jour le jeton d'accès dans la base de données. Ensuite, il renvoie les informations 
de l'utilisateur existant en réponse.

6)La fonction loginUser est similaire mais est utilisée pour authentifier un utilisateur existant 
en vérifiant les informations d'identification (email et mot de passe) 
et en renvoyant un jeton d'accès s'il est valide.
*/
class UserController extends Controller
{
    /**
     * create user
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {

        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'avatar' => 'required',
                    'type' => 'required',
                    'open_id' => 'required',
                    'name' => 'required',
                    'email' => 'required',
                    //'password' => 'required|min:6',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            //validated will have all user field values
            //we can save in the database
            // Obtenir les données validées après la validation

            $validated = $validateUser->validated();
            $map = []; // Créer un tableau de correspondance

            //email,phone,google,facebook,apple
            $map['type'] = $validated['type'];
            $map['open_id'] = $validated['open_id'];
            // Vérifier si un utilisateur avec le même 'type' et 'open_id' existe déjà

            $user = User::where($map)->first();
            //whether user has aleardy logged in or not
            //empty means soes not exist
            //then save the user in the database for the fisrt time

            if (empty($user->id)) {
                //this certain user has never been in our database
                //our job is to assing the user in the database 
                //this token is the user id
                $validated['token'] = md5(uniqid() . rand(10000, 99999));
                //user first time created
                $validated['created_at'] = Carbon::now();
                //encript password
                // $validated['password'] = Hash::make($validated['password']);
                //return the id if the row after saving
                $userID = User::insertGetId($validated);
                //user's all the information
                $userInfo = User::where('id', '=', $userID)->first();

                $accesToken = $userInfo->createToken(uniqid())->plainTextToken;

                $userInfo->access_token = $accesToken;

                User::where('id', '=', $userID)->update(['access_token' => $accesToken]);
                return response()->json([
                    'code' => 200,
                    'msg' => 'User Created Successfuly',
                    'data' => $userInfo
                ], 200);
            }
            $accesToken = $user->createToken(uniqid())->plainTextToken;
            $user->access_token = $accesToken;
            User::where('open_id', '=', $validated['open_id'])->update(['access_token' => $accesToken]);

            return response()->json([
                'code' => 200,
                'msg' => 'User logged in Successfuly',
                'data' => $user,
            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The user
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record'
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfuly',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}