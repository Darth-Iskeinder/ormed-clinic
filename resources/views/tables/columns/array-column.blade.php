<div>
    @php
        $servicesInfo = '';
    @endphp
    @if($getState())
        @foreach($getState() as $value)
            @php
                $service = json_decode($value['service'], true);
                $count = $value['count'] ?? '';
                $serviceInfo = $service['name'] ?? '';
                if ($count) {
                    $serviceInfo .= " (Количество: $count)";
                }
                $servicesInfo .= $servicesInfo ? '<br>' . $serviceInfo : $serviceInfo;
            @endphp
        @endforeach
    @endif
    {!! $servicesInfo !!}
</div>
