<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * iniciar sesión y generar token
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials))
        {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], Response::HTTP_OK);
        }
        else
        {
            return response([
                "message" => "Credenciales incorrectas"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * cerrar sesión y borrar token
     */
    public function logout(User $user)
    {       
        
        if($user->tokens()->delete())
        {
            return response()->json([
                "message" => "Sesión cerrada exitosamente",
            ], Response::HTTP_OK);
        }
        else
        {
            return response([
                "message" => "Un error ha ocurrido, intente de nuevo"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }


}
