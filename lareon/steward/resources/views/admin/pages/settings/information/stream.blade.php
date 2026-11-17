
<x-lareon::admin-layout>
    @section('title', __('information'))
    @section('description', __('this page is a brief overview of the application and the system it is running on'))

    <x-lareon::editor.tabs.layout>
        <x-lareon::editor.tabs.link title="INFORMATION" :href="route('admin.settings.information.index')" />
        <x-lareon::editor.tabs.link title="STREAM" :href="route('admin.settings.information.stream')" />
    </x-lareon::editor.tabs.layout>

    <canvas id="system-chart"></canvas>

    @push('footerScripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const api = (path) =>
                fetch('/system-info/' + path).then(r => {
                    if (!r.ok) throw new Error('API request failed');
                    return r.json();
                });

            (async () => {
                try {
                    // دریافت اولین اطلاعات برای ساخت Datasetها
                    const { charts } = await api('metrics?format=chartjs');

                    const cpu = charts.cpu;
                    const memory = charts.memory;

                    // ساخت Datasetها بدون داده قبلی
                    const datasets = [
                        ...cpu.datasets.map(d => ({
                            ...d,
                            label: 'CPU - ' + d.label,
                            data: []
                        })),

                        ...memory.datasets.map(d => ({
                            ...d,
                            label: 'RAM - ' + d.label,
                            data: []
                        }))
                    ];

                    const chart = new Chart(
                        document.getElementById('system-chart'),
                        {
                            type: 'line',

                            data: {
                                labels: [],
                                datasets
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
                        }
                    );

                    // افزودن اولین نقطه
                    const addPoint = (charts) => {
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

                        // حداکثر ۶۰ نقطه
                        if (chart.data.labels.length > 60) {
                            chart.data.labels.shift();

                            chart.data.datasets.forEach(d => {
                                d.data.shift();
                            });
                        }

                        chart.update('none');
                    };

                    // نمایش اولین اطلاعات
                    addPoint(charts);

                    // دریافت اطلاعات جدید هر ۲ ثانیه
                    setInterval(async () => {
                        try {
                            const { charts } = await api('metrics?format=chartjs');
                            addPoint(charts);

                        } catch (error) {
                            console.error('Metrics update failed:', error);
                        }
                    }, 2000);

                } catch (error) {
                    console.error('Chart initialization failed:', error);
                }
            })();
        </script>
    @endpush

</x-lareon::admin-layout>
