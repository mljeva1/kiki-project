<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class TestingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function createNoProduct()
    {
        $user = new User();
        $user->first_name = 'Kiki';
        $user->last_name = 'Admin';
        $user->email = 'kiki@admin.hr';
        $user->password = Hash::make('admin123');
        $user->role = 'admin';
        $user->is_deleted = false;

        $user->save();

        // Pogledaj rezultat (UUID i sve ostalo)
        dd($user);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
}
