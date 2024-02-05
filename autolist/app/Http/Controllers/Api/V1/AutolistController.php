<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

use App\Service\Bitrix24;

class AutolistController extends Controller
{
    const LIST_ID = 29;
    const DISK_FOLDER_ID = 95;

    private $b24Service;

    private $cleanUpFiles = [];

    public function __construct(
        Bitrix24 $b24Service
    ) {
        $this->b24Service = $b24Service;
    }

    private function b24() {
        return $this->b24Service->getApp();
    }

    private function getFields(Request $request) {
        $result = [
            'NAME' => $request->input('fields.NAME'),
		    'PROPERTY_107' => $request->input('fields.PROPERTY_107'),
            'PROPERTY_109' => $request->input('fields.PROPERTY_109')
        ];

        if ($request->hasFile('fields.PROPERTY_109')) {
            $file = $this->b24Service->uploadFile($request->file('fields.PROPERTY_109'), self::DISK_FOLDER_ID);
            if (!empty($file['FILE_ID'])) {
                $result['PROPERTY_109'] = $file['FILE_ID'];
                $this->cleanUpFiles[] = $file['ID'];
            }
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $b24result = $this->b24()->call('lists.element.get',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => self::LIST_ID,
                'ELEMENT_ORDER'  => ['NAME' => 'ASC'],
            ]);
        $result = $b24result->getResponseData()->getResult();
        return $result;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $uuid = (string) Str::uuid();
        $fields = $this->getFields($request);

        $b24result = $this->b24()->call('lists.element.add',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => self::LIST_ID,
                'ELEMENT_CODE'  => $uuid,
                'FIELDS' => $fields
            ]);
        $result = $b24result->getResponseData()->getResult();
        return $result;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $b24result = $this->b24()->call('lists.element.get',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => self::LIST_ID,
                'ELEMENT_ORDER'  => ['NAME' => 'ASC'],
                'FILTER' => [ '=ID' => $id]
            ]);
        $result = $b24result->getResponseData()->getResult();
        return $result;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $element = $this->show($id);
        if (empty($element)) {
            throw new \Exception("Element $id not found");
        }
        $oldFields = $element[0];

        $fields = $this->getFields($request);
        foreach ( $fields as $key => $value) {
            $oldFields[$key] = $value;
        }

        // if (!empty($fields['PROPERTY_109'])) {
        //     $newImage = $fields['PROPERTY_109'];
        //     $currentImages = $element[0]['PROPERTY_109'] ?? [];
        //     if (!in_array($currentImages, $newImage)) {
        //     }
        // }
        $b24result = $this->b24()->call('lists.element.update',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => self::LIST_ID,
                'ELEMENT_ID' => $id,
                'FIELDS' => $oldFields
            ]);
        $result = $b24result->getResponseData()->getResult();
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $b24result = $this->b24()->call('lists.element.delete',
            [
                'IBLOCK_TYPE_ID' => 'lists',
                'IBLOCK_ID' => self::LIST_ID,
                'ELEMENT_ID' => $id,
            ]);
        $result = $b24result->getResponseData()->getResult();
        return $result;
    }

    public function __destruct()
    {
        foreach($this->cleanUpFiles as $fileId) {
            $b24result = $this->b24()->call('disk.file.delete',
                [
                    'id' => $fileId,
                ]);
            //$uploadResult = $b24result->getResponseData()->getResult();
        }
    }
}
