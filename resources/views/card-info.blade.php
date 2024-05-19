@php
    $record = $getRecord();
    $transactions = $record ? $record->transactions : [];
    $works = $record ? $record->works : [];

    $completed = [];

    if ($record) {
        foreach ($works as $work) {
            foreach ($work->services as $item) {
                $service = json_decode($item['service'], true);
                if(!isset($completed[$service['id']])) {
                    $completed[$service['id']] = [
                        'name' => $service['name'],
                        'count' => 0
                    ];
                }

                $completed[$service['id']]['count'] += $item['count'];
            }
        }

        $paid = 0;
        foreach($transactions as $transaction) {
            if($transaction->accept) {
                $paid += $transaction->sum;
            } else {
                $paid -= $transaction->sum;
            }
        }
    } else {
        $transactions = [];
        $works = [];
        $completed = [];
        $paid = 0;
    }
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Состояние</title>
    <style>
        .card-info {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
        }
        .card-paid {
            margin-bottom: 20px;
        }
        .card-paid div {
            background: #e0f7fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 16px;
            color: #00796b;
        }
        .card-paid div span {
            font-weight: bold;
        }
        .card-completed {
            background: #fff3e0;
            padding: 10px;
            border-radius: 5px;
        }
        .card-completed ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .card-completed li {
            background: #ffe0b2;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .card-completed li:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<div class="card-info">
    <h1>Состояние</h1>
    @if($paid != 0)
    <div class="card-paid">
        <div>
            Оплачено: <span>{{$paid}}</span>

        </div>

    </div>
    @endif

    @if(!empty($completed))
    <div class="card-completed">
        Выполнено:
        <ul>
            @foreach($completed as $complete)
                <li>{{$complete['name']}} - {{$complete['count']}}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

