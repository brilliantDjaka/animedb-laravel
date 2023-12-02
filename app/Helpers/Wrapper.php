<?php

namespace App\Helpers;

class Wrapper
{
    public static function pagination($data = [], $total = 0, $req = null): array
    {
        $meta = [];
        if ($req) {
            $meta['page'] = +$req['page'];
            $meta['size'] = +$req['size'];
        }
        $meta['totalData'] = $total;
        $meta['totalPage'] = ceil(
            $total / ($req['page'] ?? 1)
        );

        return [
            'data' => $data,
            'meta' => $meta,
        ];
    }
}
