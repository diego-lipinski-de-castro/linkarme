<?php

return [
    // email.sponsor.bool_true

    'attributes' => [
        'new' => ':attribute was updated to :new',
        'blank' => ':attribute was updated to blank',
        'to_blank' => ':attribute was updated from :old to blank',
        'updated' => ':attribute was updated from :old to :new',
        'bool_true' => 'Now accepts links related to :attribute.',
        'bool_false' => 'Does not accepts links related to :attribute.',

        'pivot' => [
            'new' => ':attribute for :pivot was updated to :new',
            'blank' => ':attribute for :pivot was updated to blank',
            'to_blank' => ':attribute for :pivot was updated from :old to blank',
            'updated' => ':attribute for :pivot was updated from :old to :new',
        ],
    ],
    'sponsor' => [
        'bool_true' => 'Now accepts links related to :attribute.',
        'bool_false' => 'Does not accepts links related to :attribute.'
    ],
    'Recently added' => 'Recently added',
    'Was removed' => 'Was removed',
    'Was deleted' => 'Was deleted',
    'Was restored' => 'Was restored',
];
