<?php

namespace GindowCloud\Traits;

use Illuminate\Http\Request;

trait ResourceTrait
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = $this->repository::index($request->all());
        return json_encode($data);
    }

    public function show($id)
    {
        $data = $this->repository::show($id);
        return json_encode($data);
    }

    public function store(Request $request)
    {
        $data = $this->repository::store($request->all());
        return json_encode($data);
    }

    public function update($id, Request $request)
    {
        $data = $this->repository::update($id, $request->all());
        return json_encode($data);
    }

    public function destroy($id)
    {
        $data = $this->repository::destroy($id);
        return json_encode($data);
    }
}
