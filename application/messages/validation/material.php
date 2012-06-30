<?
return array(
	'subjectName' => array(
		'not_empty' => 'Укажите название предмета.',
		'max_length' => 'Слишком длинное название предмета.',
	),
	'materialName' => array(
		'not_empty' => 'Укажите название материала.',
		'max_length' => 'Слишком длинное название материала.',
	),
	'access' => array(
		'not_empty' => 'Укажите тип доступа материала.',
		'regex' => 'Недопустимый тип доступа.',
	),
	'url' => array(
		'url' => 'Вы указали неправильную ссылку на сторонний ресурс.',
		'max_length' => 'Слишком длинная ссылка на сторонний ресурс.',
		'checkMaterialExists' => 'Вы не указали файл для загрузки или ссылку на сторонний ресурс.',
	),
	'teacher_id' => array(
		'not_empty' => 'Укажите преподавателя.',
	),
);