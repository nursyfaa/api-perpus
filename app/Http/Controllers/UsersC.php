<?php

namespace App\Http\Controllers;

use App\Models\UsersM;
use Illuminate\Http\Request;
use App\Http\Resources\UsersR;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UsersC extends Controller
{
    public function index()
    {
        $users =UsersM::latest()->paginate(5);

        return new UsersR(true, 'List Data User', $users);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all() , [
            'username'             => 'required',
            'password'               => 'required',
            'nama_user'               => 'required',
            'role'               => 'required',
            'no_hp'               => 'required'
        ]); 

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $users = UsersM::create([
            'username'         => $request->username,
            'password'                 => Hash::make($request->password),
            'nama_user'                 => $request->nama_user,
            'role'                 => $request->role,
            'no_hp'                 => $request->no_hp,
        ]);

        return new UsersR(true, 'Data User Berhasil Ditambahkan', $users);
    }

    public function show(UsersM $users){
        return new UsersR(true, 'Data User Ditemukan', $users);
    }

    public function update(Request $request, UsersM $users){
        $validator = Validator::make($request->all() , [
            'username'             => 'required',
            'password'               => 'required',
            'nama_user'               => 'required',
            'role'               => 'required',
            'no_hp'               => 'required'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }


            Storage::delete('public/users/'.$users->username);

            $users->update([
                'username'         => $request->username,
                'password'                 => Hash::make($request->password),
                'nama_user'                 => $request->nama_user,
                'role'                 => $request->role,
                'no_hp'                 => $request->no_hp,
            ]);

        return new UsersR(true, 'Data user Berhasil Diubah', $users);

    }

    public function destroy(UsersM $users){
        Storage::delete('public/users/'.$users->username);

        $users->delete();

        return new UsersR(true, 'Data User Berhasil Dihapus', null);
    }
}