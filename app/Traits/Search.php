<?php

namespace App\Traits;

/**
 * App\Traits\Search
 *
 * @property array $dataFilter Search data filter
 */
trait Search
{
    /**
     * @param mixed $query
     * @param string $search
     * @param string $colum_name
     * @param array $array
     */
    public function scopeSearch($query, $search, $colum_name = 'name', $array = [])
    {
        if (! empty($search)) {
            $query->where($colum_name, 'LIKE', "%{$search}%");
            if (! empty($array)) {
                foreach ($array as $key => $value) {
                    $query->orWhere($key, 'LIKE', "%{$value}%");
                }
            }

            return $query;
        }
    }

    /**
     * @param mixed $query
     * @param array $array
     * @description In class property $dataFilter = ['status','type' => ['method' => 'where', 'operator' => '='],];
     */
    public function scopeFilter($query, array $array = null)
    {
        $arr = $array ?? property_exists($this, 'dataFilter') ? $this->dataFilter : [];
        if (! empty($arr)) {
            foreach ($arr as $key => $value) {
                $column = gettype($value) === 'string' ? $value : $key;
                $data = request($column, null);
                if (! empty($data)) {
                    if (gettype($value) === 'string') {
                        $query->where($value, '=', $data);
                        continue;
                    }
                    $value = (object) $value;
                    $query->{$value?->method ?? 'orWhere'}($key, $value->operator ?? '=', $data);
                }
            }
        }

        return $query;
    }
}
