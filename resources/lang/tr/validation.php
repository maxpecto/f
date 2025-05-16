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

    'accepted'             => ':attribute kabul edilmelidir.',
    'active_url'           => ':attribute geçerli bir URL olmalıdır.',
    'after'                => ':attribute değeri :date tarihinden sonra olmalıdır.',
    'after_or_equal'       => ':attribute değeri :date tarihinden sonra veya eşit olmalıdır.',
    'alpha'                => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash'           => ':attribute sadece harfler, sayılar ve tirelerden oluşmalıdır.',
    'alpha_num'            => ':attribute sadece harfler ve sayılar içermelidir.',
    'array'                => ':attribute dizi olmalıdır.',
    'before'               => ':attribute değeri :date tarihinden önce olmalıdır.',
    'before_or_equal'      => ':attribute değeri :date tarihinden önce veya eşit olmalıdır.',
    'between'              => [
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'file'    => ':attribute :min - :max kilobayt arasında olmalıdır.',
        'string'  => ':attribute :min - :max karakter arasında olmalıdır.',
        'array'   => ':attribute :min - :max arasında öğe içermelidir.',
    ],
    'boolean'              => ':attribute sadece doğru veya yanlış olmalıdır.',
    'confirmed'            => ':attribute tekrarı eşleşmiyor.',
    'date'                 => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals'          => ':attribute değeri :date tarihine eşit olmalıdır.',
    'date_format'          => ':attribute :format biçimiyle eşleşmiyor.',
    'different'            => ':attribute ile :other birbirinden farklı olmalıdır.',
    'digits'               => ':attribute :digits haneli olmalıdır.',
    'digits_between'       => ':attribute :min ile :max arasında haneli olmalıdır.',
    'dimensions'           => ':attribute görsel ölçüleri geçersiz.',
    'distinct'             => ':attribute alanı yinelenen bir değere sahip.',
    'email'                => ':attribute biçimi geçersiz.',
    'ends_with'            => ':attribute şunlardan biriyle bitmelidir: :values.',
    'exists'               => 'Seçili :attribute geçersiz.',
    'file'                 => ':attribute dosya olmalıdır.',
    'filled'               => ':attribute alanının doldurulması zorunludur.',
    'gt'                   => [
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'file'    => ':attribute, :value kilobayttan büyük olmalıdır.',
        'string'  => ':attribute, :value karakterden uzun olmalıdır.',
        'array'   => ':attribute, :value taneden fazla olmalıdır.',
    ],
    'gte'                  => [
        'numeric' => ':attribute, :value kadar veya daha büyük olmalıdır.',
        'file'    => ':attribute, :value kilobayt kadar veya daha büyük olmalıdır.',
        'string'  => ':attribute, :value karakter kadar veya daha uzun olmalıdır.',
        'array'   => ':attribute, :value tane veya daha fazla olmalıdır.',
    ],
    'image'                => ':attribute alanı resim dosyası olmalıdır.',
    'in'                   => 'Seçili :attribute geçersiz.',
    'in_array'             => ':attribute alanı :other içinde mevcut değil.',
    'integer'              => ':attribute tamsayı olmalıdır.',
    'ip'                   => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4'                 => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'                 => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json'                 => ':attribute geçerli bir JSON değişkeni olmalıdır.',
    'lt'                   => [
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'file'    => ':attribute, :value kilobayttan küçük olmalıdır.',
        'string'  => ':attribute, :value karakterden kısa olmalıdır.',
        'array'   => ':attribute, :value taneden az olmalıdır.',
    ],
    'lte'                  => [
        'numeric' => ':attribute, :value kadar veya daha küçük olmalıdır.',
        'file'    => ':attribute, :value kilobayt kadar veya daha küçük olmalıdır.',
        'string'  => ':attribute, :value karakter kadar veya daha kısa olmalıdır.',
        'array'   => ':attribute, :value tane veya daha az olmalıdır.',
    ],
    'max'                  => [
        'numeric' => ':attribute değeri :max değerinden büyük olmamalıdır.',
        'file'    => ':attribute boyutu :max kilobayttan büyük olmamalıdır.',
        'string'  => ':attribute uzunluğu :max karakterden büyük olmamalıdır.',
        'array'   => ':attribute en fazla :max öğe içerebilir.',
    ],
    'mimes'                => ':attribute dosya biçimi :values olmalıdır.',
    'mimetypes'            => ':attribute dosya biçimi :values olmalıdır.',
    'min'                  => [
        'numeric' => ':attribute değeri :min değerinden küçük olmamalıdır.',
        'file'    => ':attribute boyutu :min kilobayttan küçük olmamalıdır.',
        'string'  => ':attribute uzunluğu :min karakterden küçük olmamalıdır.',
        'array'   => ':attribute en az :min öğe içermelidir.',
    ],
    'multiple_of'          => ':attribute, :value değerinin katları olmalıdır.',
    'not_in'               => 'Seçili :attribute geçersiz.',
    'not_regex'            => ':attribute biçimi geçersiz.',
    'numeric'              => ':attribute sayı olmalıdır.',
    'password'             => 'Parola geçersiz.',
    'present'              => ':attribute alanı mevcut olmalıdır.',
    'regex'                => ':attribute biçimi geçersiz.',
    'required'             => ':attribute alanı gereklidir.',
    'required_if'          => ':attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless'      => ':attribute alanı, :other alanı :value değerlerinden birine sahip olmadığında zorunludur.',
    'required_with'        => ':attribute alanı :values varken zorunludur.',
    'required_with_all'    => ':attribute alanı herhangi bir :values değeri varken zorunludur.',
    'required_without'     => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':attribute alanı :values değerlerinden hiçbiri yokken zorunludur.',
    'prohibited'           => ':attribute alanı yasaklanmıştır.',
    'prohibited_if'        => ':attribute alanı, :other :value olduğunda yasaklanır.',
    'prohibited_unless'    => ':attribute alanı, :other :values içinde olmadıkça yasaklanır.',
    'same'                 => ':attribute ile :other eşleşmelidir.',
    'size'                 => [
        'numeric' => ':attribute :size olmalıdır.',
        'file'    => ':attribute :size kilobyte olmalıdır.',
        'string'  => ':attribute :size karakter olmalıdır.',
        'array'   => ':attribute :size öğe içermelidir.',
    ],
    'starts_with'          => ':attribute şunlardan biriyle başlamalıdır: :values.',
    'string'               => ':attribute karakterlerden oluşmalıdır.',
    'timezone'             => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique'               => ':attribute daha önceden kayıt edilmiş.',
    'uploaded'             => ':attribute yüklemesi başarısız.',
    'url'                  => ':attribute biçimi geçersiz.',
    'uuid'                 => ':attribute geçerli bir UUID olmalıdır.',

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