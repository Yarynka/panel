<?php

return [
    'sign_in' => 'Увійти',
    'go_to_login' => 'Перейти до входу',
    'failed' => 'Не вдалося знайти обліковий запис, що відповідає цим обліковим даним.',

    'forgot_password' => [
        'label' => 'Забули пароль?',
        'label_help' => 'Введіть адресу електронної пошти свого облікового запису, щоб отримати вказівки щодо скидання пароля.',
        'button' => 'Відновити рахунок',
    ],
    
 'reset_password' => [
        'button' => 'Скинути та увійти',
    ],

    'two_factor' => [
        'label' => '2-факторний токен',
        'label_help' => 'Для продовження цього облікового запису потрібен другий рівень автентифікації. Введіть код, згенерований вашим пристроєм, щоб заповнити цей логін. ',
        'checkpoint_failed' => 'Двофакторний маркер автентифікації був недійсним.',
    ],

    'throttle' => 'Забагато спроб входу. Повторіть спробу через: секунди секунд. ',
    'password_requirements' => 'Пароль повинен містити не менше 8 символів і повинен бути унікальним для цього веб-сайту.',
    '2fa_must_be_enabled' => 'Адміністратор вимагав увімкнути двофакторну автентифікацію для вашого облікового запису, щоб використовувати панель.',
];
