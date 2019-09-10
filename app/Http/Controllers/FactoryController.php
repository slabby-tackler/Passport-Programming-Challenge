<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Factory;
use App\Child;

use App\Events\FactoryCreate;
use App\Events\FactoryUpdate;
use App\Events\FactoryDelete;

use App\Events\ChildGenerate;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Factory::with('children')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rangeMin' => 'required|integer|min:0|max:1000',
            'rangeMax' => 'required|integer|min:0|max:1000',
        ]);

        $factory = new Factory();
        $factory->name = $request->name;
        $factory->lower_bound = $request->rangeMin;
        $factory->upper_bound = $request->rangeMax;

        $factory->save();

        // this adds the children property before the factory is returned.
        $factory->children;

        broadcast(new FactoryCreate($factory))->toOthers();

        return $factory;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rangeMin' => 'required|integer|min:0|max:1000',
            'rangeMax' => 'required|integer|min:0|max:1000',
        ]);

        $factory = Factory::find($id);
        $factory->name = $request->name;
        $factory->lower_bound = $request->rangeMin;
        $factory->upper_bound = $request->rangeMax;

        $factory->save();

        broadcast(new FactoryUpdate($factory))->toOthers();

        return $factory;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $factory = Factory::find($id);
        $factory->delete();

        $factory->children()->delete();

        broadcast(new FactoryDelete($factory))->toOthers();

        return $factory;
    }

    public function generateChildren(Request $request, $id)
    {
        $request->validate([
            'numChildren' => 'required|integer|min:0|max:15'
        ]);

        $factory = Factory::find($id);

        // remove the existing children
        $factory->children()->delete();

        $childrenArray = [];
        for($i = 0; $i < $request->numChildren; $i++) {
            $child = new Child();
            $child->name = rand($factory->lower_bound, $factory->upper_bound);
            $child->factory_id = $id;
            $child->save();

            array_push($childrenArray, $child);
        }

        broadcast(new ChildGenerate($factory, $childrenArray))->toOthers();

        return $childrenArray;
    }
}
