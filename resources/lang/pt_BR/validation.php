<?php

return [
    'accepted' => 'O campo :attribute deve ser aceito.',
    'active_url' => 'O campo :attribute não é uma URL válida.',
    'after' => 'O campo :attribute deve ser uma data posterior a :date.',
    'alpha' => 'O campo :attribute deve conter somente letras.',
    'alpha_num' => 'O campo :attribute deve conter somente letras e números.',
    'array' => 'O campo :attribute deve ser um array.',
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação do campo :attribute não confere.',
    'date' => 'O campo :attribute não é uma data válida.',
    'date_format' => 'O campo :attribute não confere com o formato :format.',
    'different' => 'Os campos :attribute e :other devem ser diferentes.',
    'digits' => 'O campo :attribute deve ter :digits dígitos.',
    'digits_between' => 'O campo :attribute deve ter entre :min e :max dígitos.',
    'distinct' => 'O campo :attribute possui um valor duplicado.',
    'email' => 'Informe um email válido.',
    'exists' => 'O valor selecionado em :attribute é inválido.',
    'in' => 'O valor selecionado em :attribute é inválido.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'max' => [
        'string' => 'O campo :attribute não pode ser maior que :max caracteres.',
    ],
    'min' => [
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
    ],
    'not_regex' => 'O formato do campo :attribute é inválido.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'present' => 'O campo :attribute deve estar presente.',
    'regex' => 'O formato do campo :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'same' => 'Os campos :attribute e :other devem ser iguais.',
    'size' => [
        'string' => 'O campo :attribute deve ter exatamente :size caracteres.',
    ],
    'string' => 'O campo :attribute deve ser um texto.',
    'unique' => 'O campo :attribute já está em uso.',
    'url' => 'O formato do campo :attribute é inválido.',

    'custom' => [
        'cpf' => [
            'required' => 'Informe o CPF.',
            'size' => 'O CPF deve conter 11 dígitos.',
            'unique' => 'Este CPF já está cadastrado.',
        ],
        'cnpj' => [
            'required' => 'Informe o CNPJ.',
            'size' => 'O CNPJ deve conter 14 dígitos.',
            'unique' => 'Este CNPJ já está cadastrado.',
        ],
        'phone' => [
            'regex' => 'Informe um telefone válido com DDD (10 ou 11 dígitos).',
        ],
        'email' => [
            'email' => 'Informe um email válido.',
            'unique' => 'Este email já está cadastrado.',
        ],
    ],

    'attributes' => [
        'person_type' => 'tipo de pessoa',
        'name' => 'nome',
        'cpf' => 'CPF',
        'cnpj' => 'CNPJ',
        'email' => 'email',
        'phone' => 'telefone',
        'cep' => 'CEP',
        'street' => 'logradouro',
        'number' => 'número',
        'complement' => 'complemento',
        'district' => 'bairro',
        'city' => 'cidade',
        'state' => 'UF',
        'address' => 'endereço',
    ],
];



