<?php

namespace GindowCloud\Kernel\Traits;

trait ResourceTrait
{
    public function index($para = [])
    {
        return $this->getJson($this->resource, $para);
    }

    public function show($id)
    {
        return $this->getJson($this->resource . '/' . $id);
    }

    public function store($para)
    {
        return $this->postJson($this->resource, $para);
    }

    public function update($id, $para)
    {
        return $this->patchJson($this->resource . '/' . $id, $para);
    }

    public function destroy($id)
    {
        return $this->deleteJson($this->resource . '/' . $id);
    }
}
