<?php

namespace GindowCloud\Kernel\Traits;

trait ResourceTrait
{
    public function index($para = [])
    {
        return $this->httpGet($this->resource, $para);
    }

    public function show($id)
    {
        return $this->httpGet($this->resource . '/' . $id);
    }

    public function store($para)
    {
        return $this->httpPost($this->resource, $para);
    }

    public function update($id, $para)
    {
        return $this->httpPatch($this->resource . '/' . $id, $para);
    }

    public function destroy($id)
    {
        return $this->httpDelete($this->resource . '/' . $id);
    }
}
