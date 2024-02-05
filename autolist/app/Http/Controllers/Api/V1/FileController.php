<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Bitrix24;

class FileController extends Controller
{
    const LIST_ID = 29;

    protected $b24Service;

    public function __construct(
        Bitrix24 $b24Service,
    ) {
        $this->b24Service = $b24Service;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, int $fieldId, int $fileId)
    {
        $result = [];
        $b24result = $this->b24Service->getApp()->call('lists.element.get.file.url',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => self::LIST_ID,
                'ELEMENT_ID'  => $id,
                'FIELD_ID' => $fieldId,
            ]);
        $files = $b24result->getResponseData()->getResult();

        foreach($files as $file) {
            $result[] = $file;
        }
        // error_log(var_export([__METHOD__, $result], true));
        //
        // $b24file = $this->b24Service->getApp()->call('disk.file.get',
        //     [
        //         'id' => 97, //$fileId,
        //     ]);
        // $fileResult = $b24file->getResponseData()->getResult();
        // error_log(var_export([__METHOD__, $fileResult], true));

        return $result;
    }

}
