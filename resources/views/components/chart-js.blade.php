@props(['name', 'moneyoutdata', 'moneyindata'])


<canvas id="{{$name}}"></canvas>

<script>
    // console.log(<?php echo $moneyoutdata;?>);

    const ctx = document.getElementById({{$name}});
            const data = {
                datasets: [{
                        label: 'Money-out',
                        data: <?php echo $moneyoutdata;?>,
                        borderColor: 'rgb(234, 88, 12)',
                        backgroundColor: 'rgba(234, 88, 12, 0.5)',
                        borderWidth: 3,

                        fill: true
                    },
                    {
                        label: 'Money-in',
                        data: <?php echo $moneyindata;?>,
                        borderColor: 'rgb(132, 204, 22)',
                        backgroundColor: 'rgba(132, 204, 22, 0.5)',
                        borderWidth: 3,
                        fill: true
                    },
                ],

            }

            const config = {
                type: 'line',
                data: data,
                options: {
                    tension: 0.4,
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                parser: 'yyyy-MM-dd'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                        },
                    }
                }
            }

            new Chart(ctx, config);
</script>
