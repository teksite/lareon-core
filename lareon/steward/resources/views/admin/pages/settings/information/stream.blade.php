<x-lareon::admin-layout>
    @section('title', __('information'))
    @section('description', __('this page is a brief overview of the application and the system it is running on'))
    <canvas id="system-chart"></canvas>

    @push('footerScripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const api = (path) =>
                fetch('/system-info/' + path).then(r => r.json());

            (async () => {

                // دریافت تاریخچه
                const {charts} = await api('history?points=60');

                const cpu = charts.cpu;
                const memory = charts.memory;

                // ترکیب Datasetهای CPU و RAM
                const datasets = [
                    ...cpu.datasets.map(d => ({
                        ...d,
                        label: 'CPU - ' + d.label
                    })),

                    ...memory.datasets.map(d => ({
                        ...d,
                        label: 'RAM - ' + d.label
                    }))
                ];

                const chart = new Chart(document.getElementById('system-chart'), {
                    type: 'line',

                    data: {
                        labels: cpu.labels,
                        datasets: datasets
                    },

                    options: {
                        responsive: true,
                        animation: false,

                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },

                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                min: 0,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Usage (%)'
                                }
                            }
                        }
                    }
                });

                setInterval(async () => {
                    try {
                        const {charts} = await api('metrics?format=chartjs');

                        const cpu = charts.cpu;
                        const memory = charts.memory;

                        chart.data.labels.push(cpu.labels[0]);

                        cpu.datasets.forEach((d, i) => {
                            chart.data.datasets[i].data.push(d.data[0]);
                        });

                        memory.datasets.forEach((d, i) => {
                            chart.data.datasets[cpu.datasets.length + i]
                                .data.push(d.data[0]);
                        });

                        if (chart.data.labels.length > 60) {
                            chart.data.labels.shift();

                            chart.data.datasets.forEach(d => {
                                d.data.shift();
                            });
                        }

                        chart.update('none');

                    } catch (error) {
                        console.error('Metrics update failed:', error);
                    }

                }, 2000);

            })();
        </script>
    @endpush


</x-lareon::admin-layout>
