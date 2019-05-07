<?php

return [
	'title' => 'Сброс пароля',
	'request_text' => 'На указанный Вами номер будет выслан одноразовый sms-код для сброса постоянного пароля к аккаунту',
	'check_text {0}' => 'На номер {0} был отправлен sms-код',
	
	'request_action' => 'Сбросить пароль',
	'save_password_action' => 'Сохранить пароль',
	'enter_new_password' => 'Пожалуйста, введите ваш новый пароль',
	
	'send_request_success' => 'Проверьте свою почту, мы выслали на нее письмо с инструкцией восстановления пароля.',
	'send_request_error' => 'Извините, мы не смогли сбросить Ваш пароль',
	'new_password_saved_success' => 'Ваш новый пароль был успешно сохранен.',
	
	'no_user_with_email' => 'Не найдено ни одного пользователя по указанной электронной почтой.',
	
	'password_reset_expire {min}' => 'Код активции пароля Вам был выслан ранее. Повторная отправка запроса возможна через {min} мин.',
	'restore_password_sms {activation_code}' => 'Воостановление пароля. Код активции: {activation_code}',
	'not_found_request' => 'Не найдено запроса на восстановление пароля.',
	'invalid_activation_code' => 'Неверный код активации.',
	'email_template_body' => 'Хеш %reset_hash%, для логина %login%',
	'email_template_subject' => 'Восстановление пароля',
	'old_password_message' => 'Совпадает со старым паролем',
	
];