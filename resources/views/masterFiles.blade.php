<script>
    window.masterfile = {
        chargeOfMinutes: {{ $items['charge_of_minutes']}},
        minutesPerCharge: {{ $items['minutes_per_charge']}},
        durationPriceMap: {
            @foreach($durations as $duration)
                "{{ $duration->duration_hour }}": {{ $duration->price }},
            @endforeach
        },
        durationMap: {
             @foreach($durations as $duration)
                "{{ $duration->duration_hour }}": "{{ $duration->label }}",
            @endforeach
        },
        socksPrice: {{ $items['socks_price']}}
    }
</script>