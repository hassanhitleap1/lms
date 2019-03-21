<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => ':attribute غير صالح',
    'after'                => 'يجب أن يكون تاريخ :attribute بعد :date',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'قد يحتوي :attribute على حروف فقط',
    'alpha_dash'           => 'قد يحتوي :attribute على حروف، وأرقام، وشرطات فقط',
    'alpha_num'            => 'قد يحتوي :attribute على حروف، وأرقام فقط',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'يجب أن يكون تاريخ :attribute قبل :date',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'يجب أن يكون :attribute بين :min و :max',
        'file'    => 'يجب أن يكون حجم :attribute بين :min و :max كيلوبايت',
        'string'  => 'يجب أن يكون عدد حروف :attribute بين :min و :max حرفًا',
        'array'   => 'يجب أن يكون عدد عناصر :attribute بين :min و :max',
    ],
    'boolean'              => 'القيمة في خانة :attribute يجب أن تكون صح أو خطأ',
    'confirmed'            => ':attribute غير متطابقة',
    'date'                 => 'تاريخ :attribute غير صالح',
    'date_format'          => ':attribute لا يطابق الصيغة :format',
    'different'            => 'يجب أن يكون :attribute والـ:other مختلفين',
    'digits'               => 'يجب أن يكون :attribute :digits خانات',
    'digits_between'       => 'يجب أن يكون عدد خانات :attribute بين :min و :max خانات',
    'dimensions'           => 'يحتوي :attribute على أبعاد غير صحيحة لصورة',
    'distinct'             => 'يحتوي :attribute على قيمة مكررة',
    'email'                => 'يجب أن يكون :attribute هو صيغة بريد إلكتروني صحيحة',
    'exists'               => ':attribute المحدد غير صالح',
    'file'                 => 'يجب أن يكون :attribute ملفًا',
    'filled'               => 'يجب أن تحتوي خانة :attribute على قيمة',
    'image'                => 'يجب أن يكون :attribute صورة',
    'in'                   => ':attribute المحدد غير صالح',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'يجب أن يكون :attribute عددًا صحيحًا',
    'ip'                   => 'يجب أن يكون :attribute عنوان IP صحيح',
    'ipv4'                 => 'يجب أن يكون :attribute عنوان IPv4 صحيح',
    'ipv6'                 => 'يجب أن يكون :attribute عنوان IPv6 صحيح',
    'json'                 => 'يجب أن يكون :attribute نص JSON صحيح',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'يجب أن يكون نوع :attribute هو :values',
    'mimetypes'            => 'يجب أن يكون نوع :attribute هو :values',
    'min'                  => [
        'numeric' => 'يجب أن يضم :attribute كحد أدنى :min',
        'file'    => 'يجب أن يكون حجم :attribute كحد أدنى :min كيلوبايت',
        'string'  => 'يجب أن يضم :attribute كحد أدنى :min أحرف',
        'array'   => 'يجب أن يضم :attribute كحد أدنى :min عناصر',
    ],
    'not_in'               => ':attribute المحدد غير صالح',
    'numeric'              => 'يجب أن يكون عدد عناصر :attribute رقمًا',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'صيغة :attribute غير صالحة',
    'required'             => 'خانة :attribute مطلوبة',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'يجب أن يتطابق كل من :attribute و :other',
    'size'                 => [
        'numeric' => 'حجم :attribute يجب أن يكون :size',
        'file'    => 'حجم :attribute يجب أن يكون :size كيلوبايت',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'يجب أن يكون :attribute نصًا',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'لم يتم تحميل :attribute بنجاح',
    'url'                  => 'صيغة :attribute غير صالحة',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
