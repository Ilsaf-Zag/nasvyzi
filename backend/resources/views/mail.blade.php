<div>
    Имя: {{$data['firstName']}}
</div>
<div>
    Фамилия: {{$data['surname']}}
</div>

<div>
    Телефон: {{$data['phone']}}
</div>
@if($data['companyName'])
    <div>
        Название компании: {{$data['companyName']}}
    </div>
@endif

