<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;
use ArrayObject;

class OrganisasiCustomDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private readonly OpenApiFactoryInterface $decorated
    ) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['GetActiveKantorByKantorNameRequest'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'kantor_name' => [
                    'type' => 'string',
                    'example' => 'string',
                ],
            ],
        ]);

        $schemas['GetActiveKantorByKantorNameResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'kantor_count' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'kantors' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $activeKantorByKantorNameItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getActiveKantorByKantorName',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Get List of Active Kantor',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveKantorByKantorNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of Active Kantor from Kantor Name',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the kantor name. Minimum consist of 3 character, case insensitive.',
                    true
                )]
            ),
        );

        $allActiveKantorItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getAllActiveKantor',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Get List of All Active Kantor',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveKantorByKantorNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of All Active Kantor',
            ),
        );

        $schemas['GetActiveUnitByUnitNameRequest'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'unit_name' => [
                    'type' => 'string',
                    'example' => 'string',
                ],
            ],
        ]);

        $schemas['GetActiveUnitByUnitNameResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'unit_count' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'units' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $activeUnitByUnitNameItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getActiveUnitByUnitName',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Get List of Active Unit',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveUnitByUnitNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of Active Unit from Unit Name',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the unit name. Minimum consist of 3 character, case insensitive.',
                    true
                )]
            ),
        );

        $allActiveUnitItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getAllActiveUnit',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Get List of All Active Unit',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveUnitByUnitNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of All Active Unit',
            ),
        );

        $schemas['GetActiveJenisKantorByJenisKantorNameRequest'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'jenis_kantor_name' => [
                    'type' => 'string',
                    'example' => 'string',
                ],
            ],
        ]);

        $schemas['GetActiveJenisKantorByJenisKantorNameResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'jenis_kantor_count' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'jenis_kantors' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $activeJenisKantorByJenisKantorNameItem = new Model\PathItem(
            ref: 'JenisKantor',
            get: new Model\Operation(
                operationId: 'getActiveJenisKantorByJenisKantorName',
                tags: ['JenisKantor'],
                responses: [
                    '200' => [
                        'description' => 'Get List of Active Jenis Kantor',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveJenisKantorByJenisKantorNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of Active Jenis Kantor from Jenis Kantor Name',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the jenis kantor name. Minimum consist of 3 character, case insensitive.',
                    true
                )]
            ),
        );

        $allActiveJenisKantorItem = new Model\PathItem(
            ref: 'JenisKantor',
            get: new Model\Operation(
                operationId: 'getAllActiveJenisKantor',
                tags: ['JenisKantor'],
                responses: [
                    '200' => [
                        'description' => 'Get List of All Active Jenis Kantor',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveJenisKantorByJenisKantorNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of All Active Jenis Kantor',
            ),
        );

        $schemas['GetActiveJabatanByJabatanNameRequest'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'jabatan_name' => [
                    'type' => 'string',
                    'example' => 'string',
                ],
            ],
        ]);

        $schemas['GetActiveJabatanByJabatanNameResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'jabatan_count' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'jabatans' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $activeJabatanByJabatanNameItem = new Model\PathItem(
            ref: 'Jabatan',
            get: new Model\Operation(
                operationId: 'getActiveJabatanByJabatanName',
                tags: ['Jabatan'],
                responses: [
                    '200' => [
                        'description' => 'Get List of Active Jabatan',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveJabatanByJabatanNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of Active Jabatan from Jabatan Name',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the jabatan name. Minimum consist of 3 character, case insensitive.',
                    true
                )]
            ),
        );

        $allActiveJabatanItem = new Model\PathItem(
            ref: 'Jabatan',
            get: new Model\Operation(
                operationId: 'getAllActiveJabatan',
                tags: ['Jabatan'],
                responses: [
                    '200' => [
                        'description' => 'Get List of All Active Jabatan',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetActiveJabatanByJabatanNameResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get list of All Active Jabatan',
            ),
        );

        $schemas['postKepalaKantorFromKantorIdRequest'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'kantorId' => [
                    'type' => 'string',
                    'example' => "uuid",
                ],
            ],
        ]);

        $schemas['postKepalaKantorFromKantorIdResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'kantor' => [
                    'type' => 'array',
                    'example' => [],
                    'readOnly' => true,
                ],
            ],
        ]);

        $fetchKepalaKantorFromKantorIdItem = new Model\PathItem(
            ref: 'Kantor',
            post: new Model\Operation(
                operationId: 'postKepalaKantorFromKantorIdItem',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Successful response',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/postKepalaKantorFromKantorIdResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get Kepala Kantor data from Kantor Id',
                requestBody: new Model\RequestBody(
                    description: 'Get Kepala kantor data from kantor uuid',
                    content: new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/postKepalaKantorFromKantorIdRequest',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $schemas['GetParentKantorResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'code' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'id' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'nama' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'level' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'parent' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $parentKantorByKantorIdItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getParentKantorByKantorId',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Parent Kantor Data From Kantor Id',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetParentKantorResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get parent kantor information from kantor id',
                parameters: [new Model\Parameter(
                    'id',
                    'path',
                    'Please provide the kantor id (uuid).',
                    true
                )]
            ),
        );

        $parentKantorByKantorExactNameItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getParentKantorByKantorExactName',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Parent Kantor Data From Kantor Exact Name',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetParentKantorResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get parent kantor information from kantor name (only for active kantor)',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the kantor name.',
                    true
                )]
            ),
        );

        $parentKantorByKantorLegacyKodeItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getParentKantorByKantorLegacyKode',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Parent Kantor Data From Kantor Legacy Kode',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetParentKantorResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get parent kantor information from kantor legacy kode',
                parameters: [new Model\Parameter(
                    'legacyKode',
                    'path',
                    'Please provide the kantor legacy kode.',
                    true
                )]
            ),
        );

        $schemas['GetChildKantorsResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'code' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'id' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'nama' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'level' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'childs' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $childKantorsByKantorIdItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getChildKantorsByKantorId',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Child Kantors Data From Kantor Id',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetChildKantorsResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get child kantors information from kantor id',
                parameters: [new Model\Parameter(
                    'id',
                    'path',
                    'Please provide the kantor id (uuid).',
                    true
                )]
            ),
        );

        $childKantorsByKantorExactNameItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getChildKantorsByKantorExactName',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Child Kantors Data From Kantor Exact Name',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetChildKantorsResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get child kantors information from kantor name (only for active kantor)',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the kantor name.',
                    true
                )]
            ),
        );

        $childKantorsByKantorLegacyKodeItem = new Model\PathItem(
            ref: 'Kantor',
            get: new Model\Operation(
                operationId: 'getChildKantorsByKantorLegacyKode',
                tags: ['Kantor'],
                responses: [
                    '200' => [
                        'description' => 'Child Kantors Data From Kantor Legacy Kode',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetChildKantorsResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get child kantors information from kantor legacy kode',
                parameters: [new Model\Parameter(
                    'legacyKode',
                    'path',
                    'Please provide the kantor legacy kode.',
                    true
                )]
            ),
        );

        $schemas['GetParentUnitResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'code' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'id' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'nama' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'level' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'parent' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $parentUnitByUnitIdItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getParentUnitByUnitId',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Parent Unit Data From Unit Id',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetParentUnitResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get parent unit information from unit id',
                parameters: [new Model\Parameter(
                    'id',
                    'path',
                    'Please provide the unit id (uuid).',
                    true
                )]
            ),
        );

        $parentUnitByUnitExactNameItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getParentUnitByUnitExactName',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Parent Unit Data From Unit Exact Name',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetParentUnitResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get parent unit information from unit name (only for active unit)',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the unit name.',
                    true
                )]
            ),
        );

        $parentUnitByUnitLegacyKodeItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getParentUnitByUnitLegacyKode',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Parent Unit Data From Unit Legacy Kode',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetParentUnitResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get parent unit information from unit legacy kode',
                parameters: [new Model\Parameter(
                    'legacyKode',
                    'path',
                    'Please provide the unit legacy kode.',
                    true
                )]
            ),
        );

        $schemas['GetChildUnitsResponse'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'code' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'id' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'nama' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'level' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'childs' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $childUnitsByUnitIdItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getChildUnitsByUnitId',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Child Units Data From Unit Id',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetChildUnitsResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get child units information from unit id',
                parameters: [new Model\Parameter(
                    'id',
                    'path',
                    'Please provide the unit id (uuid).',
                    true
                )]
            ),
        );

        $childUnitsByUnitExactNameItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getChildUnitsByUnitExactName',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Child Units Data From Unit Exact Name',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetChildUnitsResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get child units information from unit name (only for active unit)',
                parameters: [new Model\Parameter(
                    'name',
                    'path',
                    'Please provide the unit name.',
                    true
                )]
            ),
        );

        $childUnitsByUnitLegacyKodeItem = new Model\PathItem(
            ref: 'Unit',
            get: new Model\Operation(
                operationId: 'getChildUnitsByUnitLegacyKode',
                tags: ['Unit'],
                responses: [
                    '200' => [
                        'description' => 'Child Units Data From Unit Legacy Kode',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GetChildUnitsResponse',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get child units information from unit legacy kode',
                parameters: [new Model\Parameter(
                    'legacyKode',
                    'path',
                    'Please provide the unit legacy kode.',
                    true
                )]
            ),
        );

        $openApi->getPaths()->addPath('/api/kantors/active/show_all', $allActiveKantorItem);
        $openApi->getPaths()->addPath('/api/kantors/active/{name}', $activeKantorByKantorNameItem);
        $openApi->getPaths()->addPath('/api/kantors/kepala_kantor', $fetchKepalaKantorFromKantorIdItem);
        $openApi->getPaths()->addPath('/api/units/active/show_all', $allActiveUnitItem);
        $openApi->getPaths()->addPath('/api/units/active/{name}', $activeUnitByUnitNameItem);
        $openApi->getPaths()->addPath('/api/jenis_kantors/active/show_all', $allActiveJenisKantorItem);
        $openApi->getPaths()->addPath('/api/jenis_kantors/active/{name}', $activeJenisKantorByJenisKantorNameItem);
        $openApi->getPaths()->addPath('/api/jabatans/active/show_all', $allActiveJabatanItem);
        $openApi->getPaths()->addPath('/api/jabatans/active/{name}', $activeJabatanByJabatanNameItem);
        $openApi->getPaths()->addPath('/api/kantors/{id}/parent', $parentKantorByKantorIdItem);
        $openApi->getPaths()->addPath('/api/kantors/find_parent/by_id/{id}', $parentKantorByKantorIdItem);
        $openApi->getPaths()->addPath('/api/kantors/find_parent/by_exact_name/{name}', $parentKantorByKantorExactNameItem);
        $openApi->getPaths()->addPath('/api/kantors/find_parent/by_legacy_kode/{legacyKode}', $parentKantorByKantorLegacyKodeItem);
        $openApi->getPaths()->addPath('/api/kantors/{id}/childs', $childKantorsByKantorIdItem);
        $openApi->getPaths()->addPath('/api/kantors/find_childs/by_id/{id}', $childKantorsByKantorIdItem);
        $openApi->getPaths()->addPath('/api/kantors/find_childs/by_exact_name/{name}', $childKantorsByKantorExactNameItem);
        $openApi->getPaths()->addPath('/api/kantors/find_childs/by_legacy_kode/{legacyKode}', $childKantorsByKantorLegacyKodeItem);
        $openApi->getPaths()->addPath('/api/units/{id}/parent', $parentUnitByUnitIdItem);
        $openApi->getPaths()->addPath('/api/units/find_parent/by_id/{id}', $parentUnitByUnitIdItem);
        $openApi->getPaths()->addPath('/api/units/find_parent/by_exact_name/{name}', $parentUnitByUnitExactNameItem);
        $openApi->getPaths()->addPath('/api/units/find_parent/by_legacy_kode/{legacyKode}', $parentUnitByUnitLegacyKodeItem);
        $openApi->getPaths()->addPath('/api/units/{id}/childs', $childUnitsByUnitIdItem);
        $openApi->getPaths()->addPath('/api/units/find_childs/by_id/{id}', $childUnitsByUnitIdItem);
        $openApi->getPaths()->addPath('/api/units/find_childs/by_exact_name/{name}', $childUnitsByUnitExactNameItem);
        $openApi->getPaths()->addPath('/api/units/find_childs/by_legacy_kode/{legacyKode}', $childUnitsByUnitLegacyKodeItem);

        return $openApi;
    }
}
